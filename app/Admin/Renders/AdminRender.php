<?php

namespace App\Admin\Renders;

abstract class AdminRender {

	protected $path;
	protected $twig;

	public function __construct($twig)
	{
		$this->twig = $twig;
        $this->path = '/assets/Admin';
	}

}