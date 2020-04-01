<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{

    /**
     * Configuration de base d'un champ
     * @param $label
     * @param $placeholder
     * @return array
     */
    public function addOption($label, $placeholder)
    {
        return [
            'label'=>$label,
            'attr'=> [
                'placeholder'=>$placeholder
            ]
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->addOption(
                'Nom', ""
            ))
            ->add('prenom', TextType::class, $this->addOption(
                'Prenom', ""
            ))
            ->add('adresse', TextType::class, $this->addOption(
                'Adresse',""
            ))
            ->add('cp', TextType::class, $this->addOption(
                'Code Postal', ""
            ))
            ->add('ville', TextType::class, $this->addOption(
                'Ville',""
            ))
            ->add('email', EmailType::class, $this->addOption(
                'Email', ""
            ))
            ->add('tel', TextType::class, $this->addOption(
                'Téléphone', ""
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
