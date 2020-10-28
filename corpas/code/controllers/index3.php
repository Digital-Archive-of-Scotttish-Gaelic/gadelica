<?php


namespace controllers;
use views;

class index3
{
	public function run() {
		$view = new views\index3();
		$view->show();
	}
}