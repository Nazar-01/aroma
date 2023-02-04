<?php

namespace App\Front\Models;

use App\Database\Database;

class Review extends Database {

	public function getReviews($product_id)
	{
		$query = "SELECT * FROM `reviews` WHERE `product_id` = '$product_id' AND `approved` = 1;";

        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function create($user_id, $args)
	{
		extract($args);
		$query = "INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `score`, `message`, `approved`, `created_at`) VALUES (NULL, '$user_id', '$product_id', '$rating', '$message', '0', CURRENT_TIMESTAMP);";

        return $this->pdo->query($query);
	}

	public function checkRepeat($product_id)
	{
		$query = "SELECT `user_id` FROM `reviews` WHERE `product_id` = '$product_id';";

        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}
}