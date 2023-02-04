<?php

namespace App\Admin\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AdminController {

	protected $twig;

	public function __construct()
	{
		$this->twig = new Environment( new FilesystemLoader('../app/Admin/Views') );
	}

	public function getRandStr($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}