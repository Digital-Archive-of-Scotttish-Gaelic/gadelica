<?php

namespace controllers;
use models, views;

class source {

	public function run() {
		$model = new models\source($_GET["id"]);
		$view = new views\source($model);
		$view->show();
	}

}
