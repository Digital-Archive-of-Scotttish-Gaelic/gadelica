<?php

namespace controllers;
use models, views;

class writers2
{

	public function run($action) {

    $id = isset($_GET["id"]) ? $_GET["id"] : "0";

		switch ($action) {
			case "browse":
				if ($id == "0") {
					$model = new models\writers2();
					$view = new views\writers2($model);
					$view->show();
				} else {
					$model = new models\writer2($id);
					$view = new views\writer2($model,"browse");
					$view->show();
				}
				break;
			case "add":
				$model = new models\writer2(null); //create a dummy writer object
				$view = new views\writer2($model);
				$view->show("edit");
				break;
			case "edit":
				$model = new models\writer2($id);
				$view = new views\writer2($model);
				$view->show("edit");
				break;
			case "save":
				models\writers2::save($_POST);
				$model = new models\writer2($id);
				$view = new views\writer2($model);
				$view->show("browse");
				break;
		}
	}
}
