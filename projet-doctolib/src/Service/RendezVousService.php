<?php

namespace App\Service;

use App\Entity\RendezVous;
use App\Mapper\RendezVousMapper;
use App\Repository\PatientRepository;
use App\Repository\PraticienRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;

class RendezVousService {

    private $repository;
    private $entityManager;
    private $rendezVousMapper;
    private $patientRepository;
    private $praticienRepository;


    public function __construct(RendezVousRepository $repo,PatientRepository $patientRepository,PraticienRepository $praticienRepository, EntityManagerInterface $entityManager, RendezVousMapper $mapper){
        $this->repository = $repo;
        $this->entityManager = $entityManager;
        $this->rendezVousMapper = $mapper;
        $this->patientRepository = $patientRepository;
        $this->praticienRepository = $praticienRepository;
    }

    public function searchAll(){
        try {
            $rendezVouss = $this->repository->findAll();
            $rendezVousDTOs = [];
            foreach ($rendezVouss as $rendezVous) {
                $rendezVousDTOs[] = $this->rendezVousMapper->transformeRendezVousEntityToRendezVousDto($rendezVous);
            }
            return $rendezVousDTOs;
        }catch (DriverException $e){
            throw new RendezVousServiceException("Un problème technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
        
    }

    public function delete(RendezVous $rendezVous){
        try {
            $this->entityManager->remove($rendezVous);
            $this->entityManager->flush();
        } catch(DriverException $e){
            throw new RendezVousServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

    public function searchById(int $id){
        try {
            $rendezVous = $this->repository->find($id);
            return $this->rendezVousMapper->transformeRendezVousEntityToRendezVousDto($rendezVous);
        } catch(DriverException $e){
            throw new RendezVousServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

}