<?php

namespace models;

class district2
{
	private $_id;
	private $_name, $_notes;

	private $_db;   //an instance of models\database

	public function __construct($id) {
		$this->_id = $id;
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

	private function _load() {
		$sql = <<<SQL
			SELECT name, notes FROM districts WHERE id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id"=>$this->_id));
		$districtData = $results[0];
		$this->_setName($districtData["name"]);
		$this->_setNotes($districtData["notes"]);
	}

	//Setters

	private function _setName($name) {
		$this->_name = $name;
	}

	private function _setNotes($notes) {
		$this->_notes = $notes;
	}

	//Getters

	public function getName() {
		return $this->_name;
	}

	public function getNotes() {
		return $this->_notes;
	}

	/**
	 * Queries the database for district info
	 * @return array of results
	 */
	public static function getDistrictInfo() {
		$db = new database();
		$sql = <<<SQL
			SELECT id, name, notes FROM districts ORDER BY id ASC
SQL;
		$results = $db->fetch($sql);
		return $results;
	}



}
