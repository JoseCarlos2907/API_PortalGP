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

    #[Route('/escuderia/{id}', name: 'escuderias_get_id', methods:['GET'])]
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

    #[Route('/datos-clasificacion-oficial', name: 'escuderias_get_clas_oficial', methods:['GET'])]
    public function getDatosEscuderiasClasificacionOficial(Connection $connection): Response
    {
        $escuderias = $connection->fetchAllAssociative("SELECT E.idEscuderia AS idEscuderia, E.imgLogo AS imgLogo, E.Nombre AS nombre, E.Puntuacion AS puntosTotales, P.CountryCode AS paisCC FROM Escuderias E JOIN Paises P ON P.idPais = E.idPais");
        if(!$escuderias)
            return $this->json("No hay registros");
        
        $escuderiasJSON = [];

        foreach ($escuderias as $escuderia) {
            $escuderiasJSON[] = [
                'idEscuderia'=>$escuderia["idEscuderia"],
                'imgLogo'=>$escuderia["imgLogo"],
                'nombre'=>$escuderia["nombre"],
                'puntosTotales'=>$escuderia["puntosTotales"],
                'paisCC'=>$escuderia["paisCC"],
            ];
        }

        return $this->json($escuderiasJSON);
    }


    #[Route('/{id}/datos-perfil', name: 'escuderias_get_datos_perfil', methods:['GET'])]
    public function getDatosPerfilEscuderia($id, Connection $connection): Response
    {
        $datosEscuderia = $connection->fetchAllAssociative("SELECT E.Nombre AS nombre,E.imgLogo,E.imgEscuderia,E.Descripcion AS descripcion,C.ImgPrincipal AS imgPrincipal,C.SegundaImg AS segundaImg,C.TerceraImg AS terceraImg,C.CuartaImg AS cuartaImg FROM Escuderias E JOIN Coches C ON C.idEscuderia = E.idEscuderia WHERE E.idEscuderia = $id")[0];
        if(!$datosEscuderia)
            return $this->json("No existe la escuderia");
        
        $datosJSON = [];

        $datosJSON = [
            'nombre'=>$datosEscuderia["nombre"],
            'imgLogo'=>$datosEscuderia["imgLogo"],
            'imgEscuderia'=>$datosEscuderia["imgEscuderia"],
            'descripcion'=>$datosEscuderia["descripcion"],
            'imgPrincipal'=>$datosEscuderia["imgPrincipal"],
            'segundaImg'=>$datosEscuderia["segundaImg"],
            'terceraImg'=>$datosEscuderia["terceraImg"],
            'cuartaImg'=>$datosEscuderia["cuartaImg"],
        ];

        return $this->json($datosJSON);
    }
}
