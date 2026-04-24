<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses email ne correspondent pas.',
                'first_options'  => [
                    'label' => 'Adresse email',
                    'attr' => ['placeholder' => 'exemple@mail.com', 'class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Confirmez votre email',
                    'attr' => ['placeholder' => 'exemple@mail.com', 'class' => 'form-control']
                ],
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire'),
                    new Email(message: 'Format d\'email invalide'),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => '********', 'class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['placeholder' => '********', 'class' => 'form-control']
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Le mot de passe est obligatoire'),
                    new Length(
                        min: 6,
                        minMessage: 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        max: 4096,
                    ),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new NotBlank(message: 'Le nom est obligatoire')]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new NotBlank(message: 'Le prénom est obligatoire')]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'required' => false
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('zip_code', TextType::class, [
                'label' => 'Code postal',
                'required' => false
            ])
           
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte la politique de confidentialité",
                'mapped' => false,
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter les conditions pour vous inscrire.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}