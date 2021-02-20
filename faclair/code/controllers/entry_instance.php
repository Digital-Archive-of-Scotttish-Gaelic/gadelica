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
			case "save":
			  $db = new models\database();
			  $sql = <<<SQL
				  UPDATE lexemes
					SET `hw` = :hw, `pos` = :pos, `sub` = :sub, `m-hw` = :mhw, `m-pos` = :mpos, `m-sub` = :msub
					WHERE id = :id
SQL;
			  $db->exec($sql, array(":hw"=>$_GET["hw"], ":pos"=>$_GET["pos"], ":sub"=>$_GET["sub"], ":mhw"=>$_GET["mhw"],
				  ":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"], ":id"=>$_GET["id"]));
        if (!empty($_GET["form"])) {
		      //delete forms
		      if (!empty($_GET["delete-form"])) {
			      foreach ($_GET["delete-form"] as $formId => $value) {
				      $sql = "DELETE FROM `forms` WHERE id = :formId";
				      $db->exec($sql, array(":formId" => $formId));
				      unset($_GET["form"][$formId]);
				      unset($_GET["morph"][$formId]);
			      }
		      }
		      //insert a new form
		      if (isset($_GET["form"][0]) && $_GET["form"][0] != "") {
			      $sql = <<<SQL
				      INSERT INTO `forms` (`form`, `morph`, `lexeme_id`) VALUES (:form, :morph, :lexemeId)
SQL;
			      $db->exec($sql, array(":form" => $_GET["form"][0], ":morph" => $_GET["morph"][0], ":lexemeId" => $_GET["id"]));
			      unset($_GET["form"][0]);
			      unset($_GET["morph"][0]);
		      }
		      //update existing forms
		      foreach ($_GET["form"] as $id => $value) {
			      $sql = <<<SQL
				      UPDATE forms SET `form` = :form, `morph` = :morph
					      WHERE id = :id
SQL;
			      $db->exec($sql, array(":form" => $value, ":morph" => $_GET["morph"][$id], ":id" => $id));
		      }
	      }
	      if (!empty($_GET["en"])) {
		      //delete translations
		      if (!empty($_GET["delete-en"])) {
			      foreach ($_GET["delete-en"] as $enId => $value) {
				      $sql = "DELETE FROM `english` WHERE id = :enId";
				      $db->exec($sql, array(":enId" => $enId));
				      unset($_GET["en"][$enId]);
			      }
		      }
		      //insert a new translation
		      if (isset($_GET["en"][0]) && $_GET["en"][0] != "") {
			      $sql = <<<SQL
				      INSERT INTO `english` (`en`, `lexeme_id`) VALUES (:en, :lexemeId)
SQL;
			      $db->exec($sql, array(":en" => $_GET["en"][0], ":lexemeId" => $_GET["id"]));
			      unset($_GET["en"][0]);
		      }
		      //update existing translations
		      foreach ($_GET["en"] as $id => $value) {
			      $sql = <<<SQL
				      UPDATE english SET `en` = :en
					      WHERE id = :id
SQL;
			      $db->exec($sql, array(":en" => $value, ":id" => $id));
		      }
	      }
				if (!empty($_GET["note"])) {
					//delete notes
					if (!empty($_GET["delete-note"])) {
						foreach ($_GET["delete-note"] as $noteId => $value) {
							$sql = "DELETE FROM `notes` WHERE id = :noteId";
							$db->exec($sql, array(":noteId" => $noteId));
							unset($_GET["note"][$noteId]);
						}
					}
					//insert a new note
					if (isset($_GET["note"][0]) && $_GET["note"][0] != "") {
						$sql = <<<SQL
							INSERT INTO `notes` (`note`, `lexeme_id`) VALUES (:note, :lexemeId)
SQL;
						$db->exec($sql, array(":note" => $_GET["note"][0], ":lexemeId" => $_GET["id"]));
						unset($_GET["note"][0]);
					}
					//update existing notes
					foreach ($_GET["note"] as $id => $value) {
						$sql = <<<SQL
							UPDATE notes SET `note` = :note
								WHERE id = :id
SQL;
						$db->exec($sql, array(":note" => $value, ":id" => $id));
					}
				}				
			  $model = new models\entry_instance($_GET["id"]);
			  $view = new views\entry_instance($model);
			  $view->show('');
        break;
			default:
		    $model = new models\entry_instance($_GET["id"]);
		    $view = new views\entry_instance($model);
		    $view->show('');
		}
	}

}
