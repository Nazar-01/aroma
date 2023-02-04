<?php

namespace App\Admin\Models;

use App\Database\Database;

class User extends Database {

    public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `users` (`id`, `photo`, `name`, `email`, `password`, `is_admin`) VALUES (NULL, '$photo', '$name', '$email', MD5('$password'), '$is_admin');";

        return $this->pdo->query($query);
    }

    public function update($id, $args)
    {
        extract($args);
        $query = "UPDATE `users` SET `photo` = '$photo', `name` = '$name', `email` = '$email', `is_admin` = '$is_admin' WHERE `users`.`id` = $id";
        return $this->pdo->query($query);
    }

    public function setPassword($id, $password)
    {
        $query = "UPDATE `users` SET `password` = MD5('$password') WHERE `users`.`id` = $id;";
        return $this->pdo->query($query);
    }
    
}