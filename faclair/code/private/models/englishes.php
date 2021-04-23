<?php

namespace models;

class englishes {

  private $_terms = array(); // an array of ...
  private $_db;   // an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
		$query = <<<SQL
			SELECT DISTINCT `en`
				FROM `english`
				ORDER BY `en`
SQL;
		$results = $this->_db->fetch($query);
		foreach ($results as $nextResult) {
			$this->_terms[] = $nextResult["en"];
		}
	}

  public function getTerms() {
    return $this->_terms;
	}

}
