<?php 
declare (strict_types = 1);

namespace App\Repositories;
use App\Database;
use PDO;

class MovieRepository{

    public function __construct(private Database $database){

    }   
    
    public function getAll():array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query('SELECT * FROM movies.movie limit 5');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id):array |bool
    {
        $sql = 'SELECT * FROM movies.movie WHERE movie_id = :id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
       }

    public function create(array $data): string 
    {
        $sql = 'INSERT INTO movie (movie_id, title, budget, homepage, overview, popularity, release_date, revenue, runtime, movie_status, tagline, vote_average, vote_count) VALUES (:movie_id, :title, :budget, :homepage, :overview, :popularity, :release_date, :revenue, :runtime, :movie_status, :tagline, :vote_average, :vote_count)';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':movie_id', $data['movie_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':budget', $data['budget'], PDO::PARAM_INT);
        $stmt->bindValue(':homepage', $data['homepage'], PDO::PARAM_STR);
        $stmt->bindValue(':overview', $data['overview'], PDO::PARAM_INT);
        $stmt->bindValue(':popularity', $data['popularity'], PDO::PARAM_STR);
        $stmt->bindValue(':release_date', $data['release_date'], PDO::PARAM_INT);
        $stmt->bindValue(':vote_average', $data['vote_average'], PDO::PARAM_STR);
        $stmt->bindValue(':revenue', $data['revenue'], PDO::PARAM_INT);
        $stmt->bindValue(':runtime', $data['runtime'], PDO::PARAM_STR);
        $stmt->bindValue(':movie_status', $data['movie_status'], PDO::PARAM_INT);
        $stmt->bindValue(':vote_count', $data['vote_count'], PDO::PARAM_STR);
        $stmt->bindValue(':tagline', $data['tagline'], PDO::PARAM_STR);

        if(empty($data['movie_id'])){
            $stmt->bindValue(':movie_id', "999", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $pdo->lastInsertId();

    }
    public function update(string $id, array $data): int {
        $sql = 'UPDATE movie SET title= :title WHERE movie_id = :movie_id';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':movie_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);

        if(empty($data['title'])){
            $stmt->bindValue(':title', "title of movie inserted", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $stmt->rowCount();

    }
    public function delete(string $id): int {
        $sql = 'DELETE FROM movie WHERE movie_id = :movie_id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':movie_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getSegment(string $segment):array |bool
    {
        if ($segment == 'promoted') {
            $sql = 'SELECT * FROM movies.movie ORDER BY release_date  desc LIMIT 5';
        }

        if ($segment == 'featured') {
            $sql = 'SELECT * FROM movies.movie ORDER BY popularity  desc LIMIT 8';
        }

        if ($segment == 'popular') {
            $sql = 'SELECT * FROM movies.movie ORDER BY vote_average  desc LIMIT 10';
        }
        
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
       
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
       }
}