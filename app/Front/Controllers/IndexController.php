<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\Product;
use App\Front\Renders\IndexRender;

class IndexController extends FrontController {

    private $render;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new IndexRender($this->twig);
    }

    public function index()
    {
        $bestsellers = $this->product->getBestsellers();
        $categories = $this->product->getAll('categories');

        return $this->render->index($categories, $bestsellers, $this);
    }

    public function getCategory($id)
    {
        $category = $this->product->getOne('categories', $id);
        return $category['title'];
    }
}
