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


    #[Route('/{idCarrera}/top', name: 'resultados_clasificacion_get_top_pilotos')]
    public function getTopPilotos($idCarrera, Connection $connection): Response
    {
        $fechaYHora = $connection->fetchAllAssociative("SELECT C.HoraInicio AS hora, C.Fecha AS fecha FROM Clasificaciones AS C JOIN Carreras Car ON Car.idCarrera = C.idCarrera WHERE C.idCarrera = $idCarrera")[0];
        $resultados = $connection->fetchAllAssociative("SELECT P.Nombre AS nombre, P.Apellido AS apellido, PCC.PosicionFinal AS posicionFinal, PCC.TiempoVueltaMasRapida AS tiempo FROM Pilotos_Corren_Clasificacion PCC JOIN Pilotos P ON P.idPiloto = PCC.idPiloto JOIN Clasificaciones C ON PCC.idClasificacion = C.idClasificacion WHERE C.idCarrera = $idCarrera AND PCC.PosicionFinal <> 0 ORDER BY CAST(PCC.PosicionFinal AS UNSIGNED) LIMIT 3");
        if(!$resultados){
            $resultadoCarrerasJSON = [
                "fecha" => $fechaYHora["fecha"], 
                "hora" => $fechaYHora["hora"], 
                "tiempos" => []
            ];
            return $this->json($resultadoCarrerasJSON);
        }
        
        $resultadoCarrerasJSON = [
            "fecha" => $fechaYHora["fecha"], 
            "hora" => $fechaYHora["hora"], 
            "tiempos" => []];

        foreach ($resultados as $resultado) {
            $resultadoCarrerasJSON["tiempos"][] = [
                'nombrePiloto'=>$resultado["nombre"],
                'apellidoPiloto'=>$resultado["apellido"],
                'tiempo'=>$resultado["tiempo"],
                'posicion'=>intval($resultado["posicionFinal"])
            ];
        }

        return $this->json($resultadoCarrerasJSON);
    }
}
