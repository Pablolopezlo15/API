<?php
namespace Routes;

use Controllers\DashboardController;
use Controllers\ErrorController;
use Controllers\PonenteController;
use Controllers\AuthController;
use Controllers\UsuarioController;
use Lib\Router;

class Routes {
    public static function index() {

        Router::add('GET', '/', function() {
            return (new DashboardController())->index();
        });

        Router::add('GET', '/ponente', function() {
            return (new PonenteController())->read();
        });

        Router::add('POST', '/ponente', function() {
            return (new PonenteController())->crearPonente($_POST);
        });

        Router::add('GET', '/ponente/{id}', function($id) {
            return (new PonenteController())->buscarPonente($id);
        });

        Router::add('DELETE', '/ponente/{id}', function($id) {
            return (new PonenteController())->delete($id);
        });

        // Router::add('PUT', '/ponente/{id}', function($id) {
        //     return (new PonenteController())->update($id, $_POST);
        // });

        Router::add('GET', '/usuario/registro', function() {
            return (new UsuarioController())->registro();
        });

        Router::add('POST', '/usuario/registro', function() {
            return (new UsuarioController())->registro();
        });

        Router::add('GET', '/usuario/login', function() {
            return (new UsuarioController())->login();
        });

        Router::add('POST', '/usuario/login', function() {
            return (new UsuarioController())->login();
        });

        Router::add('GET', '/usuario/logout', function() {
            return (new UsuarioController())->logout();
        });

        Router::add('GET', '/usuario/ConfirmarCuenta/{token}', function($token) {
            return (new UsuarioController())->confirmarCuenta($token);
        });
        
        
        
        

        Router::add('GET', '/peticiones', function() {
            return (new DashboardController())->peticiones();
        });

        Router::add('GET', '/prueba', function() {
            return (new AuthController())->pruebas();
        });

        Router::add('GET', '/error', function() {
            return (new ErrorController())->error404();
        });

        Router::dispatch();
    }
}