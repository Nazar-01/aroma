<?php

namespace App\Admin\Models;

use App\Database\Database;

class Category extends Database {

    public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `categories` (`id`, `photo`, `title`) VALUES (NULL, '$photo', '$title')";
        
        return $this->pdo->query($query);
    }

    public function update($id, $args)
    {
        extract($args);
        $query = "UPDATE `categories` SET `photo` = '$photo', `title` = '$title' WHERE `categories`.`id` = $id";
        return $this->pdo->query($query);
    }

}