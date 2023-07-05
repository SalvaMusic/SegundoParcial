<?php
require_once './controllers/LogController.php';

use Illuminate\Support\Arr;
use LDAP\Result;
use Psr7Middlewares\Middleware\Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;

    class LogDeleteMiddleware {
        
        public function __invoke(Request $request, RequestHandler $handler) : Response
        {
            $response = new Response();

            try{
                $response = $handler->handle($request);
                (new LogController)->cargarUno($request, $response, []);
            } catch (Exception $e){
                $mensaje = json_encode(array("error" => $e->getMessage())); 
                $response->getBody()->write($mensaje);
            }
            
            return $response;
        }
    }
?>