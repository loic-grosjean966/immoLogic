<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceFormType;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/nouvelle-annonce/", name="new_annonce")
     */
    public function newPublication(Request $request)
    {

        // Création d'un nouvel article vide
        $newAnnonce = new Annonce();

        // Création d'un formulaire de création d'article, lié à l'article vide
        $form = $this->createForm(AnnonceFormType::class, $newAnnonce);

        // Liaison des données de requête (POST) avec le formulaire
        $form->handleRequest($request);

        // Si le formulaire est envoyé et n'a pas d'erreur
        if($form->isSubmitted() && $form->isValid()){

            // Hydratation de l'article
            $newAnnonce
                ->setAuthor($this->getUser())   // L'auteur est l'utilisateur connecté
                ->setPublicationDate(new DateTime())    // Date actuelle
            ;

            // Sauvegarde de l'article en base de données via le manage général des entités
            $em = $this->getDoctrine()->getManager();
            $em->persist($newAnnonce);
            $em->flush();

            // Message flash de type "success"
            $this->addFlash('success', 'Annonce publiée avec succès !');

            // Redirection de l'utilisateur vers la page détaillée de l'article tout nouvellement créé
            return $this->redirectToRoute('blog_publication_view', [
                'slug' => $newAnnonce->getSlug()
            ]);

        }

        // Appel de la vue en lui envoyant le formulaire à afficher
        return $this->render('annonce/newAnnonce.html.twig', [
            'form' => $form->createView()
        ]);
    }
}