<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Renders\OrdersRender;
use App\Front\Models\Order;
use App\Front\Controllers\CartController;

class OrdersController extends FrontController {

    private $render;
    protected $order;
    private $cart;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new OrdersRender($this->twig);
        $this->order = new Order($pdo);
        $this->cart = new CartController($pdo);
    }

    public function index()
    {
        if (!$_SESSION['user']) {
            return header('Location: /');
        }

        $orders = $this->order->getOrders($_SESSION['user']['id']);
        return $this->render->index($orders, $this);
    }

    public function makeOrder($args)
    {
        if (!$args['comment']) {
            $args['comment'] = '...';
        }

        $data = $this->validate($args);
        $order_id = $this->order->createOrder($data, $_SESSION['user']['id'])['LAST_INSERT_ID()'];

        foreach ($_SESSION['cart'] as $key => $value) {
            $this->order->bindProductOrder($order_id, $value['id'], $value['count']);
        }

        $this->cart->clear();

        return header('Location: /orders');
    }

    public function getProductsOrders($order_id)
    {
        $posts = $this->order->getProductsOrders($order_id);
        return $posts;
    }

    public function getProduct($product_id)
    {
        return $this->order->getOne('products', $product_id);
    }

    public function getTotalPrice($order_id)
    {
        $total = 0;
        $posts = $this->order->getProductsOrders($order_id);
        foreach ($posts as $key => $value) {
            $price = $this->getProduct($value['product_id'])['price'];
            $count = $value['count'];
            $total += $price * $count;
        }

        return $total;
    }
}
