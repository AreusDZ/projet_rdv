<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PraticienTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }

// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterNom()
    {
        $praticien = new Praticien();
        $praticien->setNom("Dupont");
        $this->assertEquals("Dupont",$praticien->getNom());

    }
// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsNomValide()
    {
        $praticien = new Praticien();
        $praticien->setNom("Dupont");
        $errors = $this->validator->validate($praticien);

        $this->assertCount(0,$errors);

    }
    
}