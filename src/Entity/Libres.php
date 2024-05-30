<?php

namespace App\Entity;

use App\Repository\LibresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Libres")]
#[ORM\Entity(repositoryClass: LibresRepository::class)]
class Libres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idLibre")]
    private ?int $idLibre = null;

    #[ORM\Column(name:"NumeroLibre")]
    private ?int $numeroLibre = null;

    #[ORM\Column(name:"Fecha")]
    private ?string $fecha = null;

    #[ORM\Column(name:"HoraInicio")]
    private ?string $hora = null;

    #[ORM\Column(name:"Estado")]
    private ?string $estado = null;
    
    #[ORM\Column(name:"idCarrera")]
    private ?int $idCarrera = null;


    public function getIdLibre(): ?int
    {
        return $this->idLibre;
    }

    public function getNumeroLibre(): ?int
    {
        return $this->numeroLibre;
    }

    public function setNumeroLibre(int $numeroLibre): static
    {
        $this->numeroLibre = $numeroLibre;

        return $this;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?string
    {
        return $this->hora;
    }

    public function setHora(string $hora): static
    {
        $this->hora = $hora;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getIdCarrera(): ?int
    {
        return $this->idCarrera;
    }

    public function setIdCarrera(int $idCarrera): static
    {
        $this->idCarrera = $idCarrera;

        return $this;
    }
}
