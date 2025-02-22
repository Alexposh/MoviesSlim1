<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\PersonRepository;
use Valitron\Validator;

class Persons
{
    public function __construct(private PersonRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([            
            'person_id'=>['required', 'integer', ['min', 1]],
            'person_name'=>['required']            
        ]);
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $person = $request->getAttribute('person');

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
        $body = json_encode([ "message" => "Person updated"]);
        $response->getBody()->write($body);
        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->repository->delete($id);
        $body = json_encode([ "message" => "Person deleted"]);
        $response->getBody()->write($body);
        return $response;
    }    

}