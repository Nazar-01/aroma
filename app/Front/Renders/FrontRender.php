<?php

namespace App\Front\Renders;

abstract class FrontRender {

	protected $path;
	protected $twig;
	protected $dir = '/Pages';

	public function __construct($twig)
	{
		$this->twig = $twig;
        $this->path = '/assets/Front';
        $this->dir = '/Pages';
	}

}