<?php

namespace Lib;

class Pages
{
    public function render(string $pageName, array $params = null): void
    {
        // Verifica si se proporcionan parámetros
        if ($params !== null) {
            // Extrae las variables del array asociativo
            extract($params);
        }

        // Construye la ruta a las vistas
        $viewsPath = dirname(__DIR__, 1) . "/Views/";

        // Incluye los archivos de la plantilla
        require_once $viewsPath . "layout/header.php";
        require_once $viewsPath . "$pageName.php";
    }
}
