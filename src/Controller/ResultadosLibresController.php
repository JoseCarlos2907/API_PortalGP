<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resultados-libres', name: 'app_resultados_libres')]
class ResultadosLibresController extends AbstractController
{
    #[Route('/{idLibre}', name: 'resultados_libres_get')]
    public function getResultadosLibresByIdLibre($idLibre, Connection $connection): Response
    {
        $resultadosClasificaciones = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Libres WHERE idLibre = $idLibre");
        $tipoLibre = $connection->fetchAllAssociative("SELECT NumeroLibre FROM Libres WHERE idLibre = $idLibre");
        if(!$resultadosClasificaciones)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadosClasificacionesJSON = [];

        foreach ($resultadosClasificaciones as $resultado) {
            $resultadosClasificacionesJSON[] = [
                'idPiloto'=>$resultado["idPiloto"],
                'idLibre'=>$resultado["idLibre"],
                'tiempoVueltaMasRapida'=>$resultado["TiempoVueltaMasRapida"],
                'posicionFinal'=>intval($resultado["PosicionFinal"]),
                'numeroLibre'=>$tipoLibre[0]['NumeroLibre']
            ];
        }

        return $this->json($resultadosClasificacionesJSON);
    }

    #[Route('/{idLibre}/piloto/{idPiloto}', name: 'resultados_piloto_libre_get')]
    public function getResultadoLibreByIdPiloto($idLibre, $idPiloto, Connection $connection): Response
    {
        $resultadoClasificacion = $connection->fetchAllAssociative("SELECT * FROM Pilotos_Corren_Libres WHERE idLibre = $idLibre AND idPiloto = $idPiloto")[0];
        $tipoLibre = $connection->fetchAllAssociative("SELECT NumeroLibre FROM Libres WHERE idLibre = $idLibre");
        if(!$resultadoClasificacion)
            return $this->json("Este evento aún no se ha efectuado");
        
        $resultadoClasificacionJSON = [];

        $resultadoClasificacionJSON = [
            'idPiloto'=>$resultadoClasificacion["idPiloto"],
            'idLibre'=>$resultadoClasificacion["idLibre"],
            'tiempoVueltaMasRapida'=>$resultadoClasificacion["TiempoVueltaMasRapida"],
            'posicionFinal'=>intval($resultadoClasificacion["PosicionFinal"]),
            'numeroLibre'=>$tipoLibre[0]['NumeroLibre']
        ];

        return $this->json($resultadoClasificacionJSON);
    }


    #[Route('/{idCarrera}/top', name: 'resultados_libres_get_top_pilotos')]
    public function getTopPilotos($idCarrera, Connection $connection): Response
    {
        $libres = $connection->fetchAllAssociative("SELECT L.NumeroLibre AS numero, L.Fecha AS fecha, L.HoraInicio AS hora, CASE  WHEN L.NumeroLibre = 1 THEN 'Libre - 1' WHEN L.NumeroLibre = 2 THEN 'Libre - 2' WHEN L.NumeroLibre = 3 THEN 'Libre - 3' WHEN L.NumeroLibre = 4 THEN 'Cla. Sprint' WHEN L.NumeroLibre = 5 THEN 'Sprint' ELSE 'Desconocido' END AS tipo FROM Libres L WHERE idCarrera = $idCarrera");
        if(!$libres)
            return $this->json("Algun problema ha habido");
        

        $resultadosCarrerasJSON = [];

        foreach($libres AS $libre){
            $num = intval($libre["numero"]);
            $resultados = $connection->fetchAllAssociative("SELECT PCL.PosicionFinal AS posicion, P.Nombre AS nombrePiloto, P.Apellido AS apellidoPiloto, PCL.TiempoVueltaMasRapida AS tiempo FROM Pilotos_Corren_Libres PCL JOIN Pilotos P ON P.idPiloto = PCL.idPiloto JOIN Libres L ON L.idLibre = PCL.idLibre JOIN Carreras C ON C.idCarrera = L.idCarrera WHERE C.idCarrera = $idCarrera AND L.NumeroLibre = $num AND PCL.PosicionFinal != 0 AND PCL.TiempoVueltaMasRapida != '+0 vueltas' ORDER BY STR_TO_DATE(PCL.TiempoVueltaMasRapida, '%i:%s:%f') LIMIT 3");
            if(!$resultados){
                $resultadosCarrerasJSON[] = [
                    "tipo"=>$libre["tipo"],
                    "fecha"=>$libre["fecha"],
                    "hora"=>$libre["hora"],
                    "numeroLibre"=>$libre["numero"],
                    "tiempos"=>[],
                ];
                continue;
            }

            $tiempos = [];

            foreach ($resultados as $resultado) {
                $tiempos[] = [
                    "posicion"=>intval($resultado["posicion"]),
                    "nombrePiloto"=>$resultado["nombrePiloto"],
                    "apellidoPiloto"=>$resultado["apellidoPiloto"],
                    "tiempo"=>$resultado["tiempo"],
                ];    
            }

            $resultadosCarrerasJSON[] = [
                "tipo"=>$libre["tipo"],
                "fecha"=>$libre["fecha"],
                "hora"=>$libre["hora"],
                "numeroLibre"=>$libre["numero"],
                "tiempos"=>$tiempos,
            ];
        }

        return $this->json($resultadosCarrerasJSON);
    }
}
