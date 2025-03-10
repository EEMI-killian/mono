<?php

namespace App\Form;

use App\Entity\Outfit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class OutfitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la tenue',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Ajouter votre image',
                'mapped' => false,
                'required' => $options['is_edit'] ? false : true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Please upload an image file',
                    ]),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ])
            ->add('isPublic', null, [
                'label' => 'Rendre publique',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outfit::class,
            'is_edit' => false,

        ]);
    }
}
