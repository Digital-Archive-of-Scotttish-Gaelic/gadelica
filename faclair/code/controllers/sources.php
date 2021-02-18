<?php

namespace controllers;
use views;

class sources
{
	public function run() {
		$view = new views\sources();
		$view->show();
	}
}
