<?php
// Error Handling

// php -S localhost:666 -t app
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ArmasController.php';
require_once './controllers/VentaController.php';
require_once './middlewares/AutenticacionMiddleware.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':login');
    $group->get('/arma/{nombreArma}', \UsuarioController::class . ':TraerUno')->add(new AutenticacionMiddleware("Admin"));

});

$app->group('/armas', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ArmasController::class . ':CargarUno')->add(new AutenticacionMiddleware("Vendedor"));
  $group->get('[/]', \ArmasController::class . ':TraerTodos');
  $group->get('/nacionalidad/{nacionalidad}', \ArmasController::class . ':FiltrarNacionalidad');
  $group->get('/id/{id}', \ArmasController::class . ':TraerUno')->add(new AutenticacionMiddleware(null));
});

$app->group('/venta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \VentaController::class . ':CargarUno')->add(new AutenticacionMiddleware(null));
  $group->get('/nacionalidadFecha', \VentaController::class . ':FiltrarNacionalidadFecha')->add(new AutenticacionMiddleware("Admin"));
  $group->get('/nombre/{nombre}', \ArmasController::class . ':FiltrarNombre')->add(new AutenticacionMiddleware("Admin"));
});


$app->run();
