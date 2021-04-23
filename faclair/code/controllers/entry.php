<?php

namespace controllers;
use models, views;

class entry {

	public function run($action) {

		switch ($action) {
			case "random":
			$model = new models\entry(null,null,null,null);
			$view = new views\entry($model);
			$view->show('');
			  break;
			default:
			  $model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"],true);
			  $view = new views\entry($model);
			  $view->show('');
		}
	}

}
