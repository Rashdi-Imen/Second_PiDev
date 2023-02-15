<?php

namespace App\Entity;

use App\Repository\CitoyenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitoyenRepository::class)]
class Citoyen extends User
{
   

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

   

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
