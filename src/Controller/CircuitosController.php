<?php

namespace App\Controller;

use App\Repository\CircuitosRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/circuitos', name: 'circuitos')]
class CircuitosController extends AbstractController
{
    #[Route('/', name: 'circuitos_get', methods:['GET'])]
    public function getAllCircuitos(CircuitosRepository $circuitosRepository): Response
    {
        $circuitos = $circuitosRepository->findAll();
        $circuitosJSON = [];

        foreach ($circuitos as $circuito) {
            $circuitosJSON[$circuito->getIdCircuito()] = [
                'idCircuito'=>$circuito->getIdCircuito(),
                'imgCircuito'=>$circuito->getImgCircuito(),
                'imgSiluetaCircuito'=>$circuito->getImgSiluetaCircuito(),
                'nombre'=>$circuito->getNombre(),
                'longitud'=>$circuito->getLongitud(),
                'tipo'=>$circuito->getTipo(),
                'idPais'=>$circuito->getIdPais()
            ];
        }
        return $this->json($circuitosJSON);
    }

    #[Route('/{id}', name: 'circuitos_get_id', methods:['GET'])]
    public function getCircuitoById($id, CircuitosRepository $circuitosRepository): Response
    {
        $circuito = $circuitosRepository->find($id);
        if(!$circuito)
            return $this->json("Circuito no encontrada");

        $circuitosJSON = [];

        $circuitosJSON= [
            'idCircuito'=>$circuito->getIdCircuito(),
            'imgCircuito'=>$circuito->getImgCircuito(),
            'imgSiluetaCircuito'=>$circuito->getImgSiluetaCircuito(),
            'nombre'=>$circuito->getNombre(),
            'longitud'=>$circuito->getLongitud(),
            'tipo'=>$circuito->getTipo(),
            'idPais'=>$circuito->getIdPais(),
        ];

        return $this->json($circuitosJSON);
    }

    #[Route('/{id}/pais', name: 'circuitos_get_pais', methods:['GET'])]
    public function getPaisCircuito($id, Connection $connection): Response
    {
        $pais = $connection->fetchAllAssociative("SELECT * FROM Paises WHERE IdPais = (SELECT IdPais FROM Circuitos WHERE IdCircuito = $id)")[0];
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
}
