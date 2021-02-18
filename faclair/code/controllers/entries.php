<?php

namespace controllers;
use models, views;

class entries
{

	public function run() {
		$model = new models\entries();
		$view = new views\entries($model);
		$view->show();
	}
	
}
