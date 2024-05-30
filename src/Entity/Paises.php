<?php

namespace App\Entity;

use App\Repository\PaisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Paises")]
#[ORM\Entity(repositoryClass: PaisesRepository::class)]
class Paises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idPais")]
    private ?int $idPais = null;

    #[ORM\Column(name:"Nombre")]
    private ?string $nombre = null;

    #[ORM\Column(name:"CountryCode")]
    private ?string $countryCode = null;


    public function getIdPais(): ?int
    {
        return $this->idPais;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
