<?php

namespace models;

class sources {

  private $_sources = array();
  private $_db;   // an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
    $query = <<<SQL
    	SELECT DISTINCT `source`
    		FROM `lexemes`
    		ORDER BY `source`
SQL;
    $results = $this->_db->fetch($query);
		foreach ($results as $nextResult) {
			$this->_sources[] = $nextResult["source"];
		}
	}

  public function getSources() {
    return $this->_sources;
	}

}
