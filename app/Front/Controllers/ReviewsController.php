<?php

namespace App\Front\Controllers;

use App\Front\Controllers\FrontController;
use App\Front\Models\Review;
use App\Front\Models\User;

class ReviewsController extends FrontController {

    private $review;
    protected $pdo;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->review = new Review($pdo);
        $this->pdo = $pdo;
    }

    public function getReviews($product_id)
    {
        $reviews = $this->review->getReviews($product_id);
        return $reviews;

    }

    public function getAuthor($user_id)
    {
        $user = ( new User($this->pdo) )->getOne('users', $user_id);
        return $user;
    }

    public function create($args)
    {
        if ( !isset($_SESSION['user']) ) {
            return $this->jsAuthError();
        }

        try {
            $data = $this->validate($args['data']);
        } catch (\ErrorException $e) {
            return $this->jsValidateError();
        }

        try {
           $this->checkRepeat($data['product_id']); 
        } catch (\ErrorException $e) {
            return $this->jsRepeatError();
        }

        $this->review->create($_SESSION['user']['id'], $data);

        return $this->jsSuccess();
    }

    public function validate($args)
    {
        if ( !isset($args['rating']) ) {
            throw new \ErrorException("Для добавления отзыва поставьте оценку и напишите комментарий!", 1);
        }

        return $data = parent::validate($args);
    }

    public function getAverageRating($product_id)
    {
        $reviews = $this->getReviews($product_id);

        if (!$reviews) return 0;

        $score = 0;
        foreach ($reviews as $key => $value) {
            $score += $value['score'];
        }

        return round( ($score / count($reviews)), 1);
    }

    public function jsAuthError()
    {
        echo 'AuthError';
    }

    public function jsValidateError()
    {
        echo 'ValidateError';
    }

    public function jsSuccess()
    {
        echo 'Success';
    }

    public function checkRepeat($product_id)
    {
        $reviews = $this->review->checkRepeat($product_id);
        foreach ($reviews as $key => $value) {
            if ($value['user_id'] === $_SESSION['user']['id']) {
                return throw new \ErrorException("Error", 1);
            }            
        }
    }

    public function jsRepeatError()
    {
        echo 'RepeatError';
    }
}
