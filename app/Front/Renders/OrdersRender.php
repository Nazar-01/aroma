<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class OrdersRender extends FrontRender {

    public function index($orders, $obj_order)
    {
        echo $this->twig->render("$this->dir/orders.twig", [
            'path' => $this->path,
            'orders' => $orders,
            'obj_order' => $obj_order
        ]);
    }
}
