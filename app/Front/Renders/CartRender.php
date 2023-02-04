<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class CartRender extends FrontRender {

    public function index($products, $obj_cart)
    {
        echo $this->twig->render("$this->dir/cart.twig", [
            'path' => $this->path,
            'products' => $products,
            'obj_cart' => $obj_cart
        ]);
    }

    public function checkout($products, $obj_cart, $message, $class)
    {
        echo $this->twig->render("$this->dir/checkout.twig", [
            'path' => $this->path,
            'products' => $products,
            'obj_cart' => $obj_cart,
            'message' => $message,
            'class' => $class
        ]);
    }
}
