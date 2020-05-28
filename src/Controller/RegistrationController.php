<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Recaptcha\RecaptchaValidator;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * Page d'inscription
     *
     * @Route("/creer-un-compte/", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, RecaptchaValidator $recaptcha, MailerInterface $mailer): Response
    {

        // Redirige de force vers l'accueil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        // Création d'un nouvel objet "User"
        $user = new User();

        // Création d'un formulaire d'inscription, lié à l'objet vide créé juste avant
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Liaison des données de requête (POST) avec le formulaire
        $form->handleRequest($request);

        // Si le formulaire a été envoyé
        if ($form->isSubmitted()) {

            // Vérification que le captcha est valide
            $captchaResponse = $request->request->get('g-recaptcha-response', null);

            // Si le captcha est null ou si il est invalide, ajout d'une erreur générale sur le formulaire (qui sera considéré comme échoué après)
            if($captchaResponse == null || !$recaptcha->verify($captchaResponse, $request->server->get('REMOTE_ADDR'))){

                // Ajout de l'erreur au formulaire
                $form->addError(new FormError('Veuillez remplir le Captcha de sécurité'));
            }

            if($form->isValid()){

                // Hydratation du nouveau compte
                $user
                    // Hashage du mot de passe
                    ->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    )
                    // Date actuelle
                    ->setRegistrationDate(new DateTime())
                    // Compte non activé
                    ->setActivated(false)
                    // md5 aléatoire comme token d'activation
                    ->setActivationToken( md5( random_bytes(100) ) )
                ;

                // Sauvegarde du nouveau compte grâce au manager général des entités
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Création d'un email d'activation
                $email = (new TemplatedEmail())
                    ->from(new Address('noreply@immologic.fr', 'Immo Logic'))
                    ->to($user->getEmail())
                    ->subject('Activation de votre compte')
                    ->htmlTemplate('security/emails/activation.html.twig')
                    ->textTemplate('security/emails/activation.txt.twig')
                    ->context([
                        'user' => $user
                    ])
                ;

                // Envoi de l'email
                $mailer->send($email);

                // Message flash de succès
                $this->addFlash('success', 'Votre compte a été créé avec succès ! Un email vous a été envoyé pour activer votre compte.');

                // Redirection de l'utilisateur vers la page de connexion
                return $this->redirectToRoute('app_login');

            }

        }

        // Appel de la vue en envoyant le formulaire à afficher
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
