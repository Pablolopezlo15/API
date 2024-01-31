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
            "iat" => $time,
            "exp" => $time + (30*60),
            "data" => $data
        );
    
        return JWT::encode($token, $_ENV['SECRET_KEY'], 'HS256');
    }

    final public static function crearTokenExpirado(array $data):string{
        $time = strtotime('now');
        $token = array(
            "lat" => $time,
            "exp" => $time - (60*60),
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

    final public static function decodeToken($token) {
        try {
            $decodeToken = JWT::decode($token, new Key(Security::clavesecreta(), 'HS256'));
            return $decodeToken;
        } catch (PDOException $exception) {
            return $response['message'] = json_decode( ResponseHttp::statusMessage(401, 'Token expirado o invalido'));
        }
    }


    final public static function verificarToken($token, $usuario) {
        try {
            // Decodificar el token JWT
            $decodeToken = JWT::decode($token, new Key(self::clavesecreta(), 'HS256'));
            
            // Verificar si el token está expirado
            $currentTimestamp = time();
            if ($currentTimestamp < $decodeToken->exp || $currentTimestamp > $decodeToken->iat) {
            

                if (($usuario->email == $decodeToken[2]) && ($usuario->confirmado == $decodeToken[3])) {
                    return ['valid' => true, 'data' => $decodeToken->data];
                }
                else {
                    return ['valid' => false, 'message' => 'Token inválido'];
                }
            }
            else {
                return ['valid' => false, 'message' => 'Token Expirado'];
            }
            // Si todo está bien, el token es válido
            // return ['valid' => true, 'data' => $decodeToken->data];º
        } catch (Exception $exception) {
            // Capturar cualquier excepción que ocurra al decodificar el token
            return ['valid' => false, 'message' => 'Token inválido'];
        }
    }
    

}
