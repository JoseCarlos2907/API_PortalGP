<?php

namespace App\Entity;

use App\Repository\PilotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Pilotos")]
#[ORM\Entity(repositoryClass: PilotosRepository::class)]
class Pilotos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idPiloto")]
    private ?int $idPiloto = null;

    #[ORM\Column(name:"ImgPerfil")]
    private ?string $imgPerfil = null;
    
    #[ORM\Column(name:"Nombre")]
    private ?string $nombre = null;

    #[ORM\Column(name:"Apellido")]
    private ?string $apellido = null;

    #[ORM\Column(name:"FechaNac")]
    private ?string $fechaNac = null;

    #[ORM\Column(name:"Peso")]
    private ?float $peso = null;

    #[ORM\Column(name:"Altura")]
    private ?float $altura = null;

    #[ORM\Column(name:"Numero")]
    private ?int $numero = null;

    #[ORM\Column(name:"Puntuacion")]
    private ?int $puntuacion = null;

    #[ORM\Column(name:"idPais")]
    private ?int $idPais = null;

    #[ORM\Column(name:"idCoche")]
    private ?int $idCoche = null;

    // #[ORM\ManyToOne(inversedBy: 'pilotos')]
    // private ?Paises $pais = null;

    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    // private ?Coches $coche = null;

    // /**
    //  * @var Collection<int, Usuarios>
    //  */
    // #[ORM\ManyToMany(targetEntity: Usuarios::class, mappedBy: 'pilotosSeguidos')]
    // private Collection $usuariosSeguidores;

    // #[ORM\ManyToOne(inversedBy: 'piloto')]
    // private ?Comentarios $comentarios = null;

    // public function __construct()
    // {
    //     $this->usuariosSeguidores = new ArrayCollection();
    // }

    public function getIdPiloto(): ?int
    {
        return $this->idPiloto;
    }

    public function getImgPerfil(): ?string
    {
        return $this->imgPerfil;
    }

    public function setImgPerfil(string $imgPerfil): static
    {
        $this->imgPerfil = $imgPerfil;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getFechaNac(): ?string
    {
        return $this->fechaNac;
    }

    public function setFechaNac(string $fechaNac): static
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getAltura(): ?float
    {
        return $this->altura;
    }

    public function setAltura(float $altura): static
    {
        $this->altura = $altura;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

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

    // public function getPais(): ?Paises
    // {
    //     return $this->pais;
    // }

    // public function setPais(?Paises $pais): static
    // {
    //     $this->pais = $pais;

    //     return $this;
    // }

    // public function getCoche(): ?Coches
    // {
    //     return $this->coche;
    // }

    // public function setCoche(?Coches $coche): static
    // {
    //     $this->coche = $coche;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Usuarios>
    //  */
    // public function getUsuariosSeguidores(): Collection
    // {
    //     return $this->usuariosSeguidores;
    // }

    // public function addUsuariosSeguidore(Usuarios $usuariosSeguidore): static
    // {
    //     if (!$this->usuariosSeguidores->contains($usuariosSeguidore)) {
    //         $this->usuariosSeguidores->add($usuariosSeguidore);
    //         $usuariosSeguidore->addPilotosSeguido($this);
    //     }

    //     return $this;
    // }

    // public function removeUsuariosSeguidore(Usuarios $usuariosSeguidore): static
    // {
    //     if ($this->usuariosSeguidores->removeElement($usuariosSeguidore)) {
    //         $usuariosSeguidore->removePilotosSeguido($this);
    //     }

    //     return $this;
    // }

    // public function getComentarios(): ?Comentarios
    // {
    //     return $this->comentarios;
    // }

    // public function setComentarios(?Comentarios $comentarios): static
    // {
    //     $this->comentarios = $comentarios;

    //     return $this;
    // }

    public function getIdPais(): ?int
    {
        return $this->idPais;
    }

    public function setIdPais(int $idPais): static
    {
        $this->idPais = $idPais;

        return $this;
    }

    public function getIdCoche(): ?int
    {
        return $this->idCoche;
    }

    public function setIdCoche(int $idCoche): static
    {
        $this->idCoche = $idCoche;

        return $this;
    }
}
