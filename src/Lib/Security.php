<?php

namespace Lib;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
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
        $exp = strtotime('+30 minutes');
        $token = array(
            "iat" => $time,
            "exp" => $exp,
            "data" => $data
        );
    
        return JWT::encode($token, $_ENV['SECRET_KEY'], 'HS256');
    }

    final public static function crearTokenExpirado(array $data):string{
        $time = strtotime('now');
        $exp = strtotime('now');
        $token = array(
            "iat" => $time,
            "exp" => $exp,
            "data" => $data
        );
    
        return JWT::encode($token, $_ENV['SECRET_KEY'], 'HS256');
    }


    final public static function expirarToken($token, $db) {
        try {
            // Decodificar el token para obtener la fecha de expiración
            $decodedToken = JWT::decode($token,new Key(Security::clavesecreta(), 'HS256'));
            $tokenExp = $decodedToken->exp;
    
            $expiredToken = $decodedToken->data;
            // Obtener la fecha y hora actuales
            $now = time();
    
            // Si el token aún no ha expirado, expirarlo
            if ($now < $tokenExp) {
                // Establecer la fecha de expiración del token a la fecha y hora actuales con la funcion crearTokenExpirado  
                // que coge los datos del token y los codifica con la fecha de expiracion actual
                $expiredToken = self::crearTokenExpirado((array)$expiredToken);

                // Ahora actualizamos también la fecha de expiración en la base de datos
                $now = date('Y-m-d H:i:s', $now);
                $sql = "UPDATE usuarios SET token_exp = :token_exp, token = :expiredToken WHERE token = :token";
                $stmt = $db->prepara($sql);
                $stmt->bindParam(':token_exp', $now);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expiredToken', $expiredToken);
                $stmt->execute();
    
                return $expiredToken;
            } else {
                // El token ya ha expirado, así que simplemente devolverlo tal como está
                return $token;
            }
        } catch (Exception $e) {
            // Imprimir el mensaje de error y la pila de llamadas
            echo 'Error: ' . $e->getMessage();
            echo 'Stack trace: ' . $e->getTraceAsString();
        }
    }

    // final public static function getToken() {
    //     $headers = apache_request_headers();
    //     if (!isset($headers['Authorization'])) {

    //         return $response['message'] = json_decode( ResponseHttp::statusMessage(403, 'Acceso denegado'));
    //     }
    //     try {
    //         $authorizationArray = explode(' ', $headers['Authorization']);
    //         $token = $authorizationArray[1];
    //         $decodeToken = JWT::decode($token,new Key(Security::clavesecreta(), 'HS256'));
    //         return $decodeToken;

    //     } catch (PDOException $exception) {
    //         return $response['message'] = json_decode( ResponseHttp::statusMessage(401, 'Token expirado o invalido'));
    //     }
    // }

    final public static function getToken() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            return json_encode(['message' => ResponseHttp::statusMessage(403, 'Acceso denegado')]);
        }
        try {
            $authorizationArray = explode(' ', $headers['Authorization']);
            $token = $authorizationArray[1];
            return $token;
        } catch (ExpiredException $expiredException) {
            return json_encode(['message' => ResponseHttp::statusMessage(401, 'Token expirado')]);
        } catch (PDOException $exception) {
            return json_encode(['message' => ResponseHttp::statusMessage(401, 'Token invalido')]);
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

    
}
