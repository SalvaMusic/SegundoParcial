<?php
require_once './models/Armas.php';
require_once './interfaces/IApiUsable.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ArmasController extends Armas implements IApiUsable
{
    public function CargarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();

        $precio = $parametros['precio'];
        $nombre = $parametros['nombre'];
        $nacionalidad = $parametros['nacionalidad'];

        $arm = new Armas();
        $arm->nombre = $nombre;
        $arm->precio = intval($precio);
        $arm->nacionalidad = $nacionalidad;
        $arm->guardar();

        $payload = json_encode(array("mensaje" => $nombre . " agregada con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $arma = Armas::obtenerArma($id);
        $data = $arma ? $arma : "Arma Inexistente";
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos(Request $request, Response $response, $args)
    {
        $lista = Armas::obtenerTodos();
        $payload = json_encode(array("lista Armas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FiltrarNacionalidad(Request $request, Response $response, $args)
    {
        var_dump($args);
        $nacionalidad = $args['nacionalidad'];
        $lista = Armas::obtenerPorNacionalidad($nacionalidad);
        $payload = json_encode(array("lista Armas Por Nacionalidad" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $precio = $parametros['precio'];
        $nombre = $parametros['nombre'];
        $nacionalidad = $parametros['nacionalidad'];

        $arm = Armas::obtenerArma($id);
        if ($arm != null){            
            $arm->nombre = $nombre;
            $arm->precio = intval($precio);
            $arm->nacionalidad = $nacionalidad;
            $arm->guardar();
            $data = "Arma modificada con exito";
        } else {
            $data = "Arma Inexistente";
        }

        $payload = json_encode(array("mensaje" => $data));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno(Request $request, Response $response, $args)
    {
        $mensaje = null;
        $armaId = $args['id'];
        $usuarioId = $request->getParsedBody()['usuarioId'];

        $arma = Armas::obtenerArma($armaId);
        if ($arma != null){
            $arma->borrarArma();
            $data = "Arma borrada con exito";
            $fecha = date('Y-m-d');
    
           /* $response = $response->withAttribute('X-fecha', $fecha);
            $response = $response->withAttribute('X-accion', 'Emilinar');
            $response = $response->withAttribute('X-armaId', $arma);
            $response = $response->withAttribute('X-usuarioId', $usuarioId);*/
        } else {
            $data = "Arma Inexistente";
        }
        $payload = json_encode($data);


        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
}
