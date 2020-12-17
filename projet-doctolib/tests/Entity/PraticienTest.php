<?php

namespace App\Tests\Entity;

use App\Entity\Praticien;
use App\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PraticienTest extends KernelTestCase
{
    private $validator;

    protected function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }
    private function getPraticien(string $nom = null, string $specialite = null){
        $praticien = (new Praticien())->setNom($nom)->setSpecialite($specialite);
        return $praticien;
    }

// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterNom()
    {
        $praticien = $this->getPraticien("toto", "Podologue");
        $praticien->setNom("Dupont");
        $this->assertEquals("Dupont",$praticien->getNom());

    }
    public function testGhetterAndSetterSpecialite()
    {
        $praticien = $this->getPraticien("toto", "Podologue");
        $praticien->setSpecialite("Louis");
        $this->assertEquals("Louis",$praticien->getSpecialite());

    }

// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsNomValide()
    {
        $praticien = $this->getPraticien("toto", "Podologue");
        $praticien->setNom("Louis");
        $errors = $this->validator->validate($praticien);

        $this->assertCount(0,$errors);

    }
    public function testIsSpecialiteValide()
    {
        $praticien = $this->getPraticien("toto", "Podologue");
        $praticien->setSpecialite("Pediatre");
        $errors = $this->validator->validate($praticien);

        $this->assertCount(0,$errors);

    }

// ***************** TEST NotBlank ******************************
    public function testNotValidBlank(){
            
        $praticien = $this->getPraticien(null, null);
        $errors = $this->validator->validate($praticien);
        $this->assertCount(2, $errors);
    
    }
// ***************** TEST Méthodes relationelles ******************************

public function testGetEmptyRdv(){
    $praticien = $this->getPraticien("toto", "Podologue");
    $this->assertCount(0, $praticien->getRdv());
}

public function testGetNotEmptyRdv(){
    $praticien = $this->getPraticien("toto", "Podologue");
    $rdv= (new RendezVous())->setDate(new \DateTime ("18-12-2020 14:15"))->setAdresse("404 rue de la liberté");
    $praticien->addRdv($rdv);
    $this->assertCount(1,$praticien->getRdv());
    $this->assertEquals($praticien,$rdv->getPraticien());
}

public function testRemoveRdv(){
    $praticien = $this->getPraticien("toto", "Podologue");
    $rdv= (new RendezVous())->setDate(new \DateTime ("18-12-2020 14:15"))->setAdresse("404 rue de la liberté");
    $praticien->addRdv($rdv);
    $this->assertCount(1, $praticien->getRdv());
    $praticien->removeRdv($rdv);
    $this->assertCount(0, $praticien->getRdv());
    $this->assertEquals(null, $rdv->getPraticien());
}
}