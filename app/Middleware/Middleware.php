<?php

namespace App\Middleware;

use App\Admin\Models\User;
use App\Admin\Controllers\UsersController;

class Middleware {

    private $handler;
    private $vars;
    private $pdo;
    private $user_id;

    public function __construct($routeInfo1, $routeInfo2, $httpMethod)
    {
        $this->handler = $routeInfo1;
        $this->vars = ($httpMethod == 'POST')? $_POST : $routeInfo2;
        $this->pdo = (new \App\Database\DbConnect())->connect('localhost', 'aroma', 'root', '');
        $this->user_id = $_SESSION['user']['id'] ?? null;
    }

    public function check()
    {
        if (!$this->user_id) {
            return $this->restrictedAccess(); 
        }

        $isAdmin = (new UsersController($this->pdo))->isAdmin($this->user_id);

        if ($isAdmin) {
            return $this->fullAccess();            
        } else {
            return $this->restrictedAccess();
        }
    }

    public function restrictedAccess()
    {
        if (str_contains($_SERVER['REQUEST_URI'], 'admin')) {
            header('Location: /');
        } else {
           return $this->fullAccess();
        }
    }

    public function fullAccess()
    {
        list($class, $method) = explode("@", $this->handler, 2);
        call_user_func([new $class($this->pdo), $method], $this->vars);
    }

}