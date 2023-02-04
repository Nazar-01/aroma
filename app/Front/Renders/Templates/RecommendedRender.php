<?php

namespace App\Front\Renders\Templates;

use App\Front\Renders\FrontRender;

class RecommendedRender extends FrontRender {

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = 'Templates';
    }

    public function index($recommended, $obj_product)
    {
        echo $this->twig->render("$this->dir/recommended.twig", [
            'path' => $this->path,
            'recommended' => $recommended,
            'obj_product' => $obj_product
        ]);
    }

}
