<?php
namespace Controllers;
use Lib\Security;
use Lib\Pages;
use Controllers\UsuarioController;
use Lib\BaseDatos;

class AuthController{

    private Security $security;
    private UsuarioController $usuarioController;
    private BaseDatos $db;

    public function __construct(){
        $this->security = new Security();
        $this->usuarioController = new UsuarioController();
        $this->db = new BaseDatos();
    }


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

    public function expirarToken($token, $db){
        $this->security->expirarToken($token, $db);
    }
    
}