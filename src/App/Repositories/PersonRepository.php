<?php 
declare (strict_types = 1);

namespace App\Repositories;
use App\Database;
use PDO;

class PersonRepository{

    public function __construct(private Database $database){

    }   
    
    public function getAll():array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query('SELECT * FROM movies.person limit 5');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id):array |bool
    {
        $sql = 'SELECT * FROM movies.person WHERE person_id = :id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
       }

    public function create(array $data): string 
    {
        $sql = 'INSERT INTO person (person_id, person_name) VALUES (:person_id, :person_name)';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':person_id', $data['person_id'], PDO::PARAM_INT);
        $stmt->bindValue(':person_name', $data['person_name'], PDO::PARAM_STR);
      
        if(empty($data['person_id'])){
            $stmt->bindValue(':person_id', "999", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $pdo->lastInsertId();

    }
    public function update(string $id, array $data): int {
        $sql = 'UPDATE person SET person_name= :person_name WHERE person_id = :person_id';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':person_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':person_name', $data['person_name'], PDO::PARAM_STR);

        if(empty($data['person_name'])){
            $stmt->bindValue(':person_name', "person_name Anonimus", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $stmt->rowCount();

    }
    public function delete(string $id): int {
        $sql = 'DELETE FROM person WHERE person_id = :person_id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':person_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getJob(string $job):array |bool
    {
        if ($job == 'actor') {
            $sql = 'SELECT movie_cast.person_id, movie_cast.character_name, person_name, person.image FROM movies.movie_cast join person on movie_cast.person_id = person.person_id where movie_id = 22 limit 10;';
        }

        if ($job == 'director') {
            $sql = 'SELECT movie_crew.person_id, person_name FROM movie_crew join person on movie_crew.person_id = person.person_id where movie_crew.job = "Director" limit 10;';
        }
                
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
       
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
       }
}