<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once './models/Usuario.php';

class Logger
{
    private static $claveSecreta = "claveSecretaSegundoParcial";
    private static $tipoEncriptacion = "HS256";

    public static function crearToken($id, $email, $tipo){
        $time = time();
        $payload = array(
         
            "iat" => $time,
            "exp" => $time + (60*60*4),
            "data" => [
                "id" => $id,
                "email" => $email,
                "tipó" => $tipo
            ]
        );
        $token = JWT::encode($payload, self::$claveSecreta, self::$tipoEncriptacion);
        return $token;
    }

    public static function ObtenerData($token)
    {
        return JWT::decode($token, new Key(self::$claveSecreta, self::$tipoEncriptacion))->data;
    }

    public static function validarToken($token, $tipo){
        $usuario = null;
        $valido = "No autorizado";
        try {
            $data = Logger::ObtenerData($token);
            $usuario = Usuario::obtenerUsuario($data->id);
            if($usuario != null && ($tipo == null || $usuario->tipo == $tipo)){
                $valido =  "Válido";                
            }
        } catch (Exception $e) {
            switch($e->getMessage()){
                case "Expired token":
                    $valido = "Sesion expirada"; 
                    break;
                case "Signature verification failed":
                    $valido = "Token inválido";
                    break;
            }
            die(json_encode(array("mensaje" => $valido)));
        }
        return $valido;
    }
}

?>