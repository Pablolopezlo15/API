<?php
namespace Lib;

class Router {
    private static $routes = [];

    public static function add(string $method, string $action, Callable $controller): void {
        $action = trim($action, '/');
        self::$routes[$method][$action] = $controller;
    }

    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = preg_replace('/API/', '', $_SERVER['REQUEST_URI']);
        $action = trim($action, '/');

        $param = null;

        // Extraer parámetro si existe en la URL
        preg_match('/[0-9]+$/', $action, $match);
        if (!empty($match)) {
            $param = $match[0];
            $action = preg_replace('/' . $match[0] . '/', ':id', $action);
        }

        $token = isset($_GET['token']) ? $_GET['token'] : null;

        $fn = self::$routes[$method][$action] ?? null;
        if ($fn) {
            // Pasar el token como parámetro al controlador
            echo call_user_func($fn, $param, $token);
        } else {
            header('Location: ./API/error/');
        }
    }
}
