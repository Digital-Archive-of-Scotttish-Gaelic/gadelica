<?php


namespace controllers;
use views;

class Index
{
	public function run() {
		$view = new views\Index();
		$view->show();
	}
}