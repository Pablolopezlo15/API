<?php
namespace Controllers;
use Lib\Security;
use Lib\Pages;
use Controllers\UsuarioController;

class AuthController{

    private Security $security;
    private UsuarioController $usuarioController;

    public function __construct(){
        $this->security = new Security();
        $this->usuarioController = new UsuarioController();
    }
    public function pruebas(){
        // $passw = $this->security->encriptarPass('1234');
        // $passw = $this->security->clavesecreta();
        // $passw = $this->security->crearToken('1234', ['id' => 1, 'name' => 'pepe']);
        $passw = $this->security->getToken();
        var_dump($passw);
    }

    public function verificarToken(){
        $token = $this->security->getToken();
        var_dump($token);
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

    public function expirarToken(){
        $this->security->expirarToken();
    }
    
}