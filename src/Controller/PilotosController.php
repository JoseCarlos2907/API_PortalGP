<?php

namespace App\Controller;

use App\Repository\PilotosRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pilotos', name: 'pilotos')]
class PilotosController extends AbstractController
{
    #[Route('/', name: 'pilotos_get', methods:['GET'])]
    public function getAllPilotos(PilotosRepository $pilotosRepository): Response
    {
        $pilotos = $pilotosRepository->findAll();
        $pilotosJSON = [];

        foreach ($pilotos as $piloto) {
            $pilotosJSON[$piloto->getIdPiloto()] = [
                'idPiloto'=>$piloto->getIdPiloto(),
                'imgPerfil'=>$piloto->getImgPerfil(),
                'nombre'=>$piloto->getNombre(),
                'apellido'=>$piloto->getApellido(),
                'fechaNac'=>$piloto->getFechaNac(),
                'peso'=>$piloto->getPeso(),
                'altura'=>$piloto->getAltura(),
                'numero'=>$piloto->getNumero(),
                'puntuacion'=>$piloto->getPuntuacion(),
                'idPais'=>$piloto->getIdPais(),
                'idCoche'=>$piloto->getIdCoche(),
            ];
        }
        return $this->json($pilotosJSON);
    }


    #[Route('/{id}', name: 'pilotos_get_id', methods:['GET'])]
    public function getPilotoById($id, PilotosRepository $pilotosRepository): Response
    {
        $piloto = $pilotosRepository->find($id);
        if(!$piloto)
            return $this->json("Piloto no encontrado");
        
        $pilotoJSON = [];
        
        $pilotoJSON= [
            'idPiloto'=>$piloto->getIdPiloto(),
            'imgPerfil'=>$piloto->getImgPerfil(),
            'nombre'=>$piloto->getNombre(),
            'apellido'=>$piloto->getApellido(),
            'fechaNac'=>$piloto->getFechaNac(),
            'peso'=>$piloto->getPeso(),
            'altura'=>$piloto->getAltura(),
            'numero'=>$piloto->getNumero(),
            'puntuacion'=>$piloto->getPuntuacion(),
            'idPais'=>$piloto->getIdPais(),
            'idCoche'=>$piloto->getIdCoche(),
        ];
        return $this->json($pilotoJSON);
    }


    #[Route('/{id}/pais', name: 'pilotos_get_pais', methods:['GET'])]
    public function getPaisPiloto($id, Connection $connection): Response
    {
        $pais = $connection->fetchAllAssociative("SELECT * FROM Paises WHERE IdPais = (SELECT IdPais FROM Pilotos WHERE IdPiloto = $id)")[0];
        if(!$pais)
            return $this->json("Pais no encontrado");
        
        $paisJSON = [];
        
        $paisJSON= [
            'idPais'=>$pais["idPais"],
            'nombre'=>$pais["Nombre"],
            'countryCode'=>$pais["CountryCode"],
        ];
        return $this->json($paisJSON);
    }

    #[Route('/{id}/coche', name: 'pilotos_get_coche', methods:['GET'])]
    public function getCochePiloto($id, Connection $connection): Response
    {
        $coche = $connection->fetchAllAssociative("SELECT * FROM Coches WHERE IdCoche = (SELECT IdCoche FROM Pilotos WHERE IdPiloto = $id)")[0];
        if(!$coche)
            return $this->json("Coche no encontrado");
        
        $cocheJSON = [];

        $cocheJSON= [
            'idCoche'=>$coche["idCoche"],
            'modelo'=>$coche["Modelo"],
            'imgPrincipal'=>$coche["ImgPrincipal"],
            'segundaImagen'=>$coche["SegundaImg"],
            'terceraImagen'=>$coche["TerceraImg"],
            'cuartaImagen'=>$coche["CuartaImg"],
        ];
        return $this->json($cocheJSON);
    }

    #[Route('/{id}/usuarios-seguidores', name: 'pilotos_get_usuarios_seguidores', methods:['GET'])]
    public function getUsuariosSeguidoresPiloto($id, Connection $connection): Response
    {
        $seguidores = $connection->fetchAllAssociative("SELECT * FROM Usuarios_Siguen_Pilotos USP JOIN Usuarios U ON USP.idUsuario = U.idUsuario WHERE USP.idPiloto = $id");
        if(!$seguidores)
            return $this->json("Este piloto no tiene seguidores");
        
        $seguidoresJSON = [];
        
        foreach ($seguidores as $key => $usuario) {
            $seguidoresJSON[] = [
                'idUsuario'=>$usuario["idUsuario"],
                'imgPerfil'=>$usuario["ImgPerfil"],
                'nombre'=>$usuario["Nombre"],
                'apellidos'=>$usuario["Apellidos"],
                'fechaNac'=>$usuario["FechaNac"],
                'nombreUsuario'=>$usuario["NombreUsuario"],
                'correo'=>$usuario["Correo"],
                'rol'=>$usuario["Rol"],
                'temaSeleccionado'=>$usuario["TemaSeleccionado"]
            ];
        }
        return $this->json($seguidoresJSON);
    }

    #[Route('/{id}/comentarios', name: 'pilotos_get_comentarios', methods:['GET'])]
    public function getComentariosPiloto($id, Connection $connection): Response
    {
        $comentarios = $connection->fetchAllAssociative("SELECT * FROM Comentarios_Usuarios_Pilotos_Carreras WHERE idPiloto = $id");
        if(!$comentarios)
            return $this->json("Ningún usuario ha hecho algún comentario eligiendo a este piloto como el mejor de la carrera");
        
        $comentariosJSON = [];

        foreach ($comentarios as $comentario) {
            $comentariosJSON[] = [
                'idCarrera'=>$comentario["idCarrera"],
                'idUsuario'=>$comentario["idUsuario"],
                'idPiloto'=>$comentario["idPiloto"],
                'comentario'=>$comentario["Comentario"]
            ];
        }

        return $this->json($comentariosJSON);
    }
}
