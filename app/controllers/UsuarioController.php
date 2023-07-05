<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once 'JWTController.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuarioController extends Usuario implements IApiUsable
{

    public static function login(Request $request, Response $response, array $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $clave = $parametros['clave'];

        $usr = Usuario::obtenerUsuarioPorEmail($email);

        if ($usr != null) {
            if (password_verify($clave, $usr->clave)) {
                $token = JWTController::crearToken($usr->id, $email, $usr->tipo);
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

    public function CargarUno(Request $request, Response $response, $args)
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

    public function TraerUno(Request $request, Response $response, $args)
    {
        $nombreArma = $args['nombreArma'];
        $lista = Usuario::obtenerUsuariosPorArma($nombreArma);

        $payload = json_encode(array("Usuarios que vendieron " . $nombreArma => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    

    public function TraerTodos(Request $request, Response $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        //Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno(Request $request, Response $response, $args)
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
