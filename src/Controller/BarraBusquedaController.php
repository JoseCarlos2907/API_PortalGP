<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/buscar', name: 'buscar')]
class BarraBusquedaController extends AbstractController
{
    #[Route('/', name: 'buscar_post_cadena', methods:['POST'])]
    public function busqueda(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $cadena = $data["cadena"];

        $resultadosBusqueda = [];

        $pilotos = $connection->fetchAllAssociative("SELECT * FROM Pilotos WHERE Nombre LIKE '%$cadena%' OR Apellido LIKE '%$cadena%' ORDER BY Nombre ASC, Apellido ASC LIMIT 5");


        foreach ($pilotos as $key => $piloto) {
            $resultadosBusqueda[] = [
                'idPiloto'=>$piloto["idPiloto"],
                'imgPerfil'=>$piloto["ImgPerfil"],
                'nombre'=>$piloto["Nombre"],
                'apellido'=>$piloto["Apellido"],
                'fechaNac'=>$piloto["FechaNac"],
                'peso'=>$piloto["Peso"],
                'altura'=>$piloto["Altura"],
                'numero'=>$piloto["Numero"],
                'puntuacion'=>$piloto["Puntuacion"],
                'tipo'=>"piloto",
            ];
        }

        if(count($resultadosBusqueda) < 5){

            $cantidad = 5 - count($resultadosBusqueda);

            $escuderias = $connection->fetchAllAssociative("SELECT * FROM Escuderias WHERE Nombre LIKE '%$cadena%' ORDER BY Nombre ASC LIMIT $cantidad");

            foreach ($escuderias as $key => $escuderia) {
                $resultadosBusqueda[] = [
                    'idEscuderia'=>$escuderia["idEscuderia"],
                    'imgLogo'=>$escuderia["imgLogo"],
                    'imgEscuderia'=>$escuderia["imgEscuderia"],
                    'nombre'=>$escuderia["Nombre"],
                    'descripcion'=>$escuderia["Descripcion"],
                    'puntuacion'=>$escuderia["Puntuacion"],
                    'tipo'=>"escuderia",
                ];
            }
        }


        if(count($resultadosBusqueda) < 5){

            $cantidad = 5 - count($resultadosBusqueda);

            $usuarios = $connection->fetchAllAssociative("SELECT * FROM Usuarios WHERE Nombre LIKE '%$cadena%' OR Apellidos LIKE '%$cadena%' OR NombreUsuario LIKE '%$cadena%' ORDER BY Nombre ASC, Apellidos ASC, NombreUsuario ASC LIMIT $cantidad");

            foreach ($usuarios as $key => $usuario) {
                $resultadosBusqueda[] = [
                    'idUsuario'=>$usuario["idUsuario"],
                    'imgPerfil'=>$usuario["ImgPerfil"],
                    'nombre'=>$usuario["Nombre"],
                    'apellidos'=>$usuario["Apellidos"],
                    'fechaNac'=>$usuario["FechaNac"],
                    'nombreUsuario'=>$usuario["NombreUsuario"],
                    'correo'=>$usuario["Correo"],
                    'rol'=>$usuario["Rol"],
                    'temaSeleccionado'=>$usuario["TemaSeleccionado"],
                    'tipo'=>"usuario",
                ];
            }
        }
        

        return $this->json($resultadosBusqueda);
    }
}
