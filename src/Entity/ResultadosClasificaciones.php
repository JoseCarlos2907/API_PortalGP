<?php

namespace App\Entity;

use App\Repository\ResultadosClasificacionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Pilotos_Corren_Clasificacion")]
#[ORM\Entity(repositoryClass: ResultadosClasificacionesRepository::class)]
class ResultadosClasificaciones
{
    #[ORM\Id]
    #[ORM\Column(name:"idPiloto")]
    private ?int $idPiloto = null;

    #[ORM\Id]
    #[ORM\Column(name:"idClasificacion")]
    private ?int $idClasificacion = null;

    #[ORM\Column(name:"TiempoVueltaMasRapida")]
    private ?string $tiempoVueltaMasRapida = null;

    #[ORM\Column(name:"PosicionFinal")]
    private ?int $posicionFinal = null;

    public function getIdPiloto(): ?int
    {
        return $this->idPiloto;
    }

    public function getIdClasificacion(): ?int
    {
        return $this->idClasificacion;
    }

    public function getTiempoVueltaMasRapida(): ?string
    {
        return $this->tiempoVueltaMasRapida;
    }

    public function setTiempoVueltaMasRapida(?string $tiempoVueltaMasRapida): static
    {
        $this->tiempoVueltaMasRapida = $tiempoVueltaMasRapida;

        return $this;
    }

    public function getPosicionFinal(): ?int
    {
        return $this->posicionFinal;
    }

    public function setPosicionFinal(?int $posicionFinal): static
    {
        $this->posicionFinal = $posicionFinal;

        return $this;
    }
}
