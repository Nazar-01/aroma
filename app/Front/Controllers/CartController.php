<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Controllers\OrdersController;
use App\Front\Renders\CartRender;
use App\Front\Models\Product;

class CartController extends FrontController {

    private $render;
    protected $product;
    protected $pdo;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new CartRender($this->twig);
        $this->product = new Product($pdo);
        $this->pdo = $pdo;
        $this->init();
    }

    public function init()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index()
    {
        return $this->render->index($_SESSION['cart'], $this);
    }

    public function add($product)
    {
        if ( !($product = $this->validate($product)) ) {
            return;
        }

        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['id'] === $product['id']) {
                $_SESSION['cart'][$key]['count'] += $product['count'];
                return;
            }
        }

        $_SESSION['cart'][] = $product;
    }

    public function validate($product)
    {
        if ($product['count'] <= 0) {
            return false;
        }

        $product['id'] = htmlspecialchars($product['id']);
        $product['count'] = (int) $product['count'];
        return $product;
    }

    public function getProduct($id)
    {
        return $product = $this->product->getOne('products', $id);
    }

    public function update($args)
    {
        $cart = $this->validateAjax($args['data']);
        unset($_SESSION['cart']);
        $_SESSION['cart'] = $cart;
        $this->removeNegative();
       
    }

    public function validateAjax($cart)
    {
        foreach ($cart as $key => $value) {
            $cart[$key]['id'] = htmlspecialchars($value['id']);
            $cart[$key]['count'] = (int) $value['count'];
        }
        return $cart;
    }

    public function removeNegative()
    {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['count'] <= 0) {
                unset($_SESSION['cart'][$key]);
            }
        }
    }

    public function getClear()
    {
        unset($_SESSION['cart']);
        header('Location: /cart');
    }

    public function clear()
    {
        unset($_SESSION['cart']);
    }

    public function getTotalPrice()
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $key => $value) {
            $product = $this->product->getOne('products', $value['id']);
            $total +=  $product['price'] * $value['count'];
        }
        return $total;
    }

    public function checkout($message = '', $class = '')
    {
        if (!$_SESSION['cart'])
            header('Location: /');

        if (!$_SESSION['user']) {
            header('Location: /login');
        }
        
        return $this->render->checkout($_SESSION['cart'], $this, $message, $class);
    }

    public function makeOrder($args)
    {
        $order = new OrdersController($this->pdo);
        try {
            $order->makeOrder($args);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Правильно заполните все поля!', $method = 'checkout', $class = 'alert alert-danger');
        }
    }
}
