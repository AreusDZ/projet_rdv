<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\RendezVous;
use DateTime;

class RendezVousTest extends KernelTestCase
{
    private $validator;

    protected function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }
    private function getRendezVous(\DateTime $date = null, string $adresse = null){
        $rendezVous = (new RendezVous())->setDate($date)->setAdresse($adresse);
        return $rendezVous;
    }

// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterDate()
    {
        $rendezVous = $this->getRendezVous(new \DateTime ("18-12-2020 14:15"), null);
        $rendezVous->setDate(new \DateTime ("18-12-2020 14:15"));
        $this->assertEquals(new \DateTime ("18-12-2020 14:15"),$rendezVous->getDate());

    }
    public function testGhetterAndSetterAdresse()
    {
        $rendezVous = $this->getRendezVous(null, "404 rue de la liberté");
        $rendezVous->setAdresse("404 rue de la liberté");
        $this->assertEquals("404 rue de la liberté",$rendezVous->getAdresse());

    }

// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsDateValide()
    {
        $rendezVous = $this->getRendezVous(new \DateTime ("18-12-2020 14:15"), "404 rue de la liberté");
        $rendezVous->setDate(new \DateTime ("18-12-2020 14:15"));
        $errors = $this->validator->validate($rendezVous);

        $this->assertCount(0,$errors);

    }
    public function testIsAdresseValide()
    {
        $rendezVous = $this->getRendezVous(new \DateTime ("18-12-2020 14:15"), "404 rue de la liberté");
        $rendezVous->setAdresse("404 rue de la liberté");
        $errors = $this->validator->validate($rendezVous);

        $this->assertCount(0,$errors);

    }

// ***************** TEST NotBlank ******************************
    public function testNotValidBlank(){
            
        $rendezVous = $this->getRendezVous(null, null);
        $errors = $this->validator->validate($rendezVous);
        $this->assertCount(2, $errors);
    
    }
}