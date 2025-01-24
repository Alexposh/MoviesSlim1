<?php
declare(strict_types=1);

namespace App;

use PDO;

 class Database
 {
        public function __construct(private string $host, // or your server's IP address
                                    private string $user,
                                    private string $pass,
                                    private string $db,
                                    private string $charset)
    {
        
    }
     public function getConnection() :PDO
     {
        $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $pdo = new PDO($dsn, $this->user, $this->pass, $options);

        return $pdo;
     }
 }

 ?>