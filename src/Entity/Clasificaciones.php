<?php

namespace App\Entity;

use App\Repository\ClasificacionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Clasificaciones")]
#[ORM\Entity(repositoryClass: ClasificacionesRepository::class)]
class Clasificaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idClasificacion")]
    private ?int $idClasificacion = null;

    #[ORM\Column(name:"Fecha")]
    private ?string $fecha = null;

    #[ORM\Column(name:"HoraInicio")]
    private ?string $horaInicio = null;

    #[ORM\Column(name:"Estado")]
    private ?string $estado = null;

    #[ORM\Column(name:"idCarrera")]
    private ?int $idCarrera = null;

    public function getIdClasificacion(): ?int
    {
        return $this->idClasificacion;
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
