<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class UsersRender extends AdminRender {
    
    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Users';
    }

    public function index($users)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'users' => $users
        ]);
    }

    public function create($errors) {
        echo $this->twig->render("$this->dir/create.twig", [
            'path' => $this->path,
            'errors' => $errors
        ]);
    }

    public function edit($user, $errors) {
        echo $this->twig->render("$this->dir/edit.twig", [
            'path' => $this->path,
            'user' => $user,
            'errors' => $errors
        ]);
    }
}
