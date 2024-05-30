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

    #[Route('/{id}', name: 'carreras_get_id', methods:['GET'])]
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
        $comentarios = $connection->fetchAllAssociative("SELECT * FROM Comentarios_Usuarios_Pilotos_Carreras WHERE idCarrera = $id");
        if(!$comentarios)
            return $this->json("Ningún usuario ha comentado en esta carrera");
        
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
}
