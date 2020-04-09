<?php

namespace App\Form;

use App\Entity\Description;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prestation', TextType::class,[
                'label'=>"Prestation",
            ])
            ->add('quantite', NumberType::class)
            ->add('unite', ChoiceType::class,[
                'choices'=>[
                    'Unité(s)'=>'Unité(s)',
                    'Mètres Carrés'=>'m²',
                    'Mètre(s)'=> 'm',
                    'Heure(s)'=>'H',
                    'Centimètre(s)'=>'cm',
                    'Gramme(s)'=>'g',
                    'Kilogramme(s)'=>'kg',
                ]
            ])
            ->add('prix', NumberType::class)
            ->add('tva', ChoiceType::class,[
                'choices'=>[
                    'Non assujetti'=> 0,
                    '20%'=>20,
                    '10%'=>10,
                    '5,5%'=>5.5
                ]
            ])
            ->add('montant', NumberType::class)
            ->add('motantTtc', NumberType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Description::class,
        ]);
    }
}
