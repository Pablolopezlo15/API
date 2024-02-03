<?php

namespace Controllers;

use Models\Equipo;
use Lib\ResponseHttp;
use Lib\Security;
use Services\UsuarioService;
use Repositories\UsuarioRepository;
use Controllers\AuthController;
class EquipoController {

    private UsuarioService $usuarioService;
    private AuthController $authController;

    public function __construct()
    {
        $this->authController = new AuthController();
        $this->usuarioService = new UsuarioService(new UsuarioRepository());
    }
    public function crearEquipo($data) {

        $fecha = $this->authController->verificarToken();

        if ($fecha){
            $response = ResponseHttp::statusMessage(401, 'El token ha expirado.');
            echo json_encode($response);
            exit();
        }

        // Obtener el contenido JSON del cuerpo de la solicitud
        $json = file_get_contents('php://input');

        // Decodificar el JSON a un array asociativo
        $data = json_decode($json, true);

        $nombre = $data['nombre'] ?? '';
        $ciudad = $data['ciudad'] ?? '';
        $division = $data['division'] ?? '';
        $color = $data['color'] ?? '';
        $redes = $data['redes'] ?? '';

        // Validar que los datos necesarios estén presentes
        if (empty($nombre) || empty($ciudad) || empty($division) || empty($color) || empty($redes)) {
            $response = ResponseHttp::statusMessage(400, 'Todos los campos son obligatorios.');
            echo json_encode($response);
            exit();
        }

        // Crear un objeto Equipo
        $equipo = new Equipo(null, $nombre, $ciudad, $division, $color, $redes);

        // Intentar crear el equipo en la base de datos
        if ($equipo->create()) {
            $response = ResponseHttp::statusMessage(201, 'Equipo creado correctamente.');
            echo json_encode($response);
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al crear el equipo.');
            echo json_encode($response);
            exit();
        }
    }

    public function read(){

        $fecha = $this->authController->verificarToken();

        if ($fecha){
            $response = ResponseHttp::statusMessage(401, 'El token ha expirado.');
            echo json_encode($response);
            exit();
        }

        $equipo = new Equipo("","","","","","");
        $equipos = $equipo->read();
        $equipoCount = count($equipos);
        $equiposArr = array();
        $equiposArr['equipos'] = array();


        if($equipoCount > 0){
            foreach($equipos as $fila) {
                array_push($equiposArr['equipos'], $fila);
            }
            $response = ResponseHttp::statusMessage(202, 'Equipos obtenidos correctamente.');
            // Merge the status message and the equipos into one array
            $response = array_merge($response, $equiposArr);
            echo json_encode($response);
        }
        else {
            $response = ResponseHttp::statusMessage(500, 'Error al obtener los equipos.');
            echo json_encode($response);
            exit();
        }
    }

    public function buscarEquipo($id) {
        
        $fecha = $this->authController->verificarToken();

        if ($fecha){
            $response = ResponseHttp::statusMessage(401, 'El token ha expirado.');
            echo json_encode($response);
            exit();
        }

    
        $equipo = new Equipo($id, "", "", "", "", "");
        $equipo = $equipo->getById();
        
        if ($equipo) {
            $response = ResponseHttp::statusMessage(202, 'Equipo obtenido correctamente.');
            echo json_encode($equipo);
    
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al obtener el equipo.');
            echo json_encode($response);
    
            exit();
        }
    }

    public function delete($id) {

        $fecha = $this->authController->verificarToken();

        if ($fecha){
            $response = ResponseHttp::statusMessage(401, 'El token ha expirado.');
            echo json_encode($response);
            exit();
        }
        
        $equipo = new Equipo($id, "", "", "", "", "");
        $equipo = $equipo->delete();

        if ($equipo > 0) {
            $response = ResponseHttp::statusMessage(202, 'Equipo eliminado correctamente.');
            echo json_encode($response);
            exit();
        } else {
            $response = ResponseHttp::statusMessage(500, 'Error al eliminar el equipo.');
            echo json_encode($response);
            exit();
        }
    }

}

