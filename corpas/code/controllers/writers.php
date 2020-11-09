<?php

namespace controllers;
use models, views;

class writers
{

	public function run($action) {

    $id = isset($_GET["id"]) ? $_GET["id"] : "0";

		switch ($action) {
			case "browse":
				if ($id == "0") { // list all writers
					$model = new models\writers();
					$view = new views\writers($model);
					$view->show();
				} else { // view particular writer
					$model = new models\writer($id);
					$view = new views\writer($model);
					$view->show("browse");
				}
				break;
			case "add":
				$model = new models\writer(null); //create a dummy writer object
				$view = new views\writer($model);
				$view->show("edit");
				break;
			case "edit":
				$model = new models\writer($id);
				$view = new views\writer($model);
				$view->show("edit");
				break;
			case "save":
				models\writers::save($_POST);
				$model = new models\writer($id);
				$view = new views\writer($model);
				$view->show("browse");
				break;
		}
	}
}
