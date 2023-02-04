<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class ProductsRender extends FrontRender {

    public function index($products, $obj_product)
    {
        echo $this->twig->render("$this->dir/products.twig", [
            'path' => $this->path,
            'products' => $products,
            'obj_product' => $obj_product
        ]);
    }

    public function getOneProduct($product, $obj_product, $obj_review)
    {

        echo $this->twig->render("$this->dir/product.twig", [
            'path' => $this->path,
            'product' => $product,
            'obj_product' => $obj_product,
            'obj_review' => $obj_review
        ]);

    }

}
