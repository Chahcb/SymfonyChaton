<?php

namespace App\Entity;

use App\Repository\AssoChatonProprioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssoChatonProprioRepository::class)]
class AssoChatonProprio
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'id')]
    #[ORM\JoinColumn(name:'chatons_id', nullable: false)]
    private Chaton $chatons;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'id')]
    #[ORM\JoinColumn(name:'proprietaires_id', nullable: false)]
    private ?Proprietaire $proprietaires = null;


    public function getChatonId(): Chaton
    {
        return $this->chatons;
    }

    public function setChatonId(Chaton $chatons): self
    {
        $this->chatons = $chatons;

        return $this;
    }

    public function getProprietaireId(): ?Proprietaire
    {
        return $this->proprietaires;
    }

    public function setProprietaireId(?Proprietaire $proprietaires): self
    {
        $this->proprietaires = $proprietaires;

        return $this;
    }
}
