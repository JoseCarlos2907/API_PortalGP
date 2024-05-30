<?php

namespace App\Entity;

use App\Repository\CochesRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name:"Coches")]
#[ORM\Entity(repositoryClass: CochesRepository::class)]
class Coches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idCoche")]
    private ?int $idCoche = null;

    #[ORM\Column(name:"Modelo")]
    private ?string $modelo = null;

    #[ORM\Column(name:"ImgPrincipal")]
    private ?string $imgPrincipal = null;
    
    #[ORM\Column(name:"SegundaImg")]
    private ?string $segundaImagen = null;

    #[ORM\Column(name:"TerceraImg")]
    private ?string $terceraImagen = null;

    #[ORM\Column(name:"CuartaImg")]
    private ?string $cuartaImagen = null;

    #[ORM\Column(name:"idEscuderia")]
    private ?int $idEscuderia = null;

    public function getIdCoche(): ?int
    {
        return $this->idCoche;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getImgPrincipal(): ?string
    {
        return $this->imgPrincipal;
    }

    public function setImgPrincipal(string $imgPrincipal): static
    {
        $this->imgPrincipal = $imgPrincipal;

        return $this;
    }

    public function getSegundaImagen(): ?string
    {
        return $this->segundaImagen;
    }

    public function setSegundaImagen(string $segundaImagen): static
    {
        $this->segundaImagen = $segundaImagen;

        return $this;
    }

    public function getTerceraImagen(): ?string
    {
        return $this->terceraImagen;
    }

    public function setTerceraImagen(string $terceraImagen): static
    {
        $this->terceraImagen = $terceraImagen;

        return $this;
    }

    public function getCuartaImagen(): ?string
    {
        return $this->cuartaImagen;
    }

    public function setCuartaImagen(string $cuartaImagen): static
    {
        $this->cuartaImagen = $cuartaImagen;

        return $this;
    }

    public function getIdEscuderia(): ?int
    {
        return $this->idEscuderia;
    }

    public function setIdEscuderia(?int $escuderia): static
    {
        $this->idEscuderia = $escuderia;

        return $this;
    }
}