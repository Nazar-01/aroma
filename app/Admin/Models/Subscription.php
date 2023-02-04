<?php

namespace App\Admin\Models;

use App\Database\Database;

class Subscription extends Database {

    public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `subscriptions` (`id`, `email`) VALUES (NULL, '$email')";
        
        return $this->pdo->query($query);
    }

    public function update($id, $args)
    {
       extract($args);
       $query = "UPDATE `subscriptions` SET `email` = '$email' WHERE `subscriptions`.`id` = $id";
       return $this->pdo->query($query);
   }

}