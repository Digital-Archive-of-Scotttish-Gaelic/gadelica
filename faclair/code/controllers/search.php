<?php

namespace controllers;
use models, views;

class search {

	public function run() {
		$model = new models\entries();
		$view = new views\search($model);
		$view->show();
	}

}
