<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\Category;
use App\Front\Renders\CategoriesRender;

class CategoriesController extends FrontController {

    private $render;
    private $category;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new CategoriesRender($this->twig);
        $this->category = new Category($pdo);
    }

    public function index()
    {
        $categories = $this->category->getAll('categories');
        return $this->render->index($categories);
    }

}
