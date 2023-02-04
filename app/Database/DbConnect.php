<?php

namespace App\Database;

class DbConnect {
    public function connect($host, $dbname, $user, $pass)
    {
        return new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
}