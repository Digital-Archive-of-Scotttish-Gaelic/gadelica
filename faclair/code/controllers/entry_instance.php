<?php

namespace controllers;
use models, views;

class entry_instance {

	public function run() {
		$model = new models\entry_instance($_GET["id"]);
		$view = new views\entry_instance($model);
		$view->show();
	}

}
