<?php

namespace controllers;
use views, models;

class sources {

	public function run() {
		$model = new models\sources();
		$view = new views\sources($model);
		$view->show();
	}

}
