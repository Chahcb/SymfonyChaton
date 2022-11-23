<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    private ?string $Prenom = null;

    #[ORM\OneToMany(mappedBy: 'Proprietaire', targetEntity: AssoChatonProprio::class, orphanRemoval: false)]
    private Collection $proprietaires;

    public function __construct()
    {
        $this->proprietaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    /**
     * @return Collection<int, AssoChatonProprio>
     */
    public function getProprietaire(): Collection
    {
        return $this->proprietaires;
    }

    public function addProprietaire(AssoChatonProprio $proprietaires): self
    {
        if (!$this->proprietaires->contains($proprietaires)) {
            $this->proprietaires->add($proprietaires);
            $proprietaires->setProprietaireId($this);
        }

        return $this;
    }

    public function removeProprietaire(AssoChatonProprio $proprietaires): self
    {
        if ($this->proprietaires->removeElement($proprietaires)) {
            // set the owning side to null (unless already changed)
            if ($proprietaires->getProprietaireId() === $this) {
                $proprietaires->setProprietaireId(null);
            }
        }

        return $this;
    }
}