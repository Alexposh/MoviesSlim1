<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\MovieActorsRepository;
use Valitron\Validator;

class Actors
{
    public function __construct(private MovieActorsRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([            
            'person_id'=>['required', 'integer', ['min', 1]],
            'person_name'=>['required']            
        ]);
    }

    public function getPage(Request $request, Response $response, string $id, string $page): Response
    {
        $movie = $request->getAttribute('movie');
      
        $pageCollected = $this->repository->getByPage($id, $page);

        $body =json_encode($pageCollected);       
        $response->getBody()->write($body);

        return $response;
    }

    public function getMovieCast(Request $request, Response $response, string $movieId): Response
    {
        $movieCast= $this->repository->getCast($movieId);

        $body =json_encode($movieCast);       
        $response->getBody()->write($body);

        return $response;
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $person = $request->getAttribute('actors');

        $body =json_encode($person);       
        $response->getBody()->write($body);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();   
        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {
            
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);}

        $id = $this->repository->create($body);
        $body = json_encode([ "message" => "Person created"]);
        $response->getBody()->write($body);
        return $response ->withStatus(201);
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        $body = $request->getParsedBody();   
        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {
            
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);}

        $rows = $this->repository->update($id, $body);
        $body = json_encode([ "message" => "Actor updated"]);
        $response->getBody()->write($body);
        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->repository->delete($id);
        $body = json_encode([ "message" => "Actor deleted"]);
        $response->getBody()->write($body);
        return $response;
    }    

    
}