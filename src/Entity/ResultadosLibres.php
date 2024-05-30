<?php

namespace App\Entity;

use App\Repository\ResultadosLibresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Pilotos_Corren_Libres")]
#[ORM\Entity(repositoryClass: ResultadosLibresRepository::class)]
class ResultadosLibres
{
    #[ORM\Id]
    #[ORM\Column(name:"idPiloto")]
    private ?int $idPiloto = null;

    #[ORM\Id]
    #[ORM\Column(name:"idLibre")]
    private ?int $idLibre = null;

    #[ORM\Column(name:"TiempoVueltaMasRapida")]
    private ?string $tiempoVueltaMasRapida = null;

    #[ORM\Column(name:"PosicionFinal")]
    private ?int $posicionFinal = null;

    public function getIdPiloto(): ?int
    {
        return $this->idPiloto;
    }

    public function getIdLibre(): ?int
    {
        return $this->idLibre;
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
