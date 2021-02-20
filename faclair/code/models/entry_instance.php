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

}
