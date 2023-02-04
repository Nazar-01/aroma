<?php

namespace App\Front\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Front\Models\Product;

use App\Front\Renders\Templates\RecommendedRender;
use App\Front\Renders\Templates\SubscriptionsRender;

abstract class FrontController {

	protected $twig;
	protected $product;

	public function __construct($pdo)
	{
		$this->twig = new Environment( new FilesystemLoader('../app/Front/Views') );
		$this->product = new Product($pdo);
        $this->addGlobal();
	}

    public function addGlobal()
    {
        if ( isset($_SESSION['user']) ) {
            $this->twig->addGlobal('user', $_SESSION['user']);
        }
        $this->twig->addGlobal('obj', $this);
    }

	public function getRecommended()
	{
		$recommended = $this->product->getRecommended();
		return (new RecommendedRender($this->twig))->index($recommended, $this);
	}

    public function getSubscription()
    {
        
        return (new SubscriptionsRender($this->twig))->index($this);
    }

	public function getRandStr($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function uploadPhoto()
    {
        if ($_FILES['photo']['name']) {
            $uploaddir = __DIR__ . '/../../../public/uploads/';
            $filename = $this->getRandStr() . $_FILES['photo']['name'];
            $uploadfile = $uploaddir . $filename;

            $move = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile);
            return $filename;
        } else {
            return 'no-photo.png';
        }
    }

    public function validate($args)
    {
        extract($args);
        $data = [];
        foreach ($args as $key => $value) {
            $data["$key"] = $$key ? htmlspecialchars($$key) : false;
        }

        foreach ($data as $key => $value) {
            if($value === false) {
                throw new \ErrorException("Неверные данные", 1);
            }
        }

        return $data;
    }

    public function coincidencePass($pass1, $pass2)
    {
        if ($pass1 !== $pass2) {
            throw new \ErrorException("Неверные данные", 1);
        }
        return;
    }

    public function error($message, $method, $class)
    {
        return $this->$method($message, $class);
    }

    public function getProductsCount()
    {
        if (isset($_SESSION['cart'])) {
            $count = count($_SESSION['cart']) ?? 0;
        } else {
            $count = 0;
        }
        echo $count;
    }
}