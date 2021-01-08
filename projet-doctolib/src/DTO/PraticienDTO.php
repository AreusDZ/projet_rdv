<?php

namespace App\Entity;

class PraticienDTO {
    private $id;
    private $nom;
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

  
}