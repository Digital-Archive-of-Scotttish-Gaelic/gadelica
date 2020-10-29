<?php

namespace controllers;
use models, views;

class writers2
{

	public function run($action) {

    $id = isset($_GET["id"]) ? $_GET["id"] : "0";

		switch ($action) {
      case "search":
        echo "WAIT!";
			  break;
      case "browse":
				$model = new models\writer2($id);
				$view = new views\writer2($model);
				$view->show();
			  break;
		}
  }

}
