<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ email
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email {{ value }} n\'est pas une adresse valide'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse email'
                    ])
                ],
            ])
            //Champ nom de famille
            ->add('name', TextType::class, [
                'label' => 'Nom de famille',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 40,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner un nom de famille'
                    ])
                ]
            ])
            //Champ prénom
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 40,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le prénom doit contenir au maximum {{ limit }} caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner un prénom'
                    ])
                ]
            ])
            //champ de mot de passe et de confirmation mot de passe
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne correspond pas à sa confirmation',
                'first_options' => [
                    'label' => 'Mot de passe'
                ],
                'second_options' =>[
                    'label' => 'Confirmation du mot de passe'
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                        'maxMessage' => 'Mot de passe trop grand'
                    ]),
                    new Regex([
                        'pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[ !\"\#\$%&\'\(\)*+,\-.\/:;<=>?@[\\^\]_`\{|\}~])^.{8,4096}$/",
                        'message' => 'Votre mot de passe doit contenir obligatoirement une minuscule, une majuscule, un chiffre et un caractère spécial'
                    ])
                ],
            ])
            //Adresse postale
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new Length([
                        'min' => 15,
                        'max' => 60,
                        'minMessage' => 'Votre adresse doit faire au moins {{ limit }} carctères',
                        'maxMessage' => 'Votre adresse doit faire au maximum {{ limit }} caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez renseigner une adresse postale'
                    ]),
                    new Regex([
                        'pattern' => "/^([0-9a-z'àâéèêôùûçÀÂÉÈÔÙÛÇ\s-]{15,60})$/i",
                        'message' => 'Votre adresse postale est invalide'
                    ])
                ]
            ])
            //Numéro de téléphone
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 15,
                        'minMessage' => 'Votre numéro de téléphone doit contenir au moins 10 caractères',
                        'maxMessage' => 'Votre numéro de téléphone doit contenir au maximum 15 caractères'
                    ]),
                    new Regex([
                        'pattern' => "/^(\d{2}[ -.]?){4}\d{2}$/",
                        'message' => 'Votre numéro de téléphone est invalide'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre numéro de téléphone'
                    ])
                ]
            ])

            // Bouton de validation
            ->add('save', SubmitType::class, [
                'label' => 'Créer mon compte',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
            
        ]);
    }
}