<?php

namespace models;

class english {

  private $_en;
  private $_entries = array(); // an array of entry instances
  private $_db;   // an instance of models\database

	public function __construct($en) {
    $this->_en = $en;
    $this->_db = isset($this->_db) ? $this->_db : new database();
  	$this->_load();
	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `lexeme_id`
    		FROM `english`
    		WHERE `en` = :en
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_en));
    foreach ($results as $nextResult) {
      $this->_entries[] = new entry_instance($nextResult["lexeme_id"]);
    }
	}

  public function getEn() {
    return $this->_en;
	}

  public function getEntries() {
    return $this->_entries;
  }

}
