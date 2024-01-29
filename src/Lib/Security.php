<?php

namespace Lib;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;
class Security {

    final public static function clavesecreta(){
        return $_ENV['SECRET_KEY'];
    }

    final public static function encriptarPass($string) {
        $password = password_hash($string, PASSWORD_DEFAULT);
        return $password;
    }

    final public static function verificarPass($string, $hash) {
        if (password_verify($string, $hash)) {
            return true;
        } else {
            return false;
        }
    }

    final public static function crearToken(array $data):string{
        $time = strtotime('now');
        $token = array(
            "lat" => $time,
            "exp" => $time + (60*60),
            "data" => $data
        );

        return JWT::encode($token, $_ENV['SECRET_KEY'], 'HS256');
    }

    final public static function getToken() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            return $response['message'] = json_decode( ResponseHttp::statusMessage(403, 'Acceso denegado'));
        }
        try {
            $authorizationArray = explode(' ', $headers['Authorization']);
            $token = $authorizationArray[1];
            $decodeToken = JWT::decode($token,new Key(Security::clavesecreta(), 'HS256'));
            return $decodeToken;

        } catch (PDOException $exception) {
            return $response['message'] = json_decode( ResponseHttp::statusMessage(401, 'Token expirado o invalido'));
        }
    }

}
