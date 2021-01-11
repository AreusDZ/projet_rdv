<?php

namespace App\Mapper;

use App\Entity\Patient;
use App\Entity\Praticien;
use App\Entity\RendezVous;
use App\Entity\RendezVousDTO;


class RendezVousMapper {

    public function transformeRendezVousDtoToRendezVousEntity(RendezVousDTO $rendezVousDTO, RendezVous $rendezVous, Patient $patient,Praticien $praticien):rendezVous {

        $rendezVous->setDate($rendezVousDTO->getDate());
        $rendezVous->setAdresse($rendezVousDTO->getAdresse());
        $rendezVous->setPatient($patient);
        $rendezVous->setPraticien($praticien);

        return $rendezVous;
    }

    public function transformeRendezVousEntityToRendezVousDto(RendezVous $rendezVous): RendezVousDTO{
      

        $rendezVousDTO = new RendezVousDTO();            
        $rendezVousDTO->setDate($rendezVous->getDate());
        $rendezVousDTO->setAdresse($rendezVous->getAdresse());
        $rendezVousDTO->setPatient($rendezVous->getPatient()->getId());
        $rendezVousDTO->setPraticien($rendezVous->getPraticien()->getId());
        
        return $rendezVousDTO;
    }
}