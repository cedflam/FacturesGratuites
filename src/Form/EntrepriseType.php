<?php

namespace App\Form;

use App\Entity\Entreprise;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\SerializerInterface;

class EntrepriseType extends AbstractType
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
            ->add('intitule', TextType::class, $this->addOption(
                "Nom de l'entreprise",
                    "Entrez le nom de l'entreprise si nécessaire."
            ))
            ->add('logo', FileType::class, [
                'label'=>'Logo',
                'data_class'=>null,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>"Logo de l'entreprise."
                ]

                ])
            ->add('nom', TextType::class, $this->addOption(
                'Nom',
                'Entrez votre nom de famille.'
            ))
            ->add('prenom', TextType::class, $this->addOption(
                'Prénom',
                'Entrez votre prénom.'
            ))
            ->add('adresse', TextType::class, $this->addOption(
                'Adresse',
                "Entrez votre adresse ou l'adresse du siège social."
            ))
            ->add('cp', NumberType::class, $this->addOption(
                'Code Postal',
                "Entrez votre code postal ou celui du siège social."
            ))
            ->add('ville', TextType::class,$this->addOption(
                'Ville',
                "Entrez la ville correspondante au code postal."
            ))
            ->add('tel', NumberType::class, $this->addOption(
                'n° de Téléphone',
                "Entrez votre numéro de téléphone."
            ))
            ->add('email', EmailType::class, $this->addOption(
                'Email',
                "Entrez votre adresse email."
            ))
            ->add('password', PasswordType::class, $this->addOption(
                'Mot de passe',
                "Entrez votre mot de passe (Supérieur à 6 caractères)."
            ))
            ->add('confirmPassword', PasswordType::class, $this->addOption(
                'Confirmation du mot de passe',
                "Entrez à nouveau le mot de passe."
            ))

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
