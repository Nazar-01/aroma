<?php

namespace App\Admin\Renders;

use App\Admin\Renders\AdminRender;

class ReviewsRender extends AdminRender {

    protected $dir;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->dir = '/Reviews';
    }

    public function index($reviews, $obj_review)
    {
        echo $this->twig->render("$this->dir/index.twig", [
            'path' => $this->path,
            'reviews' => $reviews,
            'obj_review' => $obj_review
        ]);
    }

}
