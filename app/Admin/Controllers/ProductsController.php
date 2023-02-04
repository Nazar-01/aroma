<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Product;
use App\Admin\Renders\ProductsRender;
use App\Admin\Controllers\AdminController;

class ProductsController extends AdminController {

    private $product;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->product = new Product($pdo);
        $this->render = new ProductsRender($this->twig);
    }

    public function index()
    {
        $products = $this->product->getAll('products');
        return $this->render->index($products, $this);
    }

    public function create($errors = '')
    {
        $categories = $this->getCategories();
        return $this->render->create($categories, $errors);
    }

    public function edit($args, $errors = '')
    {
        $product = $this->product->getOne('products', $args['id']);
        $categories = $this->getCategories();
        return $this->render->edit($product, $categories, $this, $errors);
    }

    public function store($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->storeError();
        }

        $data['photo'] = $this->uploadPhoto();

        $this->product->create($data);
        header('Location: /admin/products');
    }

    public function update($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->updateError(['id' => $args['id']]);
        }

        $data['photo'] = $this->uploadPhoto($args['id']);

        $this->product->update($args['id'], $data);

        header('Location: /admin/products');
    }

    public function delete($args)
    {
        $product = $this->product->delete($args['id'], 'products');
        header('Location: /admin/products');
    }

    public function validate($args)
    {
        extract($args);
        $data = [
            'title' => $title ? htmlspecialchars($title) : false,
            'content' => $content ? htmlspecialchars($content) : false,
            'description' => $description ? htmlspecialchars($description) : false,
            'price' => $price ? htmlspecialchars($price) : false,
            'category_id' => $category ? htmlspecialchars($category) : false,
            'availability' => $availability ? htmlspecialchars($availability) : 0,
            'recommend' => $recommend ? htmlspecialchars($recommend) : 0,
            'bestseller' => $bestseller ? htmlspecialchars($bestseller) : 0,
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

    public function getCategories()
    {
        $categories = $this->product->getAll('categories');
        return $categories;
    }

    public function getProductCategory($id)
    {
        $category = $this->product->getOne('categories', $id);
        return $category['title'];
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
        $product = $this->product->getOne('products', $id);
        if ($product['photo'] === 'no-photo.png') return;
        else return $product['photo'];
    }
}
