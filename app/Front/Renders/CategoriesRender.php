<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class CategoriesRender extends FrontRender {

    public function index($categories)
    {
        echo $this->twig->render("$this->dir/categories.twig", [
            'path' => $this->path,
            'categories' => $categories
        ]);
    }

}
