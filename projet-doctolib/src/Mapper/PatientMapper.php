<?php

namespace App\Mapper;

use App\Entity\Patient;
use App\Entity\PatientDTO;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PatientMapper {
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder; 
    }
    
    public function transformPatientDtoToPatientEntity(PatientDTO $patientDTO, Patient $patient) :Patient {
        $patient->setEmail($patientDTO->getEmail());
        $patient->setPassword(
            $this->passwordEncoder->encodePassword(
                $patient,
                $patientDTO->getPassword()
            )
        );
        $patient->setNom($patientDTO->getNom());
        $patient->setPrenom($patientDTO->getPrenom());
        $patient->setAge($patientDTO->getAge());
        
        return $patient;
    }

    public function transformePatientEntityToPatientDto(Patient $patient) :PatientDTO {
        $patientDTO = new PatientDTO();
        $patientDTO->setId($patient->getId());
        $patientDTO->setNom($patient->getNom());
        $patientDTO->setPrenom($patient->getPrenom());
        $patientDTO->setAge($patient->getAge());
        $patientDTO->setEmail($patient->getEmail());
        $patientDTO->setPassword($patient->getPassword());
        
        return $patientDTO;
    }
}