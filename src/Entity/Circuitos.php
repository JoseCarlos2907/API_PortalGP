<?php

namespace App\Entity;

use App\Repository\CircuitosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Circuitos")]
#[ORM\Entity(repositoryClass: CircuitosRepository::class)]
class Circuitos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idCircuito")]
    private ?int $idCircuito = null;

    #[ORM\Column(name:"ImgCircuito")]
    private ?string $imgCircuito = null;

    #[ORM\Column(name:"ImgSiluetaCircuito")]
    private ?string $imgSiluetaCircuito = null;

    #[ORM\Column(name:"Nombre")]
    private ?string $nombre = null;

    #[ORM\Column(name:"Longitud")]
    private ?float $longitud = null;

    #[ORM\Column(name:"Tipo")]
    private ?string $tipo = null;

    #[ORM\Column(name:"idPais")]
    private ?int $idPais = null;

    public function getIdCircuito(): ?int
    {
        return $this->idCircuito;
    }

    public function getImgCircuito(): ?string
    {
        return $this->imgCircuito;
    }

    public function setImgCircuito(string $imgCircuito): static
    {
        $this->imgCircuito = $imgCircuito;

        return $this;
    }

    public function getImgSiluetaCircuito(): ?string
    {
        return $this->imgSiluetaCircuito;
    }

    public function setImgSiluetaCircuito(string $imgSiluetaCircuito): static
    {
        $this->imgSiluetaCircuito = $imgSiluetaCircuito;

        return $this;
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

    public function getLongitud(): ?float
    {
        return $this->longitud;
    }

    public function setLongitud(float $longitud): static
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getIdPais(): ?int
    {
        return $this->idPais;
    }

    public function setIdPais(int $idPais): static
    {
        $this->idPais = $idPais;

        return $this;
    }
}
