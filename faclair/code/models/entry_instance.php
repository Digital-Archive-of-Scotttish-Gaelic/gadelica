<?php

namespace models;

class entry_instance {

  private $_id;
  private $_source;
  private $_hw;
  private $_pos;
  private $_sub;
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
    	SELECT `source`, `hw`, `pos`, `sub`
    		FROM `lexemes`
    		WHERE `id` = :id
SQL;
    $results = $this->_db->fetch($sql, array(":id" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_source = $nextResult["source"];
      $this->_hw = $nextResult["hw"];
      $this->_pos = $nextResult["pos"];
      $this->_sub = $nextResult["sub"];
    }
    $sql = <<<SQL
      SELECT `form`, `morph`, `id`
        FROM `forms`
        WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_forms[] = [$nextResult["form"], $nextResult["morph"]];
    }
    $sql = <<<SQL
  		SELECT `en`, `id`
  			FROM `english`
  			WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_translations[] = $nextResult["en"];
    }
    $sql = <<<SQL
      SELECT `note`, `id`
        FROM `notes`
        WHERE `lexeme_id` = :lexemeId
SQL;
    $results = $this->_db->fetch($sql, array(":lexemeId" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_notes[] = $nextResult["note"];
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

  public function getPos() {
    return $this->_pos;
	}

  public function getSub() {
    return $this->_sub;
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
