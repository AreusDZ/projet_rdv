<?php

namespace App\Entity;
use App\Entity\User;
Use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class PraticienDTO extends User {
    /**
    * @OA\Property(type="number")
    * @var int
    */
    private $id;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $nom;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $specialite;
    

    public function getId() :?Int {
        return $this->id;
    }

    public function getNom() :?string {
        return $this->nom;
    }
    public function setNom(?string $nom) :self {
        $this->nom = $nom;
        return $this;
    }

    public function getSpecialite() :?string {
        return $this->specialite;
    }
    public function setSpecialite(?string $specialite) :self {
        $this->specialite = $specialite;
        return $this;
    }
    public function setId(Int $id) :?Int {
        return $this->id = $id;
    }
  
}