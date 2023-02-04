<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class ProductsRender extends AdminRender {

    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Products';
    }

    public function index($products, $obj_product)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'products' => $products,
            'obj_product' => $obj_product
        ]);
    }

    public function create($categories, $errors)
    {
        echo $this->twig->render("$this->dir/create.twig", [
            'path' => $this->path,
            'categories' => $categories,
            'errors' => $errors
        ]);
    }

    public function edit($product, $categories, $obj_product, $errors) {
        echo $this->twig->render("$this->dir/edit.twig", [
            'path' => $this->path,
            'product' => $product,
            'categories' => $categories,
            'obj_product' => $obj_product,
            'errors' => $errors
        ]);
    }

}
