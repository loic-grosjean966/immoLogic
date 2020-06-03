<?php

namespace App\Form;

use App\Entity\Annonce;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [

                    new NotBlank([
                        'message' => 'Merci de renseigner un titre'
                    ]),

                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('propertyType', ChoiceType::class, [
                'label' => 'Appartement ou maison ?',
                'choices' => [
                    'Appartement' => true,
                    'Maison' => false
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un type de bien'
                    ])
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description du bien',
                'purify_html' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une description'
                    ]),
                    new Length([
                        'min' => 30,
                        'minMessage' => 'La description doit contenir au moins 30 caractères',
                        'max' => 20000,
                        'maxMessage' => 'La description doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('typeOfTransaction', ChoiceType::class, [
                'label' => 'Plutôt location ou vente ?',
                'choices' => [
                    'location' => true,
                    'vente' => false
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un type de transaction'
                    ])
                ]
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Sélectionnez une nouvelle photo',
                'attr' => [
                    'accept' => 'image/jpeg, image/png'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'L\'image doit être de type jpg ou png',
                        'maxSizeMessage' => 'Fichier trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisée est {{ limit }}{{ suffix }}',
                    ]),
                    new NotBlank([
                        'message' => 'Vous devez sélectionner un fichier',
                    ])
                ]
            ])

            ->add('rooms')
            ->add('save', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}