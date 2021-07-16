<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // instanciation of faker
        $faker = Faker\Factory::create('fr_FR');

        // create 20 user
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPassword('pass_1234');
            $user->setCity($faker->city());
            $user->setZipCode(974);
            $user->setRole('ROLE_USER');
            $user->setPhoneNumber(0102030405);
            $user->setStatus(mt_rand(0, 1));
            $manager->persist($user);
        }

        // create services

        // services name
        $servicesList = [
            "Douche",
            "emplacement tente",
            "Dîner",
            "Petit-déjeuner",
            "Energie",
            "Lit",
            "Abris",
            "Livraison de colis",
            "Eau",
            "Sandwich"
        ];

        $totalServices = count($servicesList);

        foreach ($servicesList as $currentService) {

            $service= new Service();
            $service->setName($currentService);
            $service->setDescription($faker->sentence(4));
            $service->setImage('tent.png');
            $manager->persist($service);
        }

        $manager->flush();
    }
}
