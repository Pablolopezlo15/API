<?php
namespace Routes;

use Controllers\DashboardController;
use Controllers\ErrorController;
use Controllers\EquipoController;
use Controllers\AuthController;
use Controllers\UsuarioController;
use Lib\Router;

class Routes {
    public static function index() {

        Router::add('GET', '/', function() {
            return (new DashboardController())->index();
        });

        Router::add('GET', '/equipo', function() {
            return (new EquipoController())->read();
        });

        Router::add('POST', '/equipo', function() {
   
            return (new EquipoController())->crearEquipo($_POST);
        });

        Router::add('POST', '/equipo/{id}', function($id) {
            return (new EquipoController())->buscarEquipo($id);
        });

        Router::add('GET', '/equipo/{id}', function($id) {
            return (new EquipoController())->buscarEquipo($id);
        });

        Router::add('DELETE', '/equipo/{id}', function($id) {
            return (new EquipoController())->delete($id);
        });

        Router::add('PUT', '/equipo/{id}', function($id) {
            return (new EquipoController())->update($id, $_POST);
        });

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
        

        Router::add('GET', '/auth/nuevoToken', function() {
            return (new AuthController())->crearNuevoToken();
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