<?php


namespace controllers;

use models, views;

class districts
{
	public function run($action) {

		$id = isset($_GET["id"]) ? $_GET["id"] : "0";

		switch ($action) {
			case "browse":
				if ($id == "0") { // list all districts
					$model = new models\districts();
					$view = new views\districts($model);
					$view->show();
				} else { // view particular writer
					$model = new models\district($id);
					$view = new views\district($model);
					$view->show();
				}
				break;
//			case "edit":
//				if ($id == "0") { // add new writer
//					$model = new models\writer(null); //create a dummy writer object
//				} else { // edit existing writer
//					$model = new models\writer($id);
//				}
//				$view = new views\writer($model);
//				$view->show("edit");
//				break;
//			case "save":
//				models\writers::save($_POST);
//				if (!$id) {
//					$model = new models\writers();
//					$view = new views\writers($model);
//					$view->show();
//				}
//				else {
//					$model = new models\writer($id);
//					$view = new views\writer($model);
//					$view->show("browse");
//				}
//				break;
		}
	}
}