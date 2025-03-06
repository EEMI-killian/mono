<?php
namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
            ])
            ->add('fit', TextType::class, [
                'label' => 'Fit',
                'required' => false,
            ])
            ->add('material', TextType::class, [
                'label' => 'Matériau',
            ])
            ->add('partnerUrl', TextType::class, [
                'required' => false,
                'label' => 'URL du partenaire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}