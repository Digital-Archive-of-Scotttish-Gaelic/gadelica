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
					$model = new models\writers2($id);
					$view = new views\writers2($model);
					$view->show();
				}
				else {
					$model = new models\writer2($id);
					$view = new views\writer2($model);
					$view->show();
				}
			  break;
		  case "add":
			  // add writer code here
				break;
			case "edit":
			  // edit writer code here
				break;
		}
  }

}
