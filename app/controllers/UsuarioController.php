<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './middlewares/Logger.php';

class UsuarioController extends Usuario implements IApiUsable
{

    public function FiltrarNacionalidad($request, $response, $args)
    {

        $payload = json_encode(array("lista Usuarios Por Nacionalidad" => array()));

       
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function login($request, $response, array $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $clave = $parametros['clave'];

        $usr = Usuario::obtenerUsuarioPorEmail($email);

        if ($usr != null) {
            if (password_verify($clave, $usr->clave)) {
                $token = Logger::crearToken($usr->id, $email);
                $retorno = json_encode(array("mensaje" => $usr->tipo . " " . $email ." Logeado correctamente"));
                $response = $response->withHeader('Authorization', $token);
            } else {
                $retorno = json_encode(array("mensaje" => "Contraseña incorrecta"));
            }
        } else {
            $retorno = json_encode(array("mensaje" => "Usuario no encontrado"));
        }
        $response->getBody()->write($retorno);
        return $response;
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->email = $email;
        $usr->guardarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario " . $nombre . " " . $apellido . " creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        //Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        //Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
}
