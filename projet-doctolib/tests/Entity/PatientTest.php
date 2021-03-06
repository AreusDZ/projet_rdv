<?php

namespace App\Tests\Entity;

use App\Entity\Patient;
use App\Entity\RendezVous;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PatientTest extends KernelTestCase
{
    private $validator;

    protected function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }
    private function getPatient(string $nom = null, string $prenom = null, int $age= null){
        $patient = (new Patient())->setNom($nom)->setPrenom($prenom)->setAge($age);
        return $patient;
    }

// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterNom()
    {
        $patient = $this->getPatient("toto", "toto", 18);
        $patient->setNom("Dupont");
        $this->assertEquals("Dupont",$patient->getNom());

    }
    public function testGhetterAndSetterPrenom()
    {
        $patient = $this->getPatient("toto", "toto", 18);
        $patient->setPrenom("Louis");
        $this->assertEquals("Louis",$patient->getPrenom());

    }
    public function testGhetterAndSetterAge()
    {
        $patient = $this->getPatient("toto", "toto", 18);
        $patient->setAge(20);
        $this->assertEquals(20,$patient->getAge());

    }

// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsNomValide()
    {
        $patient = $this->getPatient("Dupont", "David", 18);
        $patient->setNom("Dupont");
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
    public function testIsPrenomValide()
    {
        $patient = $this->getPatient("Dupont", "David", 18);
        $patient->setPrenom("Louis");
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
    public function testIsAgeValide()
    {
        $patient = $this->getPatient("Dupont", "David", 18);
        $patient->setAge(18);
        $errors = $this->validator->validate($patient);

        $this->assertCount(0,$errors);

    }
// ***************** TEST NotBlank ******************************
    public function testNotValidBlank(){
            
        $patient = $this->getPatient(null, null, null);
        $errors = $this->validator->validate($patient);
        $this->assertCount(3, $errors);
    
    }
// ***************** TEST Méthodes relationelles ******************************
    public function testGetEmptyRdv(){
        $patient = $this->getPatient("Dupont", "David", 18);
        $this->assertCount(0, $patient->getRdv());
    }

    public function testGetNotEmptyRdv(){
        $patient = $this->getPatient("Dupont", "David", 18);
        $rdv= (new RendezVous())->setDate(new \DateTime ("18-12-2020 14:15"))->setAdresse("404 rue de la liberté");
        $patient->addRdv($rdv);
        $this->assertCount(1,$patient->getRdv());
        $this->assertEquals($patient,$rdv->getPatient());
    }

    public function testRemoveRdv(){
        $patient = $this->getPatient("Dupont", "David", 18);
        $rdv= (new RendezVous())->setDate(new \DateTime ("18-12-2020 14:15"))->setAdresse("404 rue de la liberté");
        $patient->addRdv($rdv);
        $this->assertCount(1, $patient->getRdv());
        $patient->removeRdv($rdv);
        $this->assertCount(0, $patient->getRdv());
        $this->assertEquals(null, $rdv->getPatient());
    }

}