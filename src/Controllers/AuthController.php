<?php
namespace Controllers;
use Lib\Security;
use Lib\Pages;
use Controllers\UsuarioController;
use Lib\BaseDatos;

/**
 * Clase AuthController
 * 
 * Esta clase se encarga de manejar la autenticación de los usuarios
 * 
 * @package Controllers
 */

class AuthController{

    /**
     * @var Security $security
     * @var UsuarioController $usuarioController
     * @var BaseDatos $db
     */
    private Security $security;
    private UsuarioController $usuarioController;
    private BaseDatos $db;

    public function __construct(){
        $this->security = new Security();
        $this->usuarioController = new UsuarioController();
        $this->db = new BaseDatos();
    }

    /**
     * Función que se encarga de verificar si el token es válido
     * 
     * @return bool
     */

    public function verificarToken(){
        $token = $this->security->getToken();

        $expirado = $this->usuarioController->verificarFechaExpiracion($token);

        if (!$expirado){
            return true;
            exit();
        }
        else{
            $this->expirarToken($token, $this->db);
            return false;
        }
    }

    /**
     * Función que se encarga de crear un nuevo token
     * 
     * @return bool
     */
    public function crearNuevoToken(){
        $data = [];
        $id = $_SESSION['login']->id;
        $nombre = $_SESSION['login']->nombre;
        $email = $_SESSION['login']->email;
        $rol = $_SESSION['login']->rol;
        $confirmado = $_SESSION['login']->confirmado;
        array_push($data, $id, $nombre, $email, $rol, $confirmado);

        $token = $this->security->crearToken($data);

        $this->usuarioController->updateToken($id, $token);

        header('Location:  '.BASE_URL.'peticiones');

    }

    /**
     * Función que se encarga de expirar un token
     * 
     * @param string $token
     * @param BaseDatos $db
     */

    public function expirarToken($token, $db){
        $this->security->expirarToken($token, $db);
    }
    
}