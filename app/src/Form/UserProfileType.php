<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('profilePicture', FileType::class, [
            'label' => 'Ma photo de profil (JPG, PNG)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File(
                    maxSize: '2M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                    ],
                    mimeTypesMessage: 'Veuillez uploader une image JPG ou PNG valide',
                )
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
