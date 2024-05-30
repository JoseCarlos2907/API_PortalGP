<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resultados-clasificaciones', name: 'app_resultados_clasificaciones')]
class ResultadosClasificacionesController extends AbstractController
{
    #[Route('/{idClasificacion}', name: 'resultados_clasificaciones_get')]
    public function getResultadosClasifiacionesByIdClasificacion($idClasificacion, Connection $connection): Response
    {
        $resultadosClasificaciones = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Clasificacion WHERE idClasificacion = $idClasificacion");
        if(!$resultadosClasificaciones)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadosClasificacionesJSON = [];

        foreach ($resultadosClasificaciones as $resultado) {
            $resultadosClasificacionesJSON[] = [
                'idPiloto'=>$resultado["idPiloto"],
                'idClasificacion'=>$resultado["idClasificacion"],
                'tiempoVueltaMasRapida'=>$resultado["TiempoVueltaMasRapida"],
                'posicionFinal'=>intval($resultado["PosicionFinal"])
            ];
        }

        return $this->json($resultadosClasificacionesJSON);
    }

    #[Route('/{idClasificacion}/piloto/{idPiloto}', name: 'resultados_piloto_clasificacion_get')]
    public function getResultadoClasifiacionByIdPiloto($idClasificacion, $idPiloto, Connection $connection): Response
    {
        $resultadoClasificacion = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Clasificacion WHERE idClasificacion = $idClasificacion AND idPiloto = $idPiloto")[0];
        if(!$resultadoClasificacion)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadoClasificacionJSON = [];

        $resultadoClasificacionJSON = [
            'idPiloto'=>$resultadoClasificacion["idPiloto"],
            'idClasificacion'=>$resultadoClasificacion["idClasificacion"],
            'tiempoVueltaMasRapida'=>$resultadoClasificacion["TiempoVueltaMasRapida"],
            'posicionFinal'=>intval($resultadoClasificacion["PosicionFinal"])
        ];

        return $this->json($resultadoClasificacionJSON);
    }
}
