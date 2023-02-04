<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;

class AuthController extends AdminController {

    public function check() {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }
}