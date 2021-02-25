<?php

namespace controllers;
use models, views;

class home {

	public function run() {
		$model = new models\home();
		$view = new views\home($model);
		$view->show();
	}

}
