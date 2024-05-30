<?php

namespace App\Controller;

use App\Repository\EscuderiasRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/escuderias', name: 'escuderias')]
class EscuderiasController extends AbstractController
{
    #[Route('/', name: 'escuderias_get', methods:['GET'])]
    public function getAllEscuderias(EscuderiasRepository $escuderiasRepository): Response
    {
        $escuderias = $escuderiasRepository->findAll();
        $escuderiasJSON = [];

        foreach ($escuderias as $escuderia) {
            $escuderiasJSON[$escuderia->getIdEscuderia()] = [
                'idEscuderia'=>$escuderia->getIdEscuderia(),
                'imgLogo'=>$escuderia->getImgLogo(),
                'imgEscuderia'=>$escuderia->getImgEscuderia(),
                'nombre'=>$escuderia->getNombre(),
                'descripcion'=>$escuderia->getDescripcion(),
                'puntuacion'=>$escuderia->getPuntuacion(),
                'idPais'=>$escuderia->getIdPais()
            ];
        }
        return $this->json($escuderiasJSON);
    }

    #[Route('/{id}', name: 'escuderias_get_id', methods:['GET'])]
    public function getEscuderiaById($id, EscuderiasRepository $escuderiasRepository): Response
    {
        $escuderia = $escuderiasRepository->find($id);
        if(!$escuderia)
            return $this->json("Escuderia no encontrada");
        
        $escuderiaJSON = [];
            
        $escuderiaJSON = [
            'idEscuderia'=>$escuderia->getIdEscuderia(),
            'imgLogo'=>$escuderia->getImgLogo(),
            'imgEscuderia'=>$escuderia->getImgEscuderia(),
            'nombre'=>$escuderia->getNombre(),
            'descripcion'=>$escuderia->getDescripcion(),
            'puntuacion'=>$escuderia->getPuntuacion(),
            'idPais'=>$escuderia->getIdPais()
        ];
        return $this->json($escuderiaJSON);
    }

    #[Route('/{id}/pais', name: 'escuderias_get_pais', methods:['GET'])]
    public function getPaisEscuderia($id, Connection $connection): Response
    {
        $pais = $connection->fetchAllAssociative("SELECT * FROM Paises WHERE IdPais = (SELECT IdPais FROM Escuderias WHERE IdEscuderia = $id)")[0];
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


    #[Route('/{id}/coche', name: 'escuderias_get_coche', methods:['GET'])]
    public function getCocheEscuderia($id, Connection $connection): Response
    {
        $coche = $connection->fetchAllAssociative("SELECT * FROM Coches WHERE IdEscuderia = $id")[0];
        if(!$coche)
            return $this->json("Coche no encontrado");
        
        $cocheJSON = [];

        $cocheJSON= [
            'idCoche'=>$coche["idCoche"],
            'modelo'=>$coche["Modelo"],
            'imgPrincipal'=>$coche["ImgPrincipal"],
            'segundaImagen'=>$coche["SegundaImg"],
            'terceraImagen'=>$coche["TerceraImg"],
            'cuartaImagen'=>$coche["CuartaImg"],
        ];
        return $this->json($cocheJSON);
    }
}
