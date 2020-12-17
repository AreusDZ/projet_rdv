<?php

namespace App\DataFixtures;

use App\Repository\PatientRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $repository;

    public function __construct(PatientRepository $repo )
    {
        $this->repository = $repo;
    }
    public function load(ObjectManager $manager)
    {
        $patient = $this->repository->findAll();
        foreach ($patient as $p){
            $manager->remove($p);
        }

        $manager->flush();
    }
}
