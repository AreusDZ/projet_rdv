<?php

namespace App\Service;

use App\Entity\Praticien;
use App\Mapper\PraticienMapper;
use App\Repository\PraticienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;

class PraticienService {

    private $repository;
    private $entityManager;
    private $praticienMapper;


    public function __construct(PraticienRepository $repo, EntityManagerInterface $entityManager, PraticienMapper $mapper){
        $this->repository = $repo;
        $this->entityManager = $entityManager;
        $this->praticienMapper = $mapper;
    }

    public function searchAll(){
        try {
            $praticiens = $this->repository->findAll();
            $praticienDTOs = [];
            foreach ($praticiens as $praticien) {
                $praticienDTOs[] = $this->praticienMapper->transformePraticienEntityToPraticienDto($praticien);
            }
            return $praticienDTOs;
        }catch (DriverException $e){
            throw new PraticienServiceException("Un problème technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
        
    }

    public function delete(Praticien $praticien){
        try {
            $this->entityManager->remove($praticien);
            $this->entityManager->flush();
        } catch(DriverException $e){
            throw new PraticienServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

    public function searchById(int $id){
        try {
            $praticien = $this->repository->find($id);
            return $this->praticienMapper->transformePraticienEntityToPraticienDto($praticien);
        } catch(DriverException $e){
            throw new PraticienServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

}