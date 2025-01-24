<?php 
declare (strict_types = 1);

namespace App\Repositories;
use App\Database;
use PDO;

class GenreRepository{

    public function __construct(private Database $database){

    }   
    
    public function getAll():array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->query('SELECT * FROM movies.genre limit 5');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id):array |bool
    {
        $sql = 'SELECT * FROM movies.genre WHERE genre_id = :id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
       }

    public function create(array $data): string 
    {
        $sql = 'INSERT INTO genre (genre_id, genre_name) VALUES (:genre_id, :genre_name)';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':genre_id', $data['genre_id'], PDO::PARAM_INT);
        $stmt->bindValue(':genre_name', $data['genre_name'], PDO::PARAM_STR);

        if(empty($data['genre_name'])){
            $stmt->bindValue(':genre_name', "Miscellanious", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $pdo->lastInsertId();

    }
    public function update(string $id, array $data): int {
        $sql = 'UPDATE genre SET genre_name= :genre_name WHERE genre_id = :genre_id';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':genre_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':genre_name', $data['genre_name'], PDO::PARAM_STR);

        if(empty($data['genre_name'])){
            $stmt->bindValue(':genre_name', "Miscellanious", PDO::PARAM_NULL);
        }

        $stmt->execute($data);
        return $stmt->rowCount();

    }
    public function delete(string $id): int {
        $sql = 'DELETE FROM genre WHERE genre_id = :genre_id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':genre_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}