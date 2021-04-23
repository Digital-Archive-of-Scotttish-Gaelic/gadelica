<?php

namespace controllers;
use views;

class admin {

	public function run() {
		$view = new views\admin();
		$view->show();
	}

}
