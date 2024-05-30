<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name:"Usuarios")]
#[ORM\Entity(repositoryClass: UsuariosRepository::class)]
class Usuarios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idUsuario")]
    private ?int $idUsuario = null;

    #[ORM\Column(name:"ImgPerfil")]
    private ?string $imgPerfil = null;

    #[ORM\Column(name:"Nombre")]
    private ?string $nombre = null;

    #[ORM\Column(name:"Apellidos")]
    private ?string $apellidos = null;

    #[ORM\Column(name:"FechaNac")]
    private ?string $fechaNac = null;

    #[ORM\Column(name:"NombreUsuario")]
    private ?string $nombreUsuario = null;

    #[ORM\Column(name:"Correo")]
    private ?string $correo = null;

    #[ORM\Column(name:"Rol")]
    private ?string $rol = null;

    #[ORM\Column(name:"TemaSeleccionado")]
    private ?int $temaSeleccionado = null;

    #[ORM\Column(name:"idPais")]
    private ?int $idPais = null;


    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $id)
    {
        $this->idUsuario = $id;
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
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

    public function getFechaNac(): ?string
    {
        return $this->fechaNac;
    }

    public function setFechaNac(string $fechaNac): static
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario): static
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }
    
    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getTemaSeleccionado(): ?int
    {
        return $this->temaSeleccionado;
    }

    public function setTemaSeleccionado(int $temaSeleccionado): static
    {
        $this->temaSeleccionado = $temaSeleccionado;

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
