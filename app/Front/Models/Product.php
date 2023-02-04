<?php

namespace App\Front\Models;

use App\Database\Database;

class Product extends Database {

	public function getBestsellers()
	{
		$query = "SELECT * FROM `products` WHERE `bestseller` = 1;";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getProductsFromCategory($id)
	{
		$query = "SELECT * FROM `products` WHERE `category_id` = $id;";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getRecommended()
	{
		$query = "SELECT * FROM `products` WHERE `recommend` = 1;";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}
    
}