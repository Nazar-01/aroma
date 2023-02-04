<?php

namespace App\Admin\Models;

use App\Database\Database;

class Review extends Database {

    public function approve($id)
    {
        $query = "UPDATE `reviews` SET `approved` = '1' WHERE `reviews`.`id` = $id;";
        return $this->pdo->query($query);
    }

    public function prohibit($id)
    {
        $query = "UPDATE `reviews` SET `approved` = '0' WHERE `reviews`.`id` = $id;";
        return $this->pdo->query($query);
    }
    
}