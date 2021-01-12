<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\Praticien;
use App\Entity\RendezVous;
use App\Entity\RendezVousDTO;
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

    public function searchByIdPatient(int $idPatient) {
        try {
            $rdvsPatient = $this->repository->findBy(['patient' => $idPatient]);
            $rdvsPatientDTO = [];

            foreach ($rdvsPatient as $rdv) {
                $rdvsPatientDTO[] = $this->rendezVousMapper->transformeRendezVousEntityToRendezVousDto($rdv);
            }

            return $rdvsPatientDTO;

        } catch (DriverException $e) {
            throw new RendezVousServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function searchByIdPraticien(int $idPraticien) {
        try {
            $rdvsPraticien = $this->repository->findBy(['praticien' => $idPraticien]);
            $rdvsPraticienDTO = [];

            foreach ($rdvsPraticien as $rdv) {
                $rdvsPraticienDTO[] = $this->rendezVousMapper->transformeRendezVousEntityToRendezVousDto($rdv);
            }

            return $rdvsPraticienDTO;

        } catch (DriverException $e) {
            throw new RendezVousServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function persist(RendezVous $rendezVous, RendezVousDTO $rendezVousDTO) {
        try {
            $patient   = $this->patientRepository->find($rendezVousDTO->getPatient());
            $praticien = $this->praticienRepository->find($rendezVousDTO->getPraticien());
            $rdv = $this->rendezVousMapper->transformeRendezVousDtoToRendezVousEntity($rendezVousDTO, $rendezVous,$patient, $praticien);
            $this->entityManager->persist($rdv);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new RendezVousServiceException($e->getMessage(), $e->getCode());
        }
 
    }

}