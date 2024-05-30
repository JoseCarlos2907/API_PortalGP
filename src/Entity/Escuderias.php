<?php

namespace App\Entity;

use App\Repository\EscuderiasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Escuderias")]
#[ORM\Entity(repositoryClass: EscuderiasRepository::class)]
class Escuderias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idEscuderia")]
    private ?int $idEscuderia = null;

    #[ORM\Column(name:"ImgLogo")]
    private ?string $imgLogo = null;

    #[ORM\Column(name:"ImgEscuderia")]
    private ?string $imgEscuderia = null;

    #[ORM\Column(name:"Nombre")]
    private ?string $nombre = null;

    #[ORM\Column(name:"Descripcion")]
    private ?string $descripcion = null;

    #[ORM\Column(name:"Puntuacion")]
    private ?int $puntuacion = null;

    #[ORM\Column(name:"idPais")]
    private ?int $idPais = null;

    public function getIdEscuderia(): ?int
    {
        return $this->idEscuderia;
    }

    public function getImgLogo(): ?string
    {
        return $this->imgLogo;
    }

    public function setImgLogo(string $imgLogo): static
    {
        $this->imgLogo = $imgLogo;

        return $this;
    }

    public function getImgEscuderia(): ?string
    {
        return $this->imgEscuderia;
    }

    public function setImgEscuderia(string $imgEscuderia): static
    {
        $this->imgEscuderia = $imgEscuderia;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(int $puntuacion): static
    {
        $this->puntuacion = $puntuacion;

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
