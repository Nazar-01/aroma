<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class OrdersRender extends AdminRender {

    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Orders';
    }

    public function index($orders, $obj_order)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'orders' => $orders,
            'obj_order' => $obj_order
        ]);
    }
}
