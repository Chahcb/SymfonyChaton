<?php

namespace App\Entity;

use App\Repository\ChatonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatonRepository::class)]
class Chaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?bool $Sterilise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'chatons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $Categorie = null;

    #[ORM\OneToMany(mappedBy: 'Chaton', targetEntity: AssoChatonProprio::class, orphanRemoval: false)]
    private Collection $chatons;

    public function __construct()
    {
        $this->chatons = new ArrayCollection();
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

    public function isSterilise(): ?bool
    {
        return $this->Sterilise;
    }

    public function setSterilise(bool $Sterilise): self
    {
        $this->Sterilise = $Sterilise;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection<int, AssoChatonProprio>
     */
    public function getChatonId(): Collection
    {
        return $this->chatons;
    }

    public function addChatonId(AssoChatonProprio $chatons): self
    {
        if (!$this->chatons->contains($chatons)) {
            $this->chatons->add($chatons);
            $chatons->setChatonId($this);
        }

        return $this;
    }

    public function removeChatonId(AssoChatonProprio $chatons): self
    {
        if ($this->chatons->removeElement($chatons)) {
            // set the owning side to null (unless already changed)
            if ($chatons->getChatonId() === $this) {
                $chatons->setChatonId(null);
            }
        }

        return $this;
    }
}