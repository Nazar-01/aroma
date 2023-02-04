<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\Subscription;
use App\Front\Renders\Templates\SubscriptionsRender;

class SubscriptionsController extends FrontController {

    private $sub;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->sub = new Subscription($pdo);
        $this->render = new SubscriptionsRender($this->twig);
    }

    public function create($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\ErrorException $e) {
            return $this->jsValidateError();
        }

        try {
            $this->checkUnique($data['email']);
        } catch (\ErrorException $e) {
            return $this->jsUniqueError();
        }

        $this->sub->create($data);
    }

    public function checkUnique($email)
    {

        $sub = $this->sub->getSubByEmail($email);
        if ($sub) {
            throw new \ErrorException("Неуникальный email", 1);
        } else {
            return;
        }
    }

    public function jsValidateError()
    {
        echo 'ValidateError';
    }

    public function jsUniqueError()
    {
        echo 'UniqueError';
    }

}
