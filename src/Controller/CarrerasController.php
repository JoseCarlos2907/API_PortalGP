<?php

namespace App\Controller;

use App\Entity\Carreras;
use Doctrine\DBAL\Connection;
use App\Repository\CarrerasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/carreras', name: 'carreras')]
class CarrerasController extends AbstractController
{
    #[Route('/', name: 'carreras_get', methods:['GET'])]
    public function getAllCarreras(CarrerasRepository $carrerasRepository): Response
    {
        $carreras = $carrerasRepository->findAll();
        $carrerasJSON = [];

        foreach ($carreras as $carrera) {
            $carrerasJSON[$carrera->getIdCarrera()] = [
                'idCarrera'=>$carrera->getIdCarrera(),
                'vueltas'=>$carrera->getVueltas(),
                'fecha'=>$carrera->getFecha(),
                'horaInicio'=>$carrera->getHoraInicio(),
                'estado'=>$carrera->getEstado()
            ];
        }
        return $this->json($carrerasJSON);
    }

    #[Route('/carrera/{id}', name: 'carreras_get_id', methods:['GET'])]
    public function getCarreraById($id, CarrerasRepository $carrerasRepository): Response
    {
        $carrera = $carrerasRepository->find($id);
        $carreraJSON = [];
        if(!$carrera)
            return $this->json("Carrera no encontrada");

            $carreraJSON= [
                'idCarrera'=>$carrera->getIdCarrera(),
                'vueltas'=>$carrera->getVueltas(),
                'fecha'=>$carrera->getFecha(),
                'horaInicio'=>$carrera->getHoraInicio(),
                'estado'=>$carrera->getEstado()
            ];
        return $this->json($carreraJSON);
    }
    
    #[Route('/{id}/comentarios', name: 'carreras_get_comentarios', methods:['GET'])]
    public function getComentariosCarrera($id, Connection $connection): Response
    {
        $comentarios = $connection->fetchAllAssociative("SELECT COM.idUsuario AS idUsuario, COM.idPiloto AS idPiloto, COM.idCarrera AS idCarrera, COM.Comentario AS Comentario, U.NombreUsuario AS nombreUsuario, U.ImgPerfil AS imgPerfilUsuario, CONCAT(P.Nombre, ' ', P.Apellido) AS nombrePiloto, P.ImgPerfil AS imgPerfilPiloto, PA.Nombre AS nombrePais FROM Comentarios_Usuarios_Pilotos_Carreras COM JOIN Usuarios U ON COM.idUsuario = U.idUsuario JOIN Pilotos P ON COM.idPiloto = P.idPiloto JOIN Carreras C ON COM.idCarrera = C.idCarrera JOIN Circuitos Cir ON Cir.idCircuito = C.idCircuito JOIN Paises PA ON Cir.idPais = PA.idPais WHERE COM.idCarrera = $id");
        if(!$comentarios)
            return $this->json("Esta carrera no tiene comentarios");
        
        $comentariosJSON = [];

        foreach ($comentarios as $comentario) {
            $comentariosJSON[] = [
                'idCarrera'=>$comentario["idCarrera"],
                'nombrePais'=>$comentario["nombrePais"],
                'idUsuario'=>$comentario["idUsuario"],
                'nombreUsuario'=>$comentario["nombreUsuario"],
                'idPiloto'=>$comentario["idPiloto"],
                'nombrePiloto'=>$comentario["nombrePiloto"],
                'comentario'=>$comentario["Comentario"],
                'imgPerfilUsuario'=>$comentario["imgPerfilUsuario"],
                'imgPerfilPiloto'=>$comentario["imgPerfilPiloto"]
            ];
        }

        return $this->json($comentariosJSON);
    }

    #[Route('/{id}/libres', name: 'carreras_get_libres', methods:['GET'])]
    public function getLibresCarrera($id, Connection $connection): Response
    {
        $libres = $connection->fetchAllAssociative("SELECT * FROM Libres WHERE idCarrera = $id");
        $libresJSON = [];
        if(!$libres)
            return $this->json("Libres no encontrados");

            foreach ($libres as $key => $libre) {
                $libresJSON[]= [
                    'idLibre'=>$libre["idLibre"],
                    'numeroLibre'=>$libre["NumeroLibre"],
                    'fecha'=>$libre["Fecha"],
                    'horaInicio'=>$libre["HoraInicio"],
                    'estado'=>$libre["Estado"]
                ];
            }
            
        return $this->json($libresJSON);
    }

    #[Route('/{id}/clasificacion', name: 'carreras_get_clasificacion', methods:['GET'])]
    public function getClasificacionCarrera($id, Connection $connection): Response
    {
        $clasificacion = $connection->fetchAllAssociative("SELECT * FROM Clasificaciones WHERE idCarrera = $id")[0];
        if(!$clasificacion)
            return $this->json("Clasificacion no encontrada");

        return $this->json($clasificacion);
    }
    
    #[Route('/{id}/circuito', name: 'carreras_get_circuito', methods:['GET'])]
    public function getCircuitoCarrera($id, Connection $connection): Response
    {
        $circuito = $connection->fetchAllAssociative("SELECT * FROM Circuitos WHERE idCircuito = (SELECT idCircuito FROM Carreras WHERE idCarrera = $id)")[0];
        $circuitoJSON = [];
        if(!$circuito)
            return $this->json("Circuito no encontrado");

        $circuitoJSON = [
            'idCircuito'=>$circuito["idCircuito"],
            'imgCircuito'=>$circuito["ImgCircuito"],
            'imgSiluetaCircuito'=>$circuito["ImgSiluetaCircuito"],
            'nombre'=>$circuito["Nombre"],
            'longitud'=>$circuito["Longitud"],
            'tipo'=>$circuito["Tipo"]
        ];

        return $this->json($circuitoJSON);
    }

    #[Route('/fechas', name: 'carreras_get_fechas', methods:['GET'])]
    public function getAllFechas(Connection $connection): Response
    {
        $fechas= $connection->fetchAllAssociative("SELECT Fecha FROM Carreras");
        if(!$fechas)
        return $this->json("No hay carreras registradas");
    
        $fechasJSON = [];

        foreach ($fechas as $fecha) {
            $fechasJSON[] = $fecha["Fecha"];
        }


        return $this->json($fechasJSON);
    }

    #[Route('/lista-carreras', name: 'carreras_get_lista_carreras', methods:['GET'])]
    public function getListaCarreras(Connection $connection): Response
    {
        $carreras= $connection->fetchAllAssociative("SELECT C.idCarrera AS id, Ci.ImgSiluetaCircuito AS imgSiluetaCircuito, P.Nombre AS nombrePais, C.Fecha AS fecha, C.HoraInicio AS horaInicio, C.Estado AS estado FROM Carreras C  JOIN Circuitos Ci ON Ci.idCircuito = C.idCircuito JOIN Paises P ON P.idPais = Ci.idPais;");
        if(!$carreras)
            return $this->json("No hay carreras registradas");
    
        $carrerasJSON = [];

        foreach ($carreras as $carrera) {
            $carrerasJSON[] = [
                "id"=>$carrera["id"],
                "imgSiluetaCircuito"=>$carrera["imgSiluetaCircuito"],
                "nombrePais"=>$carrera["nombrePais"],
                "fecha"=>$carrera["fecha"],
                "fechaCadena"=>"",
                "horaInicio"=>$carrera["horaInicio"],
                "estado"=>$carrera["estado"],
                "clase"=>"",
            ];
        }


        return $this->json($carrerasJSON);
    }


    #[Route('/{id}/comentar', name: 'carreras_post_comentar', methods:['POST'])]
    public function comentarCarrera($id, Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $idUsuario = $data["idUsuario"];
        $idCarrera = $data["idCarrera"];

        $existeComentario = $connection->fetchAllAssociative("SELECT * FROM Comentarios_Usuarios_Pilotos_Carreras WHERE idUsuario = $idUsuario AND idCarrera = $idCarrera ");
        if(!$existeComentario){

            $comentario = $data["comentario"];
            $idPiloto = $data["idPiloto"];

            $prep = $connection->prepare("INSERT INTO Comentarios_Usuarios_Pilotos_Carreras(idPiloto, idCarrera, idUsuario, Comentario) VALUES($idPiloto, $idCarrera, $idUsuario, '$comentario')");
            $prep->execute();
            return $this->json("Comentario publicado");
        }else{
            return $this->json("El usuario ya ha comentado en esta carrera");
        }
    }
}
