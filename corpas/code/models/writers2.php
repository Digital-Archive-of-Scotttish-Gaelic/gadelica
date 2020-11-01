<?php

namespace models;

class writers2
{

	private $_members;  //an array of models\writer2 objects

  private $_db; //an instance of models\database

	public function __construct($id) {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

	/**
	 * Populates the object with info from the DB
	 */

  private function _load() {
		$writers = array();
		$sql = <<<SQL
			SELECT id FROM writer ORDER by id ASC
SQL;
		$results = $this->_db->fetch($sql);
		foreach ($results as $result) {
			$writers[] = new writer2($result["id"]);
		}
		$this->_members = $writers;
	}

	// SETTERS



	// GETTERS

	public function getAllWriters() {
		return $this->_members;
	}

}
