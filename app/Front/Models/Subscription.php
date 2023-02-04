<?php

namespace App\Front\Models;

use App\Database\Database;

class Subscription extends Database {

	public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `subscriptions` (`id`, `email`) VALUES (NULL, '$email');";

        return $this->pdo->query($query);
    }

    public function getSubByEmail($email)
    {
        $query = "SELECT * FROM `subscriptions` WHERE `email` = '$email';";

        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

}