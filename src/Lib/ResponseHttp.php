<?php

namespace Lib;

class ResponseHttp {
    
    public static function getStatusMessage($code) {
        $status = [
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found', 
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        ];

        return $status[$code] ?? $status[500];
    }

    public static function statusMessage($status, $message) {
        // Devuelve un array en lugar de una cadena JSON
        return array("status" => $status, "message" => $message);
    }

    public static function setHeaders(): void {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json; charset=utf-8');
    }
}
