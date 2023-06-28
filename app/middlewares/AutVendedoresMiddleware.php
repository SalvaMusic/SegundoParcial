<?php
require_once 'Logger.php';

use Illuminate\Support\Arr;
use LDAP\Result;
use Psr7Middlewares\Middleware\Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;

    class AutVendedoresMiddleware{
        
        public function __invoke(Request $request, RequestHandler $handler) : Response
        {
            $response = new Response();

            try{
                $header = $request->getHeaderLine('authorization');
                
                if(!empty($header)){
                    $token = trim(explode("Bearer", $header)[1]);
                    $data = Logger::ObtenerData($token);
                    $tipo = $data->tipo;
                    $UsuarioId = $data->id;

                    $parametros = $request->getParsedBody();
                    $parametros["tipo"] = $tipo;
                    $parametros["UsuarioId"] = $UsuarioId;
                    $request = $request->withParsedBody($parametros);

                    if($tipo == "Vendedor"){
                        $response = $handler->handle($request);
                    } else{
                        $mensaje = "Acceso denegado";
                        $response->getBody()->write($mensaje);
                    }
                } else {
                    $mensaje = "Falta enviar Token";
                    $response->getBody()->write($mensaje);
                }  
            }
            catch (Exception $e){
                $mensaje = json_encode(array("error" => $e->getMessage())); 
                $response->getBody()->write($mensaje);
            }
            
            return $response;
        }
    }
?>