<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Review;
use App\Admin\Renders\ReviewsRender;
use App\Admin\Controllers\AdminController;

class ReviewsController extends AdminController {

    private $product;
    private $render;

    public function __construct($pdo)
    {
        parent::__construct();
        $this->review = new Review($pdo);
        $this->render = new ReviewsRender($this->twig);
    }

    public function index() {
        $reviews = $this->review->getAll('reviews');
        return $this->render->index($reviews, $this);
    }

    public function edit($id) {
        return $this->render->edit($id);
    }

    public function delete($args)
    {
        $this->review->delete($args['id'], 'reviews');
        header('Location: /admin/reviews');
    }

    public function approve($args)
    {
        $this->review->approve($args['id']);
        header('Location: /admin/reviews');
    }

    public function prohibit($args)
    {
        $this->review->prohibit($args['id']);
        header('Location: /admin/reviews');
    }

    public function checkApproved($id)
    {
        $review = $this->review->getOne('reviews', $id);
        return $review['approved'];
    }

    public function getAuthor($id)
    {
        $user_id = $this->review->getOne('reviews', $id)['user_id'];

        $author = $this->review->getOne('users', $user_id)['email'];

        return $author;
    }

    public function getProduct($id)
    {
        $product_id = $this->review->getOne('reviews', $id)['product_id'];

        $product = $this->review->getOne('products', $product_id)['title'];

        return $product;
    }
}
