<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\User;
use App\Front\Renders\UsersRender;

class UsersController extends FrontController {

    private $render;
    private $user;
    private $id;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->render = new UsersRender($this->twig);
        $this->user = new User($pdo);
        $this->id = $_SESSION['user']['id'] ?? null;
    }

    public function profile($message = '', $class = '')
    {
        if ( $_SESSION['user'] ) {
            $user = $this->user->getOne('users', $this->id);
            return $this->render->profile($user, $message, $class);
        } else {
            header('Location: /');
        }
    }

    public function deletePhoto()
    {
        $this->user->deletePhoto($this->id);
        header('Location: /profile');
    }

    public function updatePhoto()
    {
        $filename = $this->uploadPhoto();
        $this->user->updatePhoto($this->id, $filename);
        header('Location: /profile');
    }

    public function update($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Правильно заполните все поля!', $method = 'profile', $class = 'alert alert-danger');
        }

        try {
            $this->checkUnique($data['email']);
        } catch (\ErrorException $e) {
            return $this->error($message = 'Такой email уже зарегестрирован на сайте!', $method = 'profile', $class = 'alert alert-danger');
        }

        $this->generatePassword($this->id, $data['old_pass'], $data['pass1'], $data['pass2']);

        $this->user->update($this->id, $data);


        return $this->error($message = 'Данные успешно обновлены', $method = 'profile', $class = 'alert alert-success');
        // header('Location: /profile');
    }

    public function validate($args)
    {
        extract($args);
        $data = [
            'name' => $name ? htmlspecialchars($name) : false,
            'email' => $email ? htmlspecialchars($email) : false,
            'old_pass' => $old_pass ? htmlspecialchars($old_pass) : 0,
            'pass1' => $pass1 ? htmlspecialchars($pass1) : 0,
            'pass2' => $pass2 ? htmlspecialchars($pass2) : 0,
        ];

        foreach ($data as $key => $value) {
            if($value === false) {
                throw new \ErrorException("Неверные данные", 1);
            }
        }

        return $data;
    }

    public function generatePassword($id = '', $old_pass = '', $pass1 = '', $pass2 = '')
    {
        if ( ($old_pass or $pass1 or $pass2) and !($old_pass and $pass1 and $pass2) ) {
            return $this->error($message = 'Заполните все поля-пароли', $method = 'profile', $class = 'alert alert-danger');
        }

        if ($pass1) {

            try {
                $this->securityCheck($old_pass);
            } catch (\ErrorException $e) {
                return $this->error($message = 'Пароль не верный!', $method = 'profile', $class = 'alert alert-danger');
            }

            try {
                $this->coincidencePass($pass1, $pass2);
            } catch (\ErrorException $e) {
                return $this->error($message = 'Пароли не совпадают!', $method = 'profile', $class = 'alert alert-danger');
            }
            return $this->user->setPassword($id, $pass1);
        }

        else if ( $id and $this->getPassword($id) ) {
            return;
        }
    }

    public function getPassword($id)
    {
        $user = $this->user->getOne('users', $id);
        return $user['password'];
    }

    public function checkUnique($email)
    {
        $user = $this->user->getUserByEmail($email);
        if ($user and $user['id'] !== $this->id) {
            throw new \ErrorException("Неуникальный email", 1);
        } else {
            return;
        }
    }

    public function securityCheck($conf_pass)
    {
        
        if ( $this->getPassword($this->id) !== MD5($conf_pass) ) {
            throw new \ErrorException("Неверный пароль", 1);
        } else {
            return;
        }
    }

    public function delete()
    {
        $user = $this->user->delete($this->id, 'users');
        unset($_SESSION['user']);
        header('Location: /');
    }

}
