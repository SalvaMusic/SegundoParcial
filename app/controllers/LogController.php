<?php
require_once './models/Log.php';
require_once './interfaces/IApiUsable.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogController extends Armas implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $usuarioId = $response->getAttribute('X-usuarioId');
        $armaId = $response->getAttribute('X-armaId');
        $accion = $response->getAttribute('X-accion');
        $fecha = $response->getAttribute('X-fecha');

        if($usuarioId != null && $armaId  != null && $accion != null && $fecha != null){

            $log = new Log();
            $log->usuarioId = intval($usuarioId);
            $log->armaId = intval($armaId);
            $log->accion = $accion;
            $fecha = DateTime::createFromFormat("d/m/Y", $fecha);
            $log->fecha = $fecha->format("Y-m-d");        
            $log->guardar();
        }
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $arma = Armas::obtenerArma($id);
        $data = $arma ? $arma : "Arma Inexistente";
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Armas::obtenerTodos();
        $payload = json_encode(array("lista Armas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FiltrarNacionalidad($request, $response, $args)
    {
        $nacionalidad = $args['nacionalidad'];
        $lista = Armas::obtenerPorNacionalidad($nacionalidad);
        $payload = json_encode(array("lista Armas Por Nacionalidad" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
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

    public function BorrarUno($request, $response, $args)
    {
        $mensaje = null;
        $armaId = $args['id'];
        $arma = Armas::obtenerArma($armaId);
        if ($arma != null){
            $arma->borrarArma();
            $data = "Arma borrada con exito";
        } else {
            $data = "Arma Inexistente";
        }
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
}
