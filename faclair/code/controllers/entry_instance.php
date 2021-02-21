<?php

namespace controllers;
use models, views;

class entry_instance {

	public function run($action) {
		switch ($action) {
			case "edit":
			  $model = new models\entry_instance($_GET["id"]);
			  $view = new views\entry_instance($model);
			  $view->show('edit');
        break;
			case "add":
			  $model = new models\entry_instance(null);
				$model->setMhw($_GET["mhw"]);
				$model->setMpos($_GET["mpos"]);
				$model->setMsub($_GET["msub"]);
			  $view = new views\entry_instance($model);
			  $view->show('add');
	      break;
			case "update":
			  models\entry_instance::update_basics($_GET["id"],$_GET["hw"],$_GET["pos"],$_GET["sub"],$_GET["mhw"],$_GET["mpos"],$_GET["msub"]);
        if (!empty($_GET["form"])) {
					$deleted = [];
					if (!empty($_GET["delete-form"])) { $deleted = $_GET["delete-form"]; }
				  models\entry_instance::update_forms($_GET["id"],$_GET["form"],$deleted,$_GET["morph"]);
        }
				if (!empty($_GET["en"])) {
					$deleted = [];
					if (!empty($_GET["delete-en"])) { $deleted = $_GET["delete-en"]; }
					models\entry_instance::update_translations($_GET["id"],$_GET["en"],$deleted);
				}
				if (!empty($_GET["note"])) {
					$deleted = [];
					if (!empty($_GET["delete-note"])) { $deleted = $_GET["delete-note"]; }
					models\entry_instance::update_notes($_GET["id"],$_GET["note"],$deleted);
				}
			  $model = new models\entry_instance($_GET["id"]);
			  $view = new views\entry_instance($model);
			  $view->show('');
        break;
			case "save_new":
			  $db = new models\database();
			  $sql = <<<SQL
				  INSERT INTO lexemes (`source`, `hw`, `pos`, `sub`, `m-hw`, `m-pos`, `m-sub`)
					  VALUES(:source, :hw, :pos, :sub, :mhw, :mpos, :msub);
SQL;
			  $db->exec($sql, array(":source" => $_GET["source"], ":hw"=>$_GET["hw"], ":pos"=>$_GET["pos"], ":sub"=>$_GET["sub"],
				":mhw"=>$_GET["mhw"], ":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"]));
				$model = new models\entry($_GET["mhw"],$_GET["mpos"],$_GET["msub"],true);
			  $view = new views\entry($model);
			  $view->show('');
        break;
			default:
		    $model = new models\entry_instance($_GET["id"]);
		    $view = new views\entry_instance($model);
		    $view->show('');
		}
	}

}
