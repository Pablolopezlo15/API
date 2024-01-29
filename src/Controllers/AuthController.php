<?php
namespace Controllers;
use Lib\Security;
class AuthController{

    private Security $security;

    public function __construct(){
        $this->security = new Security();
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
}