<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;
use Services\UsuarioService;
use Repositories\UsuarioRepository;
use Lib\Email;
class UsuarioController {

    /**
     * @var Pages $pages
     * @var Email $email
     * @var UsuarioService $usuarioService
     */
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

    /**
     * Función que se encarga de validar el formulario de registro
     * 
     * @param array $data
     * @return array
     */
    private function validarFormulario($data) {
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
        $apellidos = filter_var($data['apellidos'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    
        // Validación de regex
        $nombreRegex = "/^[a-zA-ZáéíóúÁÉÍÓÚ ]*$/";
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/";
    
    
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

    /**
     * Función que se encarga de validar el login
     * 
     * @param array $data
     * @return array|bool
     */
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

    /**
     * Función que se encarga de registrar un usuario, enviar un correo de confirmación y redirigir a la página de login
     * 
     * @param string $email
     * @param string $token
     * @return void
     */
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

    /**
     * Función que se encarga de confirmar la cuenta de un usuario
     * 
     * @param string $token
     * @return void
     */
    public function confirmarCuenta($token){
        $this->usuarioService->confirmarCuenta($token);
        $this->pages->render('/usuario/login', ['mensaje' => 'Cuenta confirmada correctamente']);
    }

    /**
     * Función que se encarga de volver a enviar un correo de confirmación
     * 
     * @param string $email
     * @return void
     */
    public function volverAmandarConfirmacion($email){
        $this->usuarioService->volverAmandarConfirmacion($email);
    }

    /**
     * Función que se encarga de actualizar el token en la base de datos
     * 
     * @return void
     */
    public function updateToken($id, $token){
        $this->usuarioService->updateToken($id, $token);
    }

    /**
     * Función que se encarga de verificar fecha de expiración del token
     * 
     * @param string $token
     * @return string
     */
    public function verificarFechaExpiracion($token){
        $fecha = $this->usuarioService->verificarFechaExpiracion($token);
        return $fecha;
    }

    /**
     * Función que se encarga de validar los datos del token
     * 
     * @param string $token
     * @return array
     */
    public function validarDatosToken($token){
        $datos = $this->usuarioService->validarDatosToken($token);
        return $datos;
    }

    /**
     * Función que se encarga de obtener el token de un usuario
     * 
     * @param int $id
     * @return string
     */
    public function obtenerToken($id){
        return $this->usuarioService->obtenerToken($id);
    }

    /**
     * Función que se encarga de loguear a un usuario
     * 
     * @param string $token
     * @return void
     */
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

    /**
     * Función que se encarga de cerrar la sesión de un usuario
     * 
     * @return void
     */
    public function logout(){
        Utils::deleteSession('login');
        header("Location:".BASE_URL."usuario/login");
        exit();
    }

}