<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resultados-carreras', name: 'app_resultados_carreras')]
class ResultadosCarrerasController extends AbstractController
{
    #[Route('/{idCarrera}', name: 'resultados_carreras_get')]
    public function getResultadosCarreraByIdCarrera($idCarrera, Connection $connection): Response
    {
        $resultadosCarreras = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Carreras WHERE idCarrera = $idCarrera");
        if(!$resultadosCarreras)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadosCarrerasJSON = [];

        foreach ($resultadosCarreras as $resultado) {
            $resultadosCarrerasJSON[] = [
                'idPiloto'=>$resultado["idPiloto"],
                'idCarrera'=>$resultado["idCarrera"],
                'tiempoTotalEnCarrera'=>$resultado["TiempoTotalEnCarrera"],
                'posicionFinal'=>intval($resultado["PosicionFinal"])
            ];
        }

        return $this->json($resultadosCarrerasJSON);
    }

    #[Route('/{idCarrera}/piloto/{idPiloto}', name: 'resultados_piloto_carrera_get')]
    public function getResultadoCarreraByIdPiloto($idCarrera, $idPiloto, Connection $connection): Response
    {
        $resultadoCarrera = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Carreras WHERE idCarrera = $idCarrera AND idPiloto = $idPiloto")[0];
        if(!$resultadoCarrera)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadoCarrerasJSON = [];

        $resultadoCarrerasJSON = [
            'idPiloto'=>$resultadoCarrera["idPiloto"],
            'idCarrera'=>$resultadoCarrera["idCarrera"],
            'tiempoTotalEnCarrera'=>$resultadoCarrera["TiempoTotalEnCarrera"],
            'posicionFinal'=>intval($resultadoCarrera["PosicionFinal"])
        ];

        return $this->json($resultadoCarrerasJSON);
    }

    #[Route('/{idCarrera}/top', name: 'resultados_carrera_get_top_pilotos')]
    public function getTopPilotos($idCarrera, Connection $connection): Response
    {
        $fechaYHora = $connection->fetchAllAssociative("SELECT C.HoraInicio AS hora, C.Fecha AS fecha FROM Carreras AS C WHERE C.idCarrera = $idCarrera")[0];
        $resultados = $connection->fetchAllAssociative("SELECT P.Nombre AS nombre, P.Apellido AS apellido, PCC.PosicionFinal AS posicionFinal, PCC.TiempoTotalEnCarrera AS tiempo FROM Pilotos_Corren_Carreras PCC JOIN Pilotos P ON P.idPiloto = PCC.idPiloto WHERE PCC.idCarrera = $idCarrera AND PCC.PosicionFinal != 0 ORDER BY CAST(PCC.PosicionFinal AS UNSIGNED) LIMIT 3");
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
