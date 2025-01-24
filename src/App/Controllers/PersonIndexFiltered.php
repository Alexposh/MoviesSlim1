<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\PersonRepository;

class PersonIndexFiltered
{
    public function __construct(private PersonRepository $repository){

    }
    public function __invoke(Request $request, Response $response, string $criteria): Response
    {
        $data = $this->repository->getJob($criteria);
    
        $body =json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }
}