<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Order;
use App\Admin\Renders\OrdersRender;
use App\Admin\Controllers\AdminController;

class OrdersController extends AdminController {

    private $order;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->order = new Order($pdo);
        $this->render = new OrdersRender($this->twig);
    }

    public function index() {
        $orders = $this->order->getAll('orders');

        return $this->render->index($orders, $this);
    }

    public function getUserEmail($id)
    {
        $user = $this->order->getOne('users', $id);
        return $user['email'];
    }

    public function getProducts($id)
    {
        $products = $this->order->getProducts($id);
        return $products;
    }

    public function getProduct($id)
    {
        return $this->order->getOne('products', $id);
        
    }

    public function getTotal($id)
    {
        $total = 0;
        $products = $this->order->getProducts($id);
        $price = '';
        foreach ($products as $key => $value) {
            $count = $value['count'];
            $product = $this->order->getOne('products', $value['product_id']);
            $price = $product['price'];
            
            $total += $count * $price;
        }

        return $total;
    }
}
