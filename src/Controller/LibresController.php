<?php

namespace App\Controller;

use App\Repository\LibresRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/libres', name: 'libres_get')]
class LibresController extends AbstractController
{
    #[Route('/', name: 'libres_get', methods:['GET'])]
    public function getAllLibres(LibresRepository $libresRepository): Response
    {
        $libres = $libresRepository->findAll();
        $libresJSON = [];

        foreach ($libres as $libre) {
            $libresJSON[$libre->getIdLibre()] = [
                'idLibre'=>$libre->getIdLibre(),
                'nLibre'=>$libre->getNumeroLibre(),
                'fecha'=>$libre->getFecha(),
                'hora'=>$libre->getHora(),
                'estado'=>$libre->getEstado(),
            ];
        }
        return $this->json($libresJSON);
    }


    #[Route('/{id}', name: 'libres_get_id', methods:['GET'])]
    public function getLibreById($id, LibresRepository $libresRepository): Response
    {
        $libre = $libresRepository->find($id);
        if(!$libre)
            return $this->json("Libre no encontrado");

        $libreJSON = [];

        $libreJSON= [
            'idLibre'=>$libre->getIdLibre(),
            'nLibre'=>$libre->getNumeroLibre(),
            'fecha'=>$libre->getFecha(),
            'hora'=>$libre->getHora(),
            'estado'=>$libre->getEstado(),
        ];
        return $this->json($libreJSON);
    }

    #[Route('/{id}/carrera', name: 'libres_get_carrera', methods:['GET'])]
    public function getCarreraLibre($id, Connection $connection): Response
    {
        $carrera = $connection->fetchAllAssociative("SELECT * FROM Carreras WHERE idCarrera = (SELECT idCarrera FROM Libres WHERE idLibre = $id)")[0];
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
