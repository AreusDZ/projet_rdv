<?php

namespace App\Tests\Repository;

use App\Entity\Patient;
use App\DataFixtures\AppFixtures;
use App\DataFixtures\PatientFixtures;
use App\Repository\PatientRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PatientRepositoryTest extends KernelTestCase {

    use FixturesTrait;

    private $repository;

    protected function setUp(){
        self::bootKernel();
        $this->repository = self::$container->get(PatientRepository::class);
    }

    public function testFindBy(){
        $this->loadFixtures([PatientFixtures::class]);
        $patient = $this->repository->findBy(["nom" => "David 1"]);
        $this->assertCount(1, $patient);
    }
    
    public function testFindAll(){

        // Exécution du setUp ..
        // Insérer 5 clients
        $this->loadFixtures([PatientFixtures::class]);
        $patient = $this->repository->findAll();

        $this->assertCount(5, $patient);
        // execution du tearDown ..
    }

    public function testManagerPersist(){
        $this->loadFixtures([AppFixtures::class]);
        $patient = (new Patient())->setNom("test")->setPrenom("TEST")->setAge(18);
        $manager = self::$container->get("doctrine.orm.default_entity_manager");
        $manager->persist($patient);
        $manager->flush();

        $this->assertCount(1, $this->repository->findAll());

    }

    protected function tearDown(){
        // $this->loadFixtures([AppFixtures::class]);
    }

}