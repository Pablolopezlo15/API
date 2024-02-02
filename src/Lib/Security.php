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

    final public static function expirarToken($token) {
        // Decodificar el token para obtener la fecha de expiración
        $decodedToken = JWT::decode($token, $_ENV['SECRET_KEY'], ['HS256']);
        $tokenExp = $decodedToken->exp;
    
        // Obtener la fecha y hora actuales
        $now = time();
    
        // Si el token aún no ha expirado, expirarlo
        if ($now < $tokenExp) {
            // Establecer la fecha de expiración del token a la fecha y hora actuales
            $decodedToken->exp = $now;
    
            // Codificar el token y devolverlo
            $expiredToken = self::crearToken((array)$decodedToken);
            return $expiredToken;
        } else {
            // El token ya ha expirado, así que simplemente devolverlo tal como está
            return $token;
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
            $decodeToken = JWT::decode($token,new Key(Security::clavesecreta(), 'HS256'));
            var_dump($decodeToken);
            die();
            // Verificar si el token es válido
            if ($decodeToken->exp >= time()) {
                return $decodeToken;
            } else {
                return json_encode(['message' => ResponseHttp::statusMessage(401, 'Token expirado o invalido')]);
            }
        
        } catch (ExpiredException $expiredException) {
            return json_encode(['message' => ResponseHttp::statusMessage(401, 'Token expirado')]);
        } catch (PDOException $exception) {
            return json_encode(['message' => ResponseHttp::statusMessage(401, 'Token invalido')]);
        }
    }



    // final public static function decodeToken($token) {
    //     try {
    //         $decodeToken = JWT::decode($token, new Key(Security::clavesecreta(), 'HS256'));
    //         return $decodeToken;
    //     } catch (PDOException $exception) {
    //         return $response['message'] = json_decode( ResponseHttp::statusMessage(401, 'Token expirado o invalido'));
    //     }
    // }


    // final public static function verificarToken($token) {
    //     $email = $_SESSION['login']->email;
    //     $confirmado = $_SESSION['login']->confirmado;

    //     try {
    //         // Decodificar el token JWT
    //         $decodeToken = JWT::decode($token, new Key(self::clavesecreta(), 'HS256'));
            
    //         // Verificar si el token está expirado
    //         $currentTimestamp = time();
    //         if ($currentTimestamp < $decodeToken->exp || $currentTimestamp > $decodeToken->iat) {
            

    //             if (($email == $decodeToken[2]) && ($confirmado == $decodeToken[3])) {
    //                 return ['valid' => true, 'data' => $decodeToken->data];
    //             }
    //             else {
    //                 return ['valid' => false, 'message' => 'Token inválido'];
    //             }
    //         }
    //         else {
    //             return ['valid' => false, 'message' => 'Token Expirado'];
    //         }
    //         // Si todo está bien, el token es válido
    //         return ['valid' => true, 'data' => $decodeToken->data];
    //     } catch (Exception $exception) {
    //         // Capturar cualquier excepción que ocurra al decodificar el token
    //         return ['valid' => false, 'message' => 'Token inválido'];
    //     }
    // }
    

}
