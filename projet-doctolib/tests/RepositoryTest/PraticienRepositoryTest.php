<?php

namespace App\Tests\Repository;

use App\Entity\Praticien;
use App\DataFixtures\AppFixtures;
use App\DataFixtures\PraticienFixtures;
use App\Repository\PraticienRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PraticienRepositoryTest extends KernelTestCase {

    use FixturesTrait;

    private $repository;

    protected function setUp(): void{
        self::bootKernel();
        $this->repository = self::$container->get(PraticienRepository::class);
    }

    public function testFindBy(){
        $this->loadFixtures([PraticienFixtures::class]);
        $praticien = $this->repository->findByNom(["Dubois1"]);
        $this->assertCount(1, $praticien);
    }
    
    public function testFindAll(){

        // Exécution du setUp ..
        // Insérer 5 clients
        $this->loadFixtures([PraticienFixtures::class]);
        $praticien = $this->repository->findAll();

        $this->assertCount(6, $praticien);
        // execution du tearDown ..
    }

    public function testManagerPersist(){
        $this->loadFixtures([AppFixtures::class]);
        $praticien = (new Praticien())->setNom("Dubois")->setSpecialite("Podologue")->setEmail("sam@gmail.com")->setPassword("sam");
        $manager = self::$container->get("doctrine.orm.default_entity_manager");
        $manager->persist($praticien);
        $manager->flush();

        $this->assertCount(1, $this->repository->findAll());

    }

    protected function tearDown(){
        $this->loadFixtures([AppFixtures::class]);
    }

}