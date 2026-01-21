<?php

namespace App\Form;

use App\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GalleryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez entrer un titre'
                    ),
                    new Length(
                        min: 3,
                        max: 255,
                        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
                        maxMessage: 'Le titre ne doit pas dépasser {{ limit }} caractères',
                    ),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de l\'image',
                ],
            ])

            ->add('imagePath', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => $options['require_image'],
                'constraints' => $options['require_image'] ? [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, GIF, WebP)',
                    ]),
                ] : [],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
            'require_image' => true,
        ]);
    }
}
