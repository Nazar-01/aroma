<?php

namespace App\Front\Models;

use App\Database\Database;

class Order extends Database {

	public function createOrder($args, $user_id)
	{
		extract($args);
        $query = "INSERT INTO `orders` (`user_id`, `country`, `city`, `address`, `comment`) VALUES ('$user_id', '$country', '$city', '$address', '$comment');";

        $this->pdo->query($query);

        $query2 = "SELECT LAST_INSERT_ID() FROM `orders`;";

        return $this->pdo->query($query2)->fetch(\PDO::FETCH_ASSOC);

	}

	public function bindProductOrder($order_id, $product_id, $count)
	{
		$query = "INSERT INTO `products_orders` (`id`, `order_id`, `product_id`, `count`) VALUES (NULL, '$order_id', '$product_id', '$count');";

        return $this->pdo->query($query);
	}

	public function getOrders($user_id)
	{
		$query = "SELECT * FROM `orders` WHERE `user_id` = '$user_id' ORDER BY `created_at` DESC;

;";
		return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getProductsOrders($order_id)
	{
		$query = "SELECT * FROM `products_orders` WHERE `order_id` = '$order_id';";

		return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}
}