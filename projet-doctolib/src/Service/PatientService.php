<?php

namespace App\Service;


use App\Mapper\PatientMapper;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;

class PatientService {

    private $repository;
    private $entityManager;
    private $patientMapper;


    public function __construct(PatientRepository $repo, EntityManagerInterface $entityManager, PatientMapper $mapper){
        $this->repository = $repo;
        $this->entityManager = $entityManager;
        $this->patientMapper = $mapper;
    }

    public function searchAll(){
        try {
            $patients = $this->repository->findAll();
            $patientDTOs = [];
            foreach ($patients as $patient) {
                $patientDTOs[] = $this->patientMapper->transformePatientEntityToPatientDto($patient);
            }
            return $patientDTOs;
        }catch (DriverException $e){
            throw new PatientServiceException("Un problème technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
        
    }

}