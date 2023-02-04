<?php

namespace App\Admin\Models;

use App\Database\Database;

class Order extends Database {

    public function getProducts($id)
    {
        $query = "SELECT * FROM `products_orders` WHERE `products_orders`.`order_id` = $id";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

}