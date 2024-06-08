<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/usuarios', name: 'usuarios')]
class UsuariosController extends AbstractController
{
    #[Route('/', name: 'usuarios_get', methods:['GET'])]
    public function getAllUsuarios(UsuariosRepository $usuariosRepository): Response
    {
        $usuarios = $usuariosRepository->findAll();
        $usuariosJSON = [];
        

        foreach ($usuarios as $usuario) {
            $usuariosJSON[$usuario->getIdUsuario()] = [
                'idUsuario'=>$usuario->getIdUsuario(),
                'imgPerfil'=>$usuario->getImgPerfil(),
                'nombre'=>$usuario->getNombre(),
                'apellidos'=>$usuario->getApellidos(),
                'fechaNac'=>$usuario->getFechaNac(),
                'nombreUsuario'=>$usuario->getNombreUsuario(),
                'correo'=>$usuario->getCorreo(),
                'rol'=>$usuario->getRol(),
                'temaSeleccionado'=>$usuario->getTemaSeleccionado()
            ];
        }
        return $this->json($usuariosJSON);
    }


    #[Route('/{id}', name: 'usuarios_get_id', methods:['GET'])]
    public function getUsuarioById($id, UsuariosRepository $usuariosRepository): Response
    {
        $usuario = $usuariosRepository->find($id);
        if(!$usuario)
            return $this->json("Usuario no encontrado");
        
        $usuarioJSON = [];
        
        

        $usuarioJSON= [
            'idUsuario'=>$usuario->getIdUsuario(),
            'imgPerfil'=>$usuario->getImgPerfil(),
            'nombre'=>$usuario->getNombre(),
            'apellidos'=>$usuario->getApellidos(),
            'fechaNac'=>$usuario->getFechaNac(),
            'nombreUsuario'=>$usuario->getNombreUsuario(),
            'correo'=>$usuario->getCorreo(),
            'rol'=>$usuario->getRol(),
            'temaSeleccionado'=>$usuario->getTemaSeleccionado()
        ];
        return $this->json($usuarioJSON);
    }


    #[Route('/{id}/pais', name: 'usuarios_get_pais', methods:['GET'])]
    public function getPaisUsuario($id, Connection $connection): Response
    {
        $pais = $connection->fetchAllAssociative("SELECT * FROM Paises WHERE IdPais = (SELECT IdPais FROM Usuarios WHERE IdUsuario = $id)")[0];
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

    #[Route('/{id}/seguidores', name: 'usuarios_get_seguidores', methods:['GET'])]
    public function getSeguidoresUsuario($id, Connection $connection): Response
    {
        $seguidores = $connection->fetchAllAssociative("SELECT * FROM Usuarios_Siguen_Usuarios USU JOIN Usuarios U ON USU.idUsuario1 = U.idUsuario WHERE USU.idUsuario2 = $id");
        if(!$seguidores)
            return $this->json("Este usuario no tiene seguidores");
        
        $nSeguidores = $connection->fetchAllAssociative("SELECT COUNT(Comentario) AS numComentarios FROM Comentarios_Usuarios_Pilotos_Carreras WHERE idUsuario = $id");
        if(!$nSeguidores) $nSeguidores = 0;
        
        $seguidoresJSON = [];
        
        foreach ($seguidores as $key => $usuario) {
            $seguidoresJSON[] = [
                'idUsuario'=>$usuario["idUsuario"],
                'nombreUsuario'=>$usuario["NombreUsuario"],
                'numComentarios' => count($nSeguidores),
                'imgPerfil'=>$usuario["ImgPerfil"]
            ];
        }
        return $this->json($seguidoresJSON);
    }

    #[Route('/{id}/seguidos', name: 'usuarios_get_seguidos', methods:['GET'])]
    public function getSeguidosUsuario($id, Connection $connection): Response
    {
        $seguidos = $connection->fetchAllAssociative("SELECT * FROM Usuarios_Siguen_Usuarios USU JOIN Usuarios U ON USU.idUsuario2 = U.idUsuario WHERE USU.idUsuario1 = $id");
        if(!$seguidos)
            return $this->json("Este usuario no tiene seguidos");
        
        $nSeguidos = $connection->fetchAllAssociative("SELECT COUNT(Comentario) AS numComentarios FROM Comentarios_Usuarios_Pilotos_Carreras WHERE idUsuario = $id");
        if(!$nSeguidos) $nSeguidos = 0;
        
        $seguidosJSON = [];
        
        foreach ($seguidos as $key => $seguido) {
            $seguidosJSON[] = [
                'idUsuario'=>$seguido["idUsuario"],
                'nombreUsuario'=>$seguido["NombreUsuario"],
                'numComentarios' => count($nSeguidos),
                'imgPerfil'=>$seguido["ImgPerfil"]
            ];
        }
        return $this->json($seguidosJSON);
    }

    #[Route('/{id}/pilotos-seguidos', name: 'usuarios_get_pilotos_seguidos', methods:['GET'])]
    public function getPilotosSeguidosUsuario($id, Connection $connection): Response
    {
        $pilotosSeguidos = $connection->fetchAllAssociative("SELECT * FROM Usuarios_Siguen_Pilotos USU JOIN Pilotos P ON P.idPiloto = USU.idUsuario WHERE USU.idUsuario = $id");
        if(!$pilotosSeguidos)
            return $this->json("Usuario no encontrado");
        
        $pilotosSeguidosJSON = [];
        
        foreach ($pilotosSeguidos as $key => $piloto) {
            $pilotosSeguidosJSON[] = [
                'idPiloto'=>$piloto["idPiloto"],
                'imgPerfil'=>$piloto["ImgPerfil"],
                'nombre'=>$piloto["Nombre"],
                'apellido'=>$piloto["Apellido"],
                'fechaNac'=>$piloto["FechaNac"],
                'peso'=>$piloto["Peso"],
                'altura'=>$piloto["Altura"],
                'numero'=>$piloto["Numero"],
                'puntuacion'=>$piloto["Puntuacion"]
            ];
        }
        return $this->json($pilotosSeguidosJSON);
    }


    #[Route('/{id}/comentarios', name: 'usuarios_get_comentarios', methods:['GET'])]
    public function getComentariosUsuario($id, Connection $connection): Response
    {
        $comentarios = $connection->fetchAllAssociative("SELECT COM.idUsuario AS idUsuario, COM.idPiloto AS idPiloto, COM.idCarrera AS idCarrera, COM.Comentario AS Comentario, U.NombreUsuario AS nombreUsuario, CONCAT(P.Nombre, ' ', P.Apellido) AS nombrePiloto, PA.Nombre AS nombrePais, U.ImgPerfil AS imgPerfilUsuario, P.ImgPerfil AS imgPerfilPiloto FROM Comentarios_Usuarios_Pilotos_Carreras COM JOIN Usuarios U ON COM.idUsuario = U.idUsuario JOIN Pilotos P ON COM.idPiloto = P.idPiloto JOIN Carreras C ON COM.idCarrera = C.idCarrera JOIN Circuitos Cir ON Cir.idCircuito = C.idCircuito JOIN Paises PA ON Cir.idPais = PA.idPais WHERE U.idUsuario = $id");
        if(!$comentarios)
            return $this->json("Este usuario no ha hecho ningún comentario");
        
        $comentariosJSON = [];

        foreach ($comentarios as $comentario) {
            $comentariosJSON[] = [
                'idCarrera'=>$comentario["idCarrera"],
                'nombrePais'=>$comentario["nombrePais"],
                'idUsuario'=>$comentario["idUsuario"],
                'nombreUsuario'=>$comentario["nombreUsuario"],
                'idPiloto'=>$comentario["idPiloto"],
                'nombrePiloto'=>$comentario["nombrePiloto"],
                'comentario'=>$comentario["Comentario"],
                'imgPerfilUsuario'=>$comentario["imgPerfilUsuario"],
                'imgPerfilPiloto'=>$comentario["imgPerfilPiloto"]
            ];
        }

        return $this->json($comentariosJSON);
    }

    #[Route('/gbe', name: 'usuarios_get_email', methods:['POST'])]
    public function getUsuarioByEmail(Request $request, Connection $connection, UsuariosRepository $usuariosRepository): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $correoUsuario = $data['correo'];

        $usuario = $connection->fetchAllAssociative("SELECT * FROM Usuarios WHERE Correo = '$correoUsuario'")[0];
        
        if(!$usuario)
            return $this->json("Usuario no encontrado");
        
        $usuarioJSON= [
            'idUsuario'=>$usuario["idUsuario"],
            'imgPerfil'=>$usuario["ImgPerfil"],
            'nombre'=>$usuario["Nombre"],
            'apellidos'=>$usuario["Apellidos"],
            'fechaNac'=>$usuario["FechaNac"],
            'nombreUsuario'=>$usuario["NombreUsuario"],
            'correo'=>$usuario["Correo"],
            'rol'=>$usuario["Rol"],
            'temaSeleccionado'=>$usuario["TemaSeleccionado"]
        ];

        return $this->json($usuarioJSON);
    }



    #[Route('/registrar', name: 'usuarios_post_registrar', methods:['POST'])]
    public function registrarUsuario(Request $request, EntityManagerInterface $entityManager, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $correoUsuario = $data["correo"];
        $existeCorreo = $connection->fetchAssociative("SELECT * FROM Usuarios WHERE Correo = '$correoUsuario'");

        if($existeCorreo > 0){
            return $this->json(["msg" => "Ya existe un correo con este usuario"]);
        }
        
        $passwordHasher = new NativePasswordHasher();

        $usuario = new Usuarios();
        $usuario->setNombre($data["nombre"]);
        $usuario->setImgPerfil($data["imgPerfil"]);
        $usuario->setApellidos($data["apellidos"]);
        $usuario->setFechaNac($data["fechaNac"]);
        $usuario->setNombreUsuario($data["nombreUsuario"]);
        $usuario->setCorreo($correoUsuario);

        $usuario->setRol($data["rol"]);
        $usuario->setTemaSeleccionado(intval($data["temaSeleccionado"]));
        $usuario->setIdPais(intval($data["idPais"]));
        
        $entityManager->persist($usuario);
        $entityManager->flush();

        return $this->json(["msg" => "Usuario creado correctamente"]);
    }


    #[Route('/spr', name: 'usuarios_post_seguir_pilotos_registro', methods:['POST'])]
    public function seguirPilotosRegistro(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $correoUsuario = $data["correo"];
        $idUsuario = $connection->fetchAllAssociative("SELECT idUsuario FROM Usuarios WHERE Correo = '$correoUsuario'")[0]["idUsuario"];
        $idPilotos = explode(",", $data["pilotos"]);
        
        foreach ($idPilotos as $key => $idPiloto) {
            $numAux = intval($idPiloto);

            $prep = $connection->prepare("INSERT INTO Usuarios_Siguen_Pilotos (idUsuario, idPiloto) VALUES ($idUsuario, $numAux)");
            $prep->execute();
        }
        

        return $this->json(["msg" => "Todo correcto"]);
    }

    #[Route('/hash', name: 'usuarios_post_hash', methods:['POST'])]
    public function hashAMano(Request $request): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $passwordHasher = new NativePasswordHasher();
        $cadena = $passwordHasher->hash($data["cadena"]);
        

        return $this->json(["msg" => $cadena]);
    }

    #[Route('/cambiar-datos-principales', name: 'usuarios_put_cambiar_principales', methods:['PUT'])]
    public function cambiarDatosPrincipales(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $correo = $data["correo"];
        $nombreUsuario = $data["nombreUsuario"];
        $nombre = $data["nombre"];
        $apellidos = $data["apellidos"];
        $imgPerfil = $data["imgPerfil"];
        
        $prep = $connection->prepare("UPDATE Usuarios SET NombreUsuario = '$nombreUsuario', Nombre = '$nombre', Apellidos = '$apellidos', ImgPerfil = '$imgPerfil' WHERE Correo = '$correo'");
        $prep->execute();

        return $this->json(["msg" => "Usuario cambiado con éxito"]);
    }

    #[Route('/cambiar-tema-seleccionado', name: 'usuarios_put_cambiar_tema', methods:['PUT'])]
    public function cambiarTemaSeleccionado(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $correo = $data["correo"];
        $temaSeleccionado = intval($data["temaSeleccionado"]);
        
        $prep = $connection->prepare("UPDATE Usuarios SET TemaSeleccionado = $temaSeleccionado WHERE Correo = '$correo'");
        $prep->execute();

        return $this->json(["msg" => "Tema seleccionado del Usuario cambiado con éxito"]);
    }

    #[Route('/eliminar/{id}', name: 'usuarios_delete_eliminar', methods:['DELETE'])]
    public function eliminarUsuarioById($id, Request $request, Connection $connection): Response
    {
        $prep = $connection->prepare("DELETE FROM Usuarios WHERE idUsuario = $id");
        $prep->execute();

        return $this->json(["msg" => "Usuario eliminado correctamente"]);
    }

    #[Route('/seguir', name: 'usuarios_post_seguir', methods:['POST'])]
    public function seguirUsuario(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $idSeguidor = $data["idSeguidor"];
        $idSeguido = $data["idSeguido"];

        $prep = $connection->prepare("INSERT INTO Usuarios_Siguen_Usuarios (idUsuario1, idUsuario2) VALUES ($idSeguidor, $idSeguido)");
        $prep->execute();

        return $this->json(["msg" => "Solicitud de seguimiento aceptada"]);
    }

    #[Route('/no-seguir', name: 'usuarios_post_no_seguir', methods:['POST'])]
    public function dejarDeSeguirUsuario(Request $request, Connection $connection): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $idSeguidor = $data["idSeguidor"];
        $idSeguido = $data["idSeguido"];

        $prep = $connection->prepare("DELETE FROM Usuarios_Siguen_Usuarios WHERE idUsuario1 = $idSeguidor AND idUsuario2 = $idSeguido");
        $prep->execute();

        return $this->json(["msg" => "Solicitud de cancelar seguimiento aceptada"]);
    }
}


