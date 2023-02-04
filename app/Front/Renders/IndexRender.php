<?php

namespace App\Front\Renders;

use App\Front\Renders\FrontRender;

class IndexRender extends FrontRender {

    public function index($categories, $bestsellers)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'categories' => $categories,
            'bestsellers' => $bestsellers,
        ]);
    }

}
