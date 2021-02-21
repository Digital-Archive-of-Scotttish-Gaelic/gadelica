<?php

namespace models;

class entry_instance {

  private $_id;
  private $_source;
  private $_hw;
  private $_mhw;
  private $_pos;
  private $_mpos;
  private $_sub;
  private $_msub;
  private $_forms = array();
  private $_translations = array();
  private $_notes = array();
  private $_db;   // an instance of models\database

	public function __construct($id) {
    $this->_id = $id;
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `source`, `hw`, `pos`, `sub`, `m-hw`, `m-pos`, `m-sub`
    		FROM `lexemes`
    		WHERE `id` = :id
SQL;
    $results = $this->_db->fetch($sql, array(":id" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_source = $nextResult["source"];
      $this->_hw = $nextResult["hw"];
      $this->_pos = $nextResult["pos"];
      $this->_sub = $nextResult["sub"];
      $this->_mhw = $nextResult["m-hw"];
      $this->_mpos = $nextResult["m-pos"];
      $this->_msub = $nextResult["m-sub"];
    }
    $sql = <<<SQL
      SELECT `form`, `morph`, `id`
        FROM `forms`
        WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_forms[] = [$nextResult["form"], $nextResult["morph"], $nextResult["id"]];
    }
    $sql = <<<SQL
  		SELECT `en`, `id`
  			FROM `english`
  			WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_translations[] = [$nextResult["en"],$nextResult["id"]];
    }
    $sql = <<<SQL
      SELECT `note`, `id`
        FROM `notes`
        WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_notes[] = [$nextResult["note"],$nextResult["id"]];
    }
	}

  public function getId() {
    return $this->_id;
	}

  public function getSource() {
    return $this->_source;
	}

  public function getHw() {
    return $this->_hw;
	}

  public function getMhw() {
    return $this->_mhw;
	}

  public function getPos() {
    return $this->_pos;
	}

  public function getMpos() {
    return $this->_mpos;
  }

  public function getSub() {
    return $this->_sub;
	}

  public function getMsub() {
    return $this->_msub;
	}

  public function getForms() {
    return $this->_forms;
	}

  public function getTranslations() {
    return $this->_translations;
	}

  public function getNotes() {
    return $this->_notes;
	}

  public function setMhw($s) {
    $this->_mhw = $s;
	}

  public function setMpos($s) {
    $this->_mpos = $s;
	}

  public function setMsub($s) {
    $this->_msub = $s;
	}

  public static function update_basics($id,$hw,$pos,$sub,$mhw,$mpos,$msub) {
    $db = new database();
    $sql = <<<SQL
      UPDATE lexemes
      SET `hw` = :hw, `pos` = :pos, `sub` = :sub, `m-hw` = :mhw, `m-pos` = :mpos, `m-sub` = :msub
      WHERE id = :id
SQL;
    $db->exec($sql, array(":hw"=>$hw, ":pos"=>$pos, ":sub"=>$sub, ":mhw"=>$mhw,
      ":mpos"=>$mpos, ":msub"=>$msub, ":id"=>$id));
  }

  public static function update_forms($id,$forms,$deleted,$morphs) {
    $db = new database();
    //delete forms
		if (!empty($deleted)) {
			foreach ($deleted as $formId => $value) {
				$sql = "DELETE FROM `forms` WHERE id = :formId";
				$db->exec($sql, array(":formId" => $formId));
				unset($forms[$formId]);
				unset($morphs[$formId]);
			}
		}
		//insert a new form
		if (isset($forms[0]) && $forms[0] != "") {
			$sql = <<<SQL
				INSERT INTO `forms` (`form`, `morph`, `lexeme_id`) VALUES (:form, :morph, :lexemeId)
SQL;
			$db->exec($sql, array(":form" => $forms[0], ":morph" => $morphs[0], ":lexemeId" => $id));
			unset($forms[0]);
			unset($morphs[0]);
		}
		//update existing forms
		foreach ($forms as $formId => $value) {
			$sql = <<<SQL
				UPDATE forms SET `form` = :form, `morph` = :morph
					WHERE id = :id
SQL;
			$db->exec($sql, array(":form" => $value, ":morph" => $morphs[$formId], ":id" => $formId));
		}
  }

  public static function update_translations($id,$ens,$deleted) {
    $db = new database();
    //delete translations
    if (!empty($deleted)) {
      foreach ($deleted as $enId => $value) {
        $sql = "DELETE FROM `english` WHERE id = :enId";
        $db->exec($sql, array(":enId" => $enId));
        unset($ens[$enId]);
      }
    }
    //insert a new translation
    if (isset($ens[0]) && $ens[0] != "") {
      $sql = <<<SQL
        INSERT INTO `english` (`en`, `lexeme_id`) VALUES (:en, :lexemeId)
SQL;
      $db->exec($sql, array(":en" => $ens[0], ":lexemeId" => $id));
      unset($ens[0]);
    }
    //update existing translations
    foreach ($ens as $enid => $value) {
      $sql = <<<SQL
        UPDATE english SET `en` = :en
          WHERE id = :id
SQL;
      $db->exec($sql, array(":en" => $value, ":id" => $enid));
    }
  }

  public static function update_notes($id,$notes,$deleted) {
    $db = new database();
    //delete notes
    if (!empty($deleted)) {
      foreach ($deleted as $noteId => $value) {
        $sql = "DELETE FROM `notes` WHERE id = :noteId";
        $db->exec($sql, array(":noteId" => $noteId));
        unset($notes[$noteId]);
      }
    }
    //insert a new note
    if (isset($notes[0]) && $notes[0] != "") {
      $sql = <<<SQL
        INSERT INTO `notes` (`note`, `lexeme_id`) VALUES (:note, :lexemeId)
SQL;
      $db->exec($sql, array(":note" => $notes[0], ":lexemeId" => $id));
      unset($notes[0]);
    }
    //update existing notes
    foreach ($notes as $noteid => $value) {
      $sql = <<<SQL
        UPDATE notes SET `note` = :note
          WHERE id = :id
SQL;
      $db->exec($sql, array(":note" => $value, ":id" => $noteid));
    }
  }

}
