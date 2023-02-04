<?php

namespace App\Admin\Controllers;

use App\Admin\Models\User;
use App\Admin\Renders\UsersRender;
use App\Admin\Controllers\AdminController;

class UsersController extends AdminController {

    protected $user;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->user = new User($pdo);
        $this->render = new UsersRender($this->twig);
    }

    public function index() {
        $users = $this->user->getAll('users');
        return $this->render->index($users);
    }

    public function create($errors = '') {
        return $this->render->create($errors);
    }

    public function edit($args, $errors = '') {
        $user = $this->user->getOne('users', $args['id']);
        return $this->render->edit($user, $errors);
    }

    public function store($args)
    {

        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->storeError();
        }

        $data['photo'] = $this->uploadPhoto();

        $this->user->create($data);
        header('Location: /admin/users');
    }

    public function update($args)
    {

        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->updateError(['id' => $args['id']]);
        }

        $data['photo'] = $this->uploadPhoto($args['id']);

        $this->generatePassword($args['id'], $data['password']);

        $this->user->update($args['id'], $data);

        header('Location: /admin/users');
    }

    public function delete($args)
    {
        if ( (int) $args['id'] === $_SESSION['user']['id']) {
            unset($_SESSION['user']);
        }
        $user = $this->user->delete($args['id'], 'users');
        header('Location: /admin/users');
    }

    public function validate($args)
    {
        extract($args);
        $data = [
            'name' => $name ? htmlspecialchars($name) : false,
            'email' => $email ? htmlspecialchars($email) : false,
            'password' => $password ? htmlspecialchars($password) : 0,
            'is_admin' => $is_admin ? htmlspecialchars($is_admin) : 0
        ];

        foreach ($data as $key => $value) {
            if($value === false) {
                throw new \Exception("Неверные данные", 1);
            }
        }

        return $data;
    }

    public function uploadPhoto($id = '')
    {

        if ($_FILES['photo']['name']) {
            $uploaddir = __DIR__ . '/../../../public/uploads/';
            $filename = $this->getRandStr() . $_FILES['photo']['name'];
            $uploadfile = $uploaddir . $filename;

            $move = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile);
            return $filename;
        } else if ( $id and $this->getPhoto($id) ) {
            return $this->getPhoto($id);
        }
        else {
            return 'no-photo.png';
        }
    }

    public function storeError()
    {
        $errors = "Правильно заполните все поля";
        return $this->create($errors);
    }

    public function updateError($args)
    {
        $errors = "Правильно заполните все поля";
        return $this->edit($args, $errors);
    }

    public function getPhoto($id)
    {
        $user = $this->user->getOne('users', $id);
        if ($user['photo'] === 'no-photo.png') return;
        else return $user['photo'];
    }

    public function generatePassword($id = '', $password = '')
    {
        if ($password)
             return $this->user->setPassword($id, $password);

        else if ( $id and $this->getPassword($id) ) {
            return;
        }
    }

    public function getPassword($id)
    {
        $user = $this->user->getOne('users', $id);
        return $user['password'];
    }

    public function isAdmin($id)
    {
        $user = $this->user->getOne('users', $id);

        if ($user['is_admin']) {
            return true;
        } else {
            return false;
        }
    }

}
