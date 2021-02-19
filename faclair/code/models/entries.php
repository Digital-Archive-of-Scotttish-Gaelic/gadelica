<?php

namespace models;

class entries {

  private $_entries = array();
  private $_db;   // an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
		$query = <<<SQL
			SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
				FROM `lexemes`
				ORDER BY `m-hw`
SQL;
		$results = $this->_db->fetch($query);
		foreach ($results as $nextResult) {
			$this->_entries[] = [$nextResult["m-hw"], $nextResult["m-pos"], $nextResult["m-sub"]];
		}
	}

  public function getEntries() {
    return $this->_entries;
	}

}
