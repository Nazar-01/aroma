<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class CategoriesRender extends AdminRender {

    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Categories';
    }

    public function index($categories)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'categories' => $categories
        ]);
    }

    public function create($errors) {
        echo $this->twig->render("$this->dir/create.twig", [
            'path' => $this->path,
            'errors' => $errors
        ]);
    }

    public function edit($category, $errors) {
        echo $this->twig->render("$this->dir/edit.twig", [
            'path' => $this->path,
            'category' => $category,
            'errors' => $errors
        ]);
    }
}
