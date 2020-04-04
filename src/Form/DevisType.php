<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('description', CollectionType::class,[
                'entry_type'=>DescriptionType::class,
                'allow_add'=>true,
                'allow_delete'=>true
            ])
            ->add('montantHt', NumberType::class,[
                'label'=>"Total HT"
            ])
            ->add('montantTtc', NumberType::class, [
                'label'=>"Total TTC"
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
