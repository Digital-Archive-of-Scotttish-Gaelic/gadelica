<?php

namespace controllers;
use models, views;

class english {

	public function run($action) {
		$model = new models\english($_GET["en"]);
		$view = new views\english($model);
		$view->show('');
	}

}
