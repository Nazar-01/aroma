<?php

namespace App\Admin\Models;

use App\Database\Database;

class Product extends Database {

    public function create($args)
    {
        extract($args);
        $query = "INSERT INTO `products` (`id`, `title`, `content`, `description`, `price`, `category_id`, `availability`, `recommend`, `bestseller`, `photo`) VALUES (NULL, '$title', '$content', '$description', '$price', '$category_id', '$availability', '$recommend', '$bestseller', '$photo')";
        
        return $this->pdo->query($query);
    }

    public function update($id, $args) {
        extract($args);
        $query = "UPDATE `products` SET `title` = '$title', `content` = '$content', `description` = '$description', `price` = '$price', `category_id` = '$category_id', `availability` = '$availability', `recommend` = '$recommend', `bestseller` = '$bestseller', `photo` = '$photo' WHERE `products`.`id` = $id";
        return $this->pdo->query($query);
    }

}