<?php

namespace App\DataFixtures;

use App\Entity\Acompte;
use App\Entity\Client;
use App\Entity\Description;
use App\Entity\Devis;
use App\Entity\Entreprise;
use App\Entity\Facture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $tva = [19.6, 5.5];

        //Je gère les Entreprises
        for ($i = 0; $i < 10; $i++) {
            $entreprise = new Entreprise();
            $entreprise->setIntitule($faker->company)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setAdresse($faker->address)
                ->setCp(mt_rand(11111, 99999))
                ->setVille($faker->city)
                ->setEmail($faker->companyEmail)
                ->setTel($faker->e164PhoneNumber)
                ->setLogo('logoDefault.png')
                ->setPassword($this->encoder->encodePassword($entreprise, 'password'))
                ->setRoles(['ROLE_USER']);


            //Je gère les clients
            for ($j = 0; $j < 5; $j++) {
                $client = new Client();
                $client->setNom($faker->lastName)
                    ->setPrenom($faker->firstName)
                    ->setAdresse($faker->address)
                    ->setCp(mt_rand(11111, 99999))
                    ->setVille($faker->country)
                    ->setEmail($faker->email)
                    ->setTel($faker->e164PhoneNumber)
                    ->setEntreprise($entreprise);

                $manager->persist($client);
            }
            $manager->persist($entreprise);
        }
        $manager->flush();
    }






}
