<?php

namespace controllers;
use models, views;

class search {

	public function run() {
		$model = new models\search();
		$view = new views\search($model);
		$view->show();
	}

}
