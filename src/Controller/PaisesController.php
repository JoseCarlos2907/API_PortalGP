<?php

namespace App\Controller;

use App\Repository\PaisesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paises', name: 'paises')]
class PaisesController extends AbstractController
{
    #[Route('/', name: 'paises_get', methods:['GET'])]
    public function getAllPaises(PaisesRepository $paisesRepository): Response
    {
        $paises = $paisesRepository->findAll();
        $paisesJSON = [];

        foreach ($paises as $pais) {
            $paisesJSON[] = [
                'idPais'=>$pais->getIdPais(),
                'nombre'=>$pais->getNombre(),
                'countryCode'=>$pais->getCountryCode(),
            ];
        }
        return $this->json($paisesJSON);
    }

    #[Route('/{id}', name: 'paises_get_id', methods:['GET'])]
    public function getPaisById($id, PaisesRepository $paisesRepository): Response
    {
        $pais = $paisesRepository->find($id);
        if(!$pais)
            return $this->json("Pais no encontrado");

        $paisJSON = [];

        $paisJSON= [
            'idPais'=>$pais->getIdPais(),
            'nombre'=>$pais->getNombre(),
            'countryCode'=>$pais->getCountryCode(),
        ];
        return $this->json($paisJSON);
    }
}
