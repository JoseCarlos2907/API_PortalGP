<?php

namespace App\Controller;

use App\Repository\CochesRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coches', name: 'coches')]
class CochesController extends AbstractController
{
    #[Route('/', name: 'coches_get', methods:['GET'])]
    public function getAllCoches(CochesRepository $cochesRepository): Response
    {
        $coches = $cochesRepository->findAll();
        $cochesJSON = [];

        foreach ($coches as $coche) {
            $cochesJSON[$coche->getIdCoche()] = [
                'idCoche'=>$coche->getIdCoche(),
                'modelo'=>$coche->getModelo(),
                'imgPrincipal'=>$coche->getImgPrincipal(),
                'segundaImagen'=>$coche->getSegundaImagen(),
                'terceraImagen'=>$coche->getTerceraImagen(),
                'cuartaImagen'=>$coche->getCuartaImagen(),
                'idEscuderia'=>$coche->getIdEscuderia()
            ];
        }
        return $this->json($cochesJSON);
    }

    #[Route('/{id}', name: 'coches_get_id', methods:['GET'])]
    public function getCocheById($id, CochesRepository $cochesRepository): Response
    {
        $coche = $cochesRepository->find($id);
        if(!$coche)
            return $this->json("Coche no encontrado");

        $cocheJSON = [];

        $cocheJSON= [
            'idCoche'=>$coche->getIdCoche(),
            'modelo'=>$coche->getModelo(),
            'imgPrincipal'=>$coche->getImgPrincipal(),
            'segundaImagen'=>$coche->getSegundaImagen(),
            'terceraImagen'=>$coche->getTerceraImagen(),
            'cuartaImagen'=>$coche->getCuartaImagen(),
            'idEscuderia'=>$coche->getIdEscuderia()
        ];
        return $this->json($cocheJSON);
    }

    #[Route('/{id}/escuderia', name: 'coches_get_escuderia', methods:['GET'])]
    public function getEscuderiaCoche($id, Connection $connection): Response
    {
        $escuderia = $connection->fetchAllAssociative("SELECT * FROM Escuderias WHERE idEscuderia = (SELECT idEscuderia FROM Coches WHERE idCoche = $id)")[0];
        if(!$escuderia)
            return $this->json("Escuderia no encontrada");
        
        $escuderiaJSON = [];

        $escuderiaJSON= [
            'idEscuderia'=>$escuderia["idEscuderia"],
            'imgLogo'=>$escuderia["imgLogo"],
            'imgEscuderia'=>$escuderia["imgEscuderia"],
            'nombre'=>$escuderia["Nombre"],
            'descripcion'=>$escuderia["Descripcion"],
            'puntuacion'=>$escuderia["Puntuacion"],
        ];
        return $this->json($escuderiaJSON);
    }
}
