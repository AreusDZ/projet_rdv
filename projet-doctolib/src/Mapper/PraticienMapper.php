<?php

namespace App\Mapper;

use App\Entity\Praticien;
use App\Entity\PraticienDTO;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PraticienMapper {

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder; 
    }

    public function transformePraticienDtoToPraticienEntity(PraticienDTO $praticienDTO, Praticien $praticien):Praticien {
        $praticien->setEmail($praticienDTO->getEmail());
        $praticien->setPassword(
            $this->passwordEncoder->encodePassword(
                $praticien,
                $praticienDTO->getPassword()
            )
        );
        $praticien->setNom($praticienDTO->getNom());
        $praticien->setSpecialite($praticienDTO->getSpecialite());

        return $praticien;
    }

    public function transformePraticienEntityToPraticienDto(Praticien $praticien): PraticienDTO{
        $praticienDTO = new PraticienDTO();
        $praticienDTO->setId($praticien->getId());
        $praticienDTO->setNom($praticien->getNom());
        $praticienDTO->setSpecialite($praticien->getSpecialite());
        $praticienDTO->setEmail($praticien->getEmail());
        $praticienDTO->setPassword($praticien->getPassword());

        return $praticienDTO;
    }
}