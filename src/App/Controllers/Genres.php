<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\GenreRepository;
use Valitron\Validator;

class Genres
{
    public function __construct(private GenreRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'genre_name' =>['required'],
            'genre_id' =>['required', 'integer', ['min', 1]]
        ]);
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $genre = $request->getAttribute('genre');

        $body =json_encode($genre);       
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
     $body = json_encode([ "message" => "Genre created"]);
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
     $body = json_encode([ "message" => "Genre updated"]);
     $response->getBody()->write($body);
     return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
     $rows = $this->repository->delete($id);
     $body = json_encode([ "message" => "Genre deleted"]);
     $response->getBody()->write($body);
     return $response;
    }

}