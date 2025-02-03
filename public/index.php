<?php
declare(strict_types=1);
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response; 
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;
use App\Controllers\GenreIndex;
use App\Controllers\MovieIndex;
use App\Controllers\PersonIndex;
use App\Controllers\MovieIndexFiltered;
use App\Controllers\PersonIndexFiltered;
use App\Controllers\Movies;
use App\Controllers\Actors;
use App\Controllers\Genres;
use App\Controllers\GenreIndexHome;
use App\Controllers\Persons;
use App\Middleware\GetGenre; 
use App\Middleware\GetMovie;
use App\Middleware\GetPerson;
use Slim\Routing\RouteCollectorProxy;

// php -S localhost:8080 -t public

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);
$app->addBodyParsingMiddleware();
$error_middleware = $app-> addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);

$app->get('/hello', function (Request $request, Response $response) {
    $response->getBody()->write("Hello");
   return $response;
});

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/categories', GenreIndex::class);
    $group->get('/categories/home', GenreIndexHome::class);
    $group->post('/categories', Genres::class . ':create');

    $group->group('', function (RouteCollectorProxy $group) {
        $group->get('/categories/{id:[0-9]+}', Genres::class . ':show');
        $group->patch('/categories/{id:[0-9]+}', Genres::class . ':update');
        $group->delete('/categories/{id:[0-9]+}', Genres::class . ':delete');
    })->add(GetGenre::class);
    
});

$app->get('/api/moviesearch/{name:[a-z]+}', Movies::class . ':getMoviesByName');
$app->get('/api/moviename/{name:[a-z]+}', Movies::class . ':getSingleMovieByName');
$app->get('/api/moviecast/{movie:[0-9]+}', Actors::class . ':getMovieCast');
// function (Request $request, Response $response) {
//     $response->getBody()->write("Hello");
//     return $response;
// });

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/movie', MovieIndex::class);    
    $group->get('/movie/{segment:[a-z]+}', MovieIndexFiltered::class);
    $group->get('/movie/p/{page:[0-9]+}', Movies::class . ':getPage');
    $group->post('/movie', Movies::class . ':create');

    $group->group('', function (RouteCollectorProxy $group) {
        
        $group->get('/movie/{id:[0-9]+}', Movies::class . ':show');
        $group->patch('/movie/{id:[0-9]+}', Movies::class . ':update');
        $group->delete('/movie/{id:[0-9]+}', Movies::class . ':delete');
    })->add(GetMovie::class);
    
});

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/person', PersonIndex::class);
    $group->get('/person/{job:[a-z]+}', PersonIndexFiltered::class);
    $group->post('/person', Persons::class . ':create');

    $group->group('', function (RouteCollectorProxy $group) {
        $group->get('/person/{id:[0-9]+}', Persons::class . ':show');
        $group->patch('/person/{id:[0-9]+}', Persons::class . ':update');
        $group->delete('/person/{id:[0-9]+}', Persons::class . ':delete');
    })->add(GetPerson::class);
    
});

// $app->group('/api', function (RouteCollectorProxy $group) {
//     $group->get('/moviecast/{movie:[0-9]+}/{page:[0-9]+}', Actors::class . ':getPage');
   
//     // $group->group('', function (RouteCollectorProxy $group) {        
//     //     $group->get('/actors/{id:[0-9]+}', Actors::class . ':show');
//     // })->add(GetActor::class);

//     // api/actors/${actorId}
    
// });

$app->run(); 