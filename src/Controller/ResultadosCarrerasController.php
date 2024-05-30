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
}
