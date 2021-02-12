<?php

namespace App\Entity;
Use OpenApi\Annotations as OA;
/**
 * @OA\Schema()
 */
class RendezVousDTO {
    /**
     * @OA\Property(type="number")
     * @var int
     */
    private $id;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $date;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $adresse;
    
    /**
     * @OA\Property(type="number")
     * @var int
     */
    private $patient;

    /**
     * @OA\Property(type="number")
     * @var int
     */
    private $praticien;

    

    public function getId() :?Int {
        return $this->id;
    }

    

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPatient()
    {
        return $this->patient;
    }

    public function setPatient($patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getPraticien()
    {
        return $this->praticien;
    }

    public function setPraticien( $praticien): self
    {
        $this->praticien = $praticien;

        return $this;
    }
}