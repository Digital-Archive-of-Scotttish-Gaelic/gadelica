<?php

namespace controllers;
use models, views;

class entry {

	public function run() {
		$model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"]);
		$view = new views\entry($model);
		$view->show();
	}

}
