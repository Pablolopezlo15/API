<?php

namespace Controllers;

use Models\Ponente;
use Lib\ResponseHttp;
use Lib\Security;
class PonenteController {
    public function crearPonente($data) {

            // Obtener el contenido JSON del cuerpo de la solicitud
        $json = file_get_contents('php://input');

        // Decodificar el JSON a un array asociativo
        $data = json_decode($json, true);

        $nombre = $data['nombre'] ?? '';
        $apellidos = $data['apellidos'] ?? '';
        $imagen = $data['imagen'] ?? '';
        $tags = $data['tags'] ?? '';
        $redes = $data['redes'] ?? '';

        // Validar que los datos necesarios estén presentes
        if (empty($nombre) || empty($apellidos) || empty($imagen) || empty($tags) || empty($redes)) {
            $response = ResponseHttp::statusMessage(400, 'Todos los campos son obligatorios.');
            echo json_encode($response);
            exit();
        }

        // Crear un objeto Ponente
        $ponente = new Ponente(null, $nombre, $apellidos, $imagen, $tags, $redes);

        // Intentar crear el ponente en la base de datos
        if ($ponente->create()) {
            $response = ResponseHttp::statusMessage(201, 'Ponente creado correctamente.');
            echo json_encode($response);
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al crear el ponente.');
            echo json_encode($response);
            exit();
        }
    }

    public function read(){

        $token_exp = $_SESSION['login']->token_exp;
    
        if ($token_exp < strtotime('now')) {
            $response = ResponseHttp::statusMessage(401, 'Token caducado.');
            echo json_encode($response);
            exit();
        }

        $ponente = new Ponente("","","","","","");
        $ponentes = $ponente->read();
        $ponenteCount = count($ponentes);
        $ponentesArr = array();
        $ponentesArr['ponentes'] = array();

        // $token = Security::getToken();

        if($ponenteCount > 0){
        foreach($ponentes as $fila) {
            array_push($ponentesArr['ponentes'], $fila);
        }
        $response = ResponseHttp::statusMessage(202, 'Ponentes obtenidos correctamente.');
        echo json_encode($ponentesArr);
        }
        else {
            $response = ResponseHttp::statusMessage(500, 'Error al obtener los ponentes.');
            echo json_encode($response);
        exit();
        }
    }

    public function buscarPonente($id) {
        
        $id_usuario = $_SESSION['login']->id;
        $usuario = $usuario->getById($id_usuario);
        $token = $usuario['token'];

        Security::verificarToken($token);

        $ponente = new Ponente($id, "", "", "", "", "");
        $ponente = $ponente->getById();
        
        $ponentesArr = array();
        $ponentesArr['ponentes'] = array();

        if ($ponentesArr > 0) {
            $response = ResponseHttp::statusMessage(202, 'Ponente obtenido correctamente.');
            echo json_encode($ponente);
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al obtener el ponente.');
            echo json_encode($response);
            exit();
        }
    }

    public function delete($id) {
        $ponente = new Ponente($id, "", "", "", "", "");
        $ponente = $ponente->delete();

        if ($ponente > 0) {
            $response = ResponseHttp::statusMessage(202, 'Ponente eliminado correctamente.');
            echo json_encode($ponente);
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al eliminar el ponente.');
            echo json_encode($response);
            exit();
        }
    }

}

