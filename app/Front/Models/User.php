<?php

namespace App\Front\Models;

use App\Database\Database;

class User extends Database {

	public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `users` (`id`, `photo`, `name`, `email`, `password`, `is_admin`) VALUES (NULL, '$photo', '$name', '$email', MD5('$password'), '0');";

        return $this->pdo->query($query);
    }

    public function getUserByEmail($email)
    {
    	$query = "SELECT * FROM `users` WHERE `email` = '$email';";
    	return $this->pdo->query($query)->fetch(\PDO::FETCH_ASSOC);
    }

    public function tryGetUser($email, $password)
    {
        $query = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = MD5('$password');";
        return $this->pdo->query($query)->fetch(\PDO::FETCH_ASSOC);
    }

    public function deletePhoto($id)
    {
        $query = "UPDATE `users` SET `photo` = 'no-photo.png' WHERE `users`.`id` = $id;";
        return $this->pdo->query($query);
    }

    public function updatePhoto($id, $filename)
    {
        $query = "UPDATE `users` SET `photo` = '$filename' WHERE `users`.`id` = $id;";
        return $this->pdo->query($query);
    }

    public function update($id, $args)
    {
        extract($args);
        $query = "UPDATE `users` SET `name` = '$name', `email` = '$email' WHERE `users`.`id` = $id;";
        return $this->pdo->query($query);
    }

    public function setPassword($id, $password)
    {
        $query = "UPDATE `users` SET `password` = MD5('$password') WHERE `users`.`id` = $id;";
        return $this->pdo->query($query);
    }

}