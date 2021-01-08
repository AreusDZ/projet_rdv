<?php

namespace App\Mapper;

use App\Entity\Praticien;
use App\Entity\PraticienDTO;


class PraticienMapper {

    public function transformePraticienDtoToPraticienEntity(PraticienDTO $praticienDTO, Praticien $praticien):Praticien {

        $praticien->setNom($praticienDTO->getNom());
        $praticien->setSpecialite($praticienDTO->getSpecialite());
        return $praticien;
    }

    public function transformePraticienEntityToPraticienDto(Praticien $praticien): PraticienDTO{
        $praticienDTO = new PraticienDTO();
        $praticienDTO->setNom($praticien->getNom());
        $praticienDTO->setSpecialite($praticien->getSpecialite());
        return $praticienDTO;
    }
}