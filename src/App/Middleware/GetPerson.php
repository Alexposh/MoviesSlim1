<?php

declare (strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;
use App\Repositories\PersonRepository;
use Slim\Exception\HttpNotFoundException;


class GetPerson
{

    public function __construct(private PersonRepository $repository)
    {
        
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $context = RouteContext::fromRequest($request);
        $route = $context->getRoute();
        $id = $route->getArgument('id');
       
        $person = $this->repository->getById((int) $id);

        if($person == false){
            throw new HttpNotFoundException($request, message: 'Movie not found');
        }
        $request = $request->withAttribute('person', $person);

        return $handler->handle($request);
    }
}