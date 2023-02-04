<?php

namespace App\Front\Renders;

use App\Front\Renders\UsersRender;

class UsersRender extends FrontRender {

    public function profile($user, $message, $class)
    {
        echo $this->twig->render("$this->dir/profile.twig", [
            'path' => $this->path,
            'user' => $user,
            'message' => $message,
            'class' => $class

        ]);
    }

}
