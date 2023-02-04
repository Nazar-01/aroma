<?php

namespace App\Front\Renders\Templates;

use App\Front\Renders\FrontRender;

class SubscriptionsRender extends FrontRender {

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = 'Templates';
    }

    public function index($obj, $message = '', $class = '')
    {
        echo $this->twig->render("$this->dir/subscription.twig", [
            'path' => $this->path,
            'obj' => $obj,
            'message' => $message,
            'class' => $class
        ]);
    }

}
