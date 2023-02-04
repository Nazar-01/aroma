<?php

namespace App\Database;

abstract class Database {

    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($table) {
        $query = "SELECT * FROM $table;";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOne($table, $id) {
        $query = "SELECT * FROM $table WHERE id = $id;";
        return $this->pdo->query($query)->fetch(\PDO::FETCH_ASSOC);
    }

    // abstract public function create($args);

    // abstract public function update($id, $args);

    public function delete($id, $table) {
        $query =  "DELETE FROM $table WHERE $table.id = $id";
        return $this->pdo->query($query);
    }

}