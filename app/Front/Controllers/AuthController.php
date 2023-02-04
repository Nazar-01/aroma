<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\User;
use App\Front\Renders\AuthRender;

class AuthController extends FrontController {

    private $render;
    private $user;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new AuthRender($this->twig);
        $this->user = new User($pdo);
    }

    public function loginPage($message = '', $class = '')
    {
        return $this->render->login($message, $class);
    }

    public function registerPage($message = '', $class = '')
    {
        return $this->render->register($message, $class);
    }

    public function registerStore($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Повторите ввод данных, правильно заполнив все поля!', $method = 'registerPage', $class = 'alert alert-danger');
        }

        $data['photo'] = $this->uploadPhoto();

        try {
            $this->checkUnique($data['email']);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Такой email уже зарегестрирован на сайте!', $method = 'registerPage', $class = 'alert alert-danger');
        }

        try {
            $this->coincidencePass($data['password'], $data['confirmPassword']);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Пароли не совпадают!', $method = 'registerPage', $class = 'alert alert-danger');
        }

        $this->user->create($data);
        return $this->loginPage($message = 'Вы успешно зарегестрировались, можете войти в систему!', $class = 'alert alert-success');
    }

    public function login($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Повторите ввод данных, правильно заполнив все поля!', $method = 'loginPage', $class = 'alert alert-danger');
        }

        if ( $user = $this->user->tryGetUser($data['email'], $data['password']) ) {
            $_SESSION['user'] = $user;
            header('Location: /');
        } else {
            return $this->error($message = 'Неверные данные!', $method = 'loginPage', $class = 'alert alert-danger');
        }
    }

    public function checkUnique($email)
    {
        $user = $this->user->getUserByEmail($email);
        if ($user) {
            throw new \ErrorException("Неуникальный email", 1);
        } else {
            return;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}
