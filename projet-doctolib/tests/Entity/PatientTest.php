<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PatientTest extends KernelTestCase
{
    private $validator;

    public function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }
// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterNom()
    {
        $patient = new Patient();
        $patient->setNom("Dupont");
        $this->assertEquals("Dupont",$patient->getNom());

    }
    public function testGhetterAndSetterPrenom()
    {
        $patient = new Patient();
        $patient->setPrenom("Louis");
        $this->assertEquals("Louis",$patient->getPrenom());

    }
    public function testGhetterAndSetterAge()
    {
        $patient = new Patient();
        $patient->setAge(18);
        $this->assertEquals(18,$patient->getAge());

    }

// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsNomValide()
    {
        $patient = new Patient();
        $patient->setNom("Dupont");
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
    public function testIsPrenomValide()
    {
        $patient = new Patient();
        $patient->setPrenom("Louis");
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
    public function testIsAgeValide()
    {
        $patient = new Patient();
        $patient->setAge(18);
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
}