<?php

declare (strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use App\Repositories\MovieRepository;
use Slim\Exception\HttpNotFoundException;


class GetMovie
{

    public function __construct(private MovieRepository $repository)
    {
        
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $context = RouteContext::fromRequest($request);
        $route = $context->getRoute();
        $id = $route->getArgument('id');
       
        $movie = $this->repository->getById((int) $id);

        if($movie == false){
            throw new HttpNotFoundException($request, message: 'Movie not found');
        }
        $request = $request->withAttribute('movie', $movie);

        return $handler->handle($request);
    }
}