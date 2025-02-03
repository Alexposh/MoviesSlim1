<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR\Http\Message\ResponseInterface as Response;
use App\Repositories\MovieRepository;
use Valitron\Validator;

class Movies
{
    public function __construct(private MovieRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([            
            'movie_id'=>['required', 'integer', ['min', 1]],
            'title'=>['required']
            // 'budget'=>[],
            // 'homepage'=>[],
            // 'overview'=>[],
            // 'popularity'=>[],
            // 'release_date'=>[],
            // 'vote_average'=>[],
            // 'revenue'=>[],
            // 'runtime'=>[],
            // 'movie_status'=>[],
            // 'vote_count'=>[],
            // 'tagline'=>[]
        ]);
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $movie = $request->getAttribute('movie');

        $body =json_encode($movie);       
        $response->getBody()->write($body);

        return $response;
    }

    public function getPage(Request $request, Response $response, string $page): Response
    {
        $movie = $request->getAttribute('movie');

        $page = $this->repository->getByPage($page);

        $body =json_encode($page);       
        $response->getBody()->write($body);

        return $response;
    }


    public function getMoviesByName(Request $request, Response $response, string $name): Response
    {
        // $movie = $request->getAttribute('moviesearch');

        $foundMovies = $this->repository->getNamesOfMovies($name);

        $body =json_encode($foundMovies);       
        $response->getBody()->write($body);

        return $response;
    }
    public function getSingleMovieByName(Request $request, Response $response, string $name): Response
    {
        // $movie = $request->getAttribute('moviesearch');

        $foundMovie = $this->repository->getMovieByName($name);

        $body =json_encode($foundMovie);       
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
        $body = json_encode([ "message" => "Movie created"]);
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
        $body = json_encode([ "message" => "Movie updated"]);
        $response->getBody()->write($body);
        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->repository->delete($id);
        $body = json_encode([ "message" => "Movie deleted"]);
        $response->getBody()->write($body);
        return $response;
    }    

}