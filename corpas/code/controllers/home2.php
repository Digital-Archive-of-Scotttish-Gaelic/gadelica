<?php

namespace controllers;
use views;

class home2
{

	public function run() { // why does this work????
		$view = new views\home2();
		$view->show();
	}

}
