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


    #[Route('/piloto/{id}', name: 'pilotos_get_id', methods:['GET'])]
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

    #[Route('/datos-clasificacion-oficial', name: 'pilotos_get_datos_clas_glob', methods:['GET'])]
    public function getDatosClasificacion(Connection $connection): Response
    {
        $pilotos = $connection->fetchAllAssociative("SELECT P.idPiloto AS idPiloto, P.Nombre AS nombre, P.Apellido AS apellido, P.ImgPerfil AS imgPerfil, P.Puntuacion AS puntosTotales, E.Nombre AS nombreEscuderia, PA.CountryCode AS paisCC FROM  Pilotos P JOIN  Coches C ON P.idCoche = C.idCoche JOIN  Escuderias E ON C.idEscuderia = E.idEscuderia JOIN  Paises PA ON P.idPais = PA.idPais ORDER BY puntosTotales DESC");

        if(!$pilotos)
            return $this->json("No hay registros");
        
        $pilotosJSON = [];

        foreach ($pilotos as $piloto) {
            $pilotosJSON[] = [
                'idPiloto'=>$piloto["idPiloto"],
                'nombre'=>$piloto["nombre"],
                'apellido'=>$piloto["apellido"],
                'imgPerfil'=>$piloto["imgPerfil"],
                'puntosTotales'=>$piloto["puntosTotales"],
                'nombreEscuderia'=>$piloto["nombreEscuderia"],
                'paisCC'=>$piloto["paisCC"],
            ];
        }

        return $this->json($pilotosJSON);
    }

    #[Route('/datos-clasificacion-comunidad', name: 'pilotos_get_datos_clas_com', methods:['GET'])]
    public function getDatosPilotosClasificacionComunidad(Connection $connection): Response
    {
        $pilotos = $connection->fetchAllAssociative("SELECT P.idPiloto AS idPiloto, P.Nombre AS nombre, P.Apellido AS apellido, P.ImgPerfil AS imgPerfil, COUNT(CM.Comentario) AS votosTotales, E.Nombre AS nombreEscuderia, PA.CountryCode AS paisCC FROM Pilotos P JOIN Coches C ON P.idCoche = C.idCoche JOIN Escuderias E ON C.idEscuderia = E.idEscuderia JOIN Paises PA ON P.idPais = PA.idPais LEFT JOIN Comentarios_Usuarios_Pilotos_Carreras CM ON P.idPiloto = CM.idPiloto GROUP BY P.idPiloto, P.Nombre, P.Apellido, P.ImgPerfil, E.Nombre, PA.CountryCode ORDER BY votosTotales DESC");
        if(!$pilotos)
            return $this->json("No hay registros");
        
        $pilotosJSON = [];

        foreach ($pilotos as $piloto) {
            $pilotosJSON[] = [
                'idPiloto'=>$piloto["idPiloto"],
                'nombre'=>$piloto["nombre"],
                'apellido'=>$piloto["apellido"],
                'imgPerfil'=>$piloto["imgPerfil"],
                'votosTotales'=>$piloto["votosTotales"],
                'nombreEscuderia'=>$piloto["nombreEscuderia"],
                'paisCC'=>$piloto["paisCC"],
            ];
        }

        return $this->json($pilotosJSON);
    }

    #[Route('/{id}/puntuaciones', name: 'pilotos_get_puntuaciones', methods:['GET'])]
    public function getPuntuacionesPiloto($id, Connection $connection): Response
    {
        $posicionesPiloto = $connection->fetchAllAssociative("SELECT R.PosicionFinal FROM Carreras C JOIN Pilotos_Corren_Carreras R ON C.idCarrera = R.idCarrera WHERE R.idPiloto = $id");
        if(!$posicionesPiloto)
            return $this->json("Este piloto aun no ha saltado a la pista");
        
        $puntuaciones = [];

        foreach ($posicionesPiloto as $posicion) {
            $puntuacion = 0;
            switch ($posicion["PosicionFinal"]) {
                case 1:
                    $puntuacion = 25;
                    break;
                case 2:
                    $puntuacion = 18;
                    break;
                case 3:
                    $puntuacion = 15;
                    break;
                case 4:
                    $puntuacion = 12;
                    break;
                case 5:
                    $puntuacion = 10;
                    break;
                case 6:
                    $puntuacion = 8;
                    break;
                case 7:
                    $puntuacion = 6;
                    break;
                case 8:
                    $puntuacion = 4;
                    break;
                case 9:
                    $puntuacion = 2;
                    break;
                case 10:
                    $puntuacion = 1;
                    break;
                
                default:
                    $puntuacion = 0;
                    break;
            }
            $puntuaciones[] = $puntuacion;
        }

        return $this->json($puntuaciones);
    }
}
