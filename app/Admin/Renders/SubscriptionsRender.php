<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class SubscriptionsRender extends AdminRender {

    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Subscriptions';
    }

    public function index($subs)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'subs' => $subs
        ]);
    }

    public function create($errors) {
        echo $this->twig->render("$this->dir/create.twig", [
            'path' => $this->path,
            'errors' => $errors
        ]);
    }

    public function edit($sub, $errors) {
        echo $this->twig->render("$this->dir/edit.twig", [
            'path' => $this->path,
            'sub' => $sub,
            'errors' => $errors
        ]);
    }
}
