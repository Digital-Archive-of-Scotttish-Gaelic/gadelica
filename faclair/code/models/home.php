<?php

namespace models;

class home {

  private $_search = "";
  private $_entries = array();
  private $_db;   // an instance of models\database

	public function __construct() {
    if (isset($_GET["search"])) {
      $this->_search = $_GET["search"];
      $this->_db = isset($this->_db) ? $this->_db : new database();
    	$this->_load();
    }
	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `lexeme_id`
    		FROM `english`
    		WHERE `en` = :en
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_search));
    foreach ($results as $nextResult) {
      $this->_entries[] = new entry_instance($nextResult["lexeme_id"]);
    }
	}

  public function getSearch() {
    return $this->_search;
	}

  public function getEntries() {
    return $this->_entries;
	}

}
