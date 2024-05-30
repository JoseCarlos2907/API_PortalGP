<?php

namespace App\Controller;

use App\Repository\ClasificacionesRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clasificaciones', name: 'clasificaciones')]
class ClasificacionesController extends AbstractController
{
    #[Route('/', name: 'clasificaciones_get', methods:['GET'])]
    public function getAllClasificaciones(ClasificacionesRepository $clasificacionesRepository): Response
    {
        $clasificaciones = $clasificacionesRepository->findAll();
        $clasificacionesJSON = [];

        foreach ($clasificaciones as $clasificacion) {
            $clasificacionesJSON[$clasificacion->getIdClasificacion()] = [
                'idClasificacion'=>$clasificacion->getIdClasificacion(),
                'fecha'=>$clasificacion->getFecha(),
                'horaInicio'=>$clasificacion->getHoraInicio(),
                'estado'=>$clasificacion->getEstado(),
            ];
        }
        return $this->json($clasificacionesJSON);
    }

    #[Route('/{id}', name: 'clasificaciones_get_id', methods:['GET'])]
    public function getClasificacionById($id, ClasificacionesRepository $clasificacionesRepository): Response
    {
        $clasificacion = $clasificacionesRepository->find($id);
        if(!$clasificacion)
            return $this->json("Clasificación no encontrada");

        $clasificacionJSON = [];

        $clasificacionJSON= [
            'idClasificacion'=>$clasificacion->getIdClasificacion(),
            'fecha'=>$clasificacion->getFecha(),
            'horaInicio'=>$clasificacion->getHoraInicio(),
            'estado'=>$clasificacion->getEstado(),
        ];
        return $this->json($clasificacionJSON);
    }

    #[Route('/{id}/carrera', name: 'clasificaciones_get_carrera', methods:['GET'])]
    public function getCarreraClasificacion($id, Connection $connection): Response
    {
        $carrera = $connection->fetchAllAssociative("SELECT * FROM Carreras WHERE idCarrera = (SELECT idCarrera FROM Clasificaciones WHERE idClasificacion = $id)")[0];
        if(!$carrera)
            return $this->json("Carrera no encontrada");
        
        $carreraJSON = [];

        $carreraJSON= [
            'idCarrera'=>$carrera["idCarrera"],
            'vueltas'=>$carrera["Vueltas"],
            'fecha'=>$carrera["Fecha"],
            'horaInicio'=>$carrera["HoraInicio"],
            'estado'=>$carrera["Estado"]
        ];
        return $this->json($carreraJSON);
    }
}
