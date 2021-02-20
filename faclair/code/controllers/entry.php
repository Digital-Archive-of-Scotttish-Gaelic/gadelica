<?php

namespace controllers;
use models, views;

class entry {

	public function run($action) {

		switch ($action) {
			case "add_part":
			  $model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"],false);
			  $view = new views\entry($model);
				$view->show('add_part');
				break;
			case "save_part":
			  $db = new models\database();
				$sql = <<<SQL
					INSERT INTO parts (`m-hw`, `m-pos`, `m-sub`, `m-p-hw`, `m-p-pos`, `m-p-sub`)
						VALUES(:mhw, :mpos, :msub, :mphw, :mppos, :mpsub);
SQL;
				$db->exec($sql, array(":mhw"=>$_GET["mhw"], ":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"],
					":mphw"=>$_GET["mphw"], ":mppos"=>$_GET["mppos"], ":mpsub"=>$_GET["mpsub"]));
				$model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"],true);
			  $view = new views\entry($model);
			  $view->show('');
				break;
			case "delete_part":
			  $db = new models\database();
			  if (!isset($_GET["id"])) {
				  die('<h2>Error: unrecognised parameter</h2>');
			  } else {
				  $sql = <<<SQL
					  DELETE FROM parts WHERE id = :id
SQL;
				  $db->exec($sql, array(":id" => $_GET["id"]));
        }
				$model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"],true);
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
