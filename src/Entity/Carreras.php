<?php

namespace App\Entity;

use App\Repository\CarrerasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Carreras")]
#[ORM\Entity(repositoryClass: CarrerasRepository::class)]
class Carreras
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idCarrera")]
    private ?int $idCarrera = null;

    #[ORM\Column(name:"Vueltas")]
    private ?int $vueltas = null;

    #[ORM\Column(name:"Fecha")]
    private ?string $fecha = null;

    #[ORM\Column(name:"HoraInicio")]
    private ?string $horaInicio = null;

    #[ORM\Column(name:"Estado")]
    private ?string $estado = null;

    public function getIdCarrera(): ?int
    {
        return $this->idCarrera;
    }

    public function getVueltas(): ?int
    {
        return $this->vueltas;
    }

    public function setVueltas(int $vueltas): static
    {
        $this->vueltas = $vueltas;

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

    public function getHoraInicio(): ?string
    {
        return $this->horaInicio;
    }

    public function setHoraInicio(string $horaInicio): static
    {
        $this->horaInicio = $horaInicio;

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
}
