<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire'),
                    new Email(message : 'Format d\'email invalide'),
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(message: 'Le mot de passe est obligatoire'),
                    new Length(
                        min :6,
                        minMessage : 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                    ),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text', // Affiche un calendrier HTML5
                'label' => 'Date de naissance'
            ])
            ->add('telephone', TelType::class)
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('zip_code', TextType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
