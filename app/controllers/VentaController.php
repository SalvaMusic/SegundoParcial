<?php
require_once './models/Armas.php';
require_once './models/Venta.php';
require_once './interfaces/IApiUsable.php';

class VentaController extends Venta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $mensaje = null;

        $fecha = $parametros['fecha'];
        $cantidad = $parametros['cantidad'];
        $armaId = $parametros['armaId'];
        $usuarioId = $parametros['usuarioId'];

        $venta = new Venta();
        $venta->usuarioId = intval($usuarioId);
        $venta->cantidad = intval($cantidad);
        
        $fecha = DateTime::createFromFormat("d/m/Y", $fecha);

        if ($fecha === false) {
            $mensaje = "La cadena de fecha es inválida";
        } else {
            $venta->fecha = $fecha->format("Y-m-d");
        }
        
        if ($mensaje == null){
            $armaId = intval($armaId);
            $arma = Armas::obtenerArma($armaId);
            if($arma != null){
                $venta->armaId = $armaId;
                $venta->guardar();
                $mensaje = "Venta de arma ". $arma->nombre ." realizada correctamente.";
            } else {
                $mensaje = "Arma inexistente.";
            }
        }

        $payload = json_encode(array("mensaje" => $mensaje));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
       /* $id = $args['id'];
        $arma = Venta::obtener($id);
        $data = $arma ? $arma : "Arma Inexistente";
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');*/
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Venta::obtenerTodos();
        $payload = json_encode(array("lista Armas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FiltrarNacionalidadFecha($request, $response, $args)
    {
        $pais = 'EEUU';
        $fechaInicio = '2022-11-13';
        $fechaFin = '2022-11-16';
        $lista = Venta::obtenerTodosPaisFecha($pais, $fechaInicio, $fechaFin);
        $payload = json_encode(array("lista Ventas Armas EEUU y fecha" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
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
