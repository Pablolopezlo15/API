<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;
use Services\UsuarioService;
use Repositories\UsuarioRepository;
use Lib\Email;
class UsuarioController {
    private Pages $pages;
    private Email $email;
    private UsuarioService $usuarioService;
    private $errores = [];

    // Constructor de la clase UsuarioController
    public function __construct()
    {
        $this->pages = new Pages();
        $this->usuarioService = new UsuarioService(new UsuarioRepository());
    }

    // Método para validar el formulario de usuario
    private function validarFormulario($data) {
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
        $apellidos = filter_var($data['apellidos'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    
        // Validación de regex
        $nombreRegex = "/^[a-zA-ZáéíóúÁÉÍÓÚ ]*$/";
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; // Al menos una letra, un número y mínimo 8 caracteres
    
    
        if (empty($nombre) || !preg_match($nombreRegex, $nombre)) {
            $this->errores[] = 'El nombre solo debe contener letras y espacios.';
        }
        if (empty($apellidos) || !preg_match($nombreRegex, $apellidos)) {
            $this->errores[] = 'Los apellidos solo deben contener letras y espacios.';
        }
        if (empty($email) || !preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }
        if (empty($password) || !preg_match($passwordRegex, $password)) {
            $this->errores[] = 'La contraseña debe tener al menos una letra, un número y un mínimo de 8 caracteres.';
        }

        if (empty($this->errores)) {
            return [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]),
            ];
        } else {
            return $this->errores;
        }
    }

    // Método para validar el inicio de sesión
    private function validarLogin($data) {

        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    
        // Validación de regex
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; // Al menos una letra, un número y mínimo 8 caracteres
    
        if (!preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }
        if (!preg_match($passwordRegex, $password)) {
            $this->errores[] = 'La contraseña debe tener al menos una letra, un número y un mínimo de 8 caracteres.';
        }
    
        if (empty($this->errores)) {
            return [
                'email' => $email,
                'password' => $password
            ];
        } else {
            return false;
        }
    }

    // Método para registrar un nuevo usuario
    public function registro(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $this->validarFormulario($_POST['data']);

                if ($registrado != ""){
                    if (is_array($registrado)) {
         
                        $usuario = Usuario::fromArray($registrado);
                        $save = $this->usuarioService->create($usuario);
                        $registrado = "";
                        if ($save){
                            $_SESSION['register'] = "complete";
                            
                        } else {
                            echo "Error al crear el usuario\n";
                            $_SESSION['register'] = "failed";
                        }
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                }
                else {
                    $_SESSION['register'] = "failed";
                }
    
            }
        }
    
        $this->pages->render('/usuario/registro', ['errores' => $this->errores]);
    }

    // Método para confirmar la cuenta de un usuario
    public function confirmarCuenta($token){
        $this->usuarioService->confirmarCuenta($token);
    }

    // Método para iniciar sesión
    public function login(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $login = $this->validarLogin($_POST['data']);
    
                if ($login !== false) {
                    $usuario = Usuario::fromArray($login);

                    $verify = $this->usuarioService->login($usuario);

                    if($verify->confirmado == 0){
                        $_SESSION['login'] = "noconfirmado";
                    }
                    elseif ($verify!=false){
                        $_SESSION['login'] = $verify;
                        header("Location:".BASE_URL."peticiones");
                    } else {
                        $_SESSION['login'] = "failed";
                    }
                } else {
                    $_SESSION['login'] = "failed";
                }
            } else {
                $_SESSION['login'] = "failed";
            }
        }
    
        $this->pages->render('/usuario/login', ['errores' => $this->errores]);
    }

    // Método para cerrar sesión
    public function logout(){
        Utils::deleteSession('login');

        header("Location:".BASE_URL);
    }

    // Método para eliminar un usuario
    public function eliminar($id){
        $this->usuarioService->delete($id);
        header("Location:".BASE_URL."usuario/verTodos");
    }

    // Método para editar un usuario
    public function editar($id){
        $usuarios = $this->usuarioService->verTodos();
        $this->pages->render('/usuario/verTodos', ['usuarios' => $usuarios, 'id' => $id]);
    }

    // Método para validar la edición de un usuario
    public function validarEditar($data){
        $id = filter_var($data['id'], FILTER_SANITIZE_STRING);
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
        $apellidos = filter_var($data['apellidos'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $rol = filter_var($data['rol'], FILTER_SANITIZE_STRING);
    
        $nombreRegex = "/^[a-zA-ZáéíóúÁÉÍÓÚ ]*$/";
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; 
    
    
        if (!preg_match($nombreRegex, $nombre)) {
            $this->errores[] = 'El nombre solo debe contener letras y espacios.';
        }
        if (!preg_match($nombreRegex, $apellidos)) {
            $this->errores[] = 'Los apellidos solo deben contener letras y espacios.';
        }
        if (!preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }

        if (empty($this->errores)) {
            return [
                'id' => $id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'rol' => $rol
            ];
        } else {
            return $this->errores;
        }
    }

    // Método para actualizar un usuario
    public function actualizar(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $this->validarEditar($_POST['data']);

                if ($registrado != "") {
                    $usuario = Usuario::fromArray($registrado);

                    $save = $this->usuarioService->update($usuario);
                    if ($save){
                        $_SESSION['register'] = "complete";
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                } else {
                    $_SESSION['register'] = "failed";
                }
                $usuario->desconecta();
            }
        }
        header("Location:".BASE_URL."usuario/verTodos");
    }
}