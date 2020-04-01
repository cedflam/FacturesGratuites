<?php

namespace App\Form;

use App\Entity\Description;

use Doctrine\DBAL\Types\FloatType;
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
                    'Gramme(s)'=>'grammes',
                    'Kilogramme(s)'=>'kilogrammes',
                    'Centimètre(s)'=>'centimètres',
                    'Mètre(s)'=> 'mètres',
                ]
            ])
            ->add('prix', NumberType::class)
            ->add('tva', ChoiceType::class,[
                'choices'=>[
                    '20%'=>20,
                    '10%'=>10,
                    '5,5%'=>5.5
                ]
            ])
            ->add('montant', NumberType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Description::class,
        ]);
    }
}
