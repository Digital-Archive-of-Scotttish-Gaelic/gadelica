<?php

namespace controllers;
use models, views;

class englishes {

	public function run() {
		$model = new models\englishes();
		$view = new views\englishes($model);
		$view->show();
	}
	
}
