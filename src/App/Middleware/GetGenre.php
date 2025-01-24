<?php

declare (strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use App\Repositories\GenreRepository;
use Slim\Exception\HttpNotFoundException;


class GetGenre
{

    public function __construct(private GenreRepository $repository)
    {
        
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $context = RouteContext::fromRequest($request);
        $route = $context->getRoute();
        $id = $route->getArgument('id');
       
        $genre = $this->repository->getById((int) $id);

        if($genre == false){
            throw new HttpNotFoundException($request, message: 'Genre not found');
        }
        $request = $request->withAttribute('genre', $genre);

        return $handler->handle($request);
    }
}