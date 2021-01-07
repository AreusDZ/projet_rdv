<?php

namespace App\Mapper;

use App\Entity\Patient;
use App\Entity\PatientDTO;


class PatientMapper {

    public function transformePatientDtoToPatientEntity(PatientDTO $patientDTO, Patient $patient):Patient {

        $patient->setNom($patientDTO->getNom());
        $patient->setPrenom($patientDTO->getPrenom());
        $patient->setAge($patientDTO->getAge());
        return $patient;
    }

    public function transformePatientEntityToPatientDto(Patient $patient): PatientDTO{
        $patientDTO = new PatientDTO();
        $patientDTO->setNom($patient->getNom());
        $patientDTO->setPrenom($patient->getPrenom());
        $patientDTO->setAge($patient->getAge());
        return $patientDTO;
    }
}