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
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Obtiene la parte de la URL sin el query string
        $action = trim(str_replace('/API', '', $url), '/'); // Remueve '/API' del inicio de la ruta
        $param = null;
    
        // Intenta encontrar una ruta con un parámetro al final
        preg_match('/[^\/]+$/', $action, $match);
        if (!empty($match)) {
            $param = $match[0];
            $actionWithParam = preg_replace('/'.$match[0].'$/','{id}',$action);
            $callback = self::$routes[$method][$actionWithParam] ?? null;
            if (!isset($callback)) {
                $actionWithParam = preg_replace('/'.$match[0].'$/','{token}',$action);
                $callback = self::$routes[$method][$actionWithParam] ?? null;
            }
        }
    
        // Si no se encontró una ruta con un parámetro, intenta encontrar una ruta sin parámetro
        if (!isset($callback)) {
            $callback = self::$routes[$method][$action] ?? null;
        }
    
        if ($callback) {
            echo call_user_func($callback, $param);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
