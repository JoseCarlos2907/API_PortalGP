<?php

namespace App\Entity;

use App\Repository\ResultadosCarrerasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Pilotos_Corren_Carreras")]
#[ORM\Entity(repositoryClass: ResultadosCarrerasRepository::class)]
class ResultadosCarreras
{
    #[ORM\Id]
    #[ORM\Column(name:"idPiloto")]
    private ?int $idPiloto = null;

    #[ORM\Id]
    #[ORM\Column(name:"idCarrera")]
    private ?int $idCarrera = null;

    #[ORM\Column(name:"TiempoTotalEnCarrera")]
    private ?string $tiempoTotalEnCarrera = null;

    #[ORM\Column(name:"PosicionFinal")]
    private ?int $posicionFinal = null;

    public function getIdPiloto(): ?int
    {
        return $this->idPiloto;
    }

    public function getIdCarrera(): ?int
    {
        return $this->idCarrera;
    }

    public function getTiempoTotalEnCarrera(): ?string
    {
        return $this->tiempoTotalEnCarrera;
    }

    public function setTiempoTotalEnCarrera(?string $tiempoTotalEnCarrera): static
    {
        $this->tiempoTotalEnCarrera = $tiempoTotalEnCarrera;

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
