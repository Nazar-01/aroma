<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class AuthRender extends FrontRender {

    public function login($message, $class)
    {
        echo $this->twig->render("$this->dir/login.twig", [
            'path' => $this->path,
            'message' => $message,
            'class' => $class
        ]);
    }

    public function register($message, $class)
    {
        echo $this->twig->render("$this->dir/register.twig", [
            'path' => $this->path,
            'message' => $message,
            'class' => $class
        ]);
    }

}
