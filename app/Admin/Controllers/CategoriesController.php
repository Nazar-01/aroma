<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use App\Admin\Models\Category;
use App\Admin\Renders\CategoriesRender;

class CategoriesController extends AdminController {

    private $category;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->category = new Category($pdo);
        $this->render = new CategoriesRender($this->twig);
    }

    public function index()
    {
        $categories = $this->category->getAll('categories');
        return $this->render->index($categories);
    }

    public function create($errors = '')
    {
        return $this->render->create($errors);
    }

    public function edit($args, $errors = '')
    {
        $category = $this->category->getOne('categories', $args['id']);
        return $this->render->edit($category, $errors);
    }

    public function delete($args)
    {
        $category = $this->category->delete($args['id'], 'categories');
        header('Location: /admin/categories');
    }

    public function store($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->storeError();
        }

        $data['photo'] = $this->uploadPhoto();

        $this->category->create($data);
        header('Location: /admin/categories');
    }

    public function update($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->updateError(['id' => $args['id']]);
        }

        $data['photo'] = $this->uploadPhoto($args['id']);

        $this->category->update($args['id'], $data);

        header('Location: /admin/categories');
    }

    public function validate($args)
    {
        extract($args);
        $data = [
            'title' => $title ? htmlspecialchars($title) : false,
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
        $category = $this->category->getOne('categories', $id);
        if ($category['photo'] === 'no-photo.png') return;
        else return $category['photo'];
    }
}
