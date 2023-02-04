<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\Product;
use App\Front\Renders\ProductsRender;
use App\Front\Controllers\ReviewsController;

class ProductsController extends FrontController {

    private $render;
    protected $review;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new ProductsRender($this->twig);
        $this->review = new ReviewsController($pdo);
    }

    public function getAllProducts()
    {
        $products = $this->product->getAll('products');
        return $this->render->index($products, $this);
    }

    public function getOneProduct($args)
    {
        $product = $this->product->getOne('products', $args['id']);
       
        return $this->render->getOneProduct($product, $this, $this->review);
    }

    public function getProductsFromCategory($args)
    {
        $products = $this->product->getProductsFromCategory($args['id']);
        return $this->render->index($products, $this);
    }

    public function getCategory($id)
    {
        $category = $this->product->getOne('categories', $id);
        return $category['title'];
    }
}
