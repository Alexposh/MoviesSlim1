<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\MovieRepository;

class MovieIndexPage
{
    public function __construct(private MovieRepository $repository){

    }
    public function __invoke(Request $request, Response $response, string $page): Response
    {
        $data = $this->repository->getByPage($page);
    
        $body =json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }
}