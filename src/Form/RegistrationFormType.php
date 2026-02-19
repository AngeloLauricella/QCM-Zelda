<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d’utilisateur',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom d’utilisateur']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom d’utilisateur doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une adresse e-mail']),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les conditions d’utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter nos conditions.']),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Créez un mot de passe sécurisé',
                        'class' => 'form-input',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                        'class' => 'form-input',
                    ],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
