<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\PersonRepository;

class PersonIndex
{
    public function __construct(private PersonRepository $repository){

    }
    public function __invoke(Request $request, Response $response): Response
    {
        $data = $this->repository->getAll();
    
        $body =json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }
}