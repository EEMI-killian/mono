<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Outfit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Item1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('brand')
            ->add('color')
            ->add('type')
            ->add('fit')
            ->add('material')
            ->add('isPublic')
            ->add('OutfitId', EntityType::class, [
                'class' => Outfit::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('userId', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
