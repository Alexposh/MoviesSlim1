<?php

use App\Database;

return [
    Database::class =>function(){
        return new Database( host : 'localhost', // or your server's IP address
                                user : 'root',       
                                pass : '410072',
                                db : 'movies',
                                charset : 'utf8mb4');
    }
         
];