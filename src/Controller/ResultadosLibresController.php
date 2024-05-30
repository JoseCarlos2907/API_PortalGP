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
}
