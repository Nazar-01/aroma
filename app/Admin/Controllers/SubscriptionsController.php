<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Subscription;
use App\Admin\Renders\SubscriptionsRender;
use App\Admin\Controllers\AdminController;

class SubscriptionsController extends AdminController {

    private $subscription;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->sub = new Subscription($pdo);
        $this->render = new SubscriptionsRender($this->twig);
    }

    public function index()
    {
        $subs = $this->sub->getAll('subscriptions');
        return $this->render->index($subs);
    }

    public function create($errors = '')
    {
        return $this->render->create($errors);
    }

    public function edit($args, $errors = '')
    {
        $sub = $this->sub->getOne('subscriptions', $args['id']);
        return $this->render->edit($sub, $errors);
    }

    public function store($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->storeError();
        }

        $this->sub->create($data);
        header('Location: /admin/subs');
    }

    public function update($args)
    {
        try {
            $data = $this->validate($args);
        } catch (\Exception $e) {
            return $this->updateError(['id' => $args['id']]);
        }

        $this->sub->update($args['id'], $data);

        header('Location: /admin/subs');
    }

    public function delete($args)
    {
        $sub = $this->sub->delete($args['id'], 'subscriptions');
        header('Location: /admin/subs');
    }

    public function validate($args)
    {
        extract($args);
        $data = [
            'email' => $email ? htmlspecialchars($email) : false,
        ];

        foreach ($data as $key => $value) {
            if($value === false) {
                throw new \Exception("Неверные данные", 1);
            }
        }

        return $data;
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
}
