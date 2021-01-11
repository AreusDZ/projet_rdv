<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PraticienRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PraticienRepository::class)
 */
class Praticien extends User
{

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $specialite;

    /**
     * @ORM\OneToMany(targetEntity=RendezVous::class, mappedBy="praticien")
     */
    private $rdv;

    public function __construct()
    {
        $this->rdv = new ArrayCollection();
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection|RendezVous[]
     */
    public function getRdv(): Collection
    {
        return $this->rdv;
    }

    public function addRdv(RendezVous $rdv): self
    {
        if (!$this->rdv->contains($rdv)) {
            $this->rdv[] = $rdv;
            $rdv->setPraticien($this);
        }

        return $this;
    }

    public function removeRdv(RendezVous $rdv): self
    {
        if ($this->rdv->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getPraticien() === $this) {
                $rdv->setPraticien(null);
            }
        }

        return $this;
    }
}
