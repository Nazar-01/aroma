<?php

namespace App\Admin\Controllers;

use App\Admin\Models\DashboardRender;

class DashboardController {

    private $render;

    public function __construct($pdo, $twig)
    {
        $this->render = new DashboardRender($twig);
    }

    public function index() {
        return $this->render->index();
    }
}