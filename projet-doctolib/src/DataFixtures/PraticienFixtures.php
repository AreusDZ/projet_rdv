<?php

namespace App\DataFixtures;

use App\Entity\Praticien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PraticienFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<=5;$i++){
            $praticien = (new Praticien())->setNom("Dubois$i")->setSpecialite("Podologue$i")->setEmail("doc$i@gmail.com")
            ->setPassword("doc$i");
            $manager->persist($praticien);
        }

        $manager->flush();
    }
}