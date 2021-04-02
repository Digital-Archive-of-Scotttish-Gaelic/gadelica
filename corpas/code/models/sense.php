<?php


namespace models;


class sense
{
	private $_id, $_name, $_description, $_headword, $_wordclass;
	private $_groupId;  //the ID associated with the workspace
	private $_db;  //database connection

	public function __construct($id) {
		$this->_id = $id;
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

	private function _load() {
		$sql = <<<SQL
			SELECT name, description, headword, wordclass FROM sense WHERE id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		$this->_init($results[0]);
	}

	private function _init($params) {
		$this->_setName($params["name"]);
		$this->_setDescription($params["description"]);
		$this->_setHeadword($params["headword"]);
		$this->_setWordclass($params["wordclass"]);
	}

	//SETTERS

	private function _setName($name) {
		$this->_name = $name;
	}

	private function _setDescription($description) {
		$this->_description = $description;
	}

	private function _setHeadword($headword) {
		$this->_headword = $headword;
	}

	private function _setWordclass($wordclass) {
		$this->_wordclass = $wordclass;
	}

	/**
	 * Checks which group ("workspace") is associated with this sense and sets the class property
	 */
	private function _setGroupId() {
		$sql = <<<SQL
			SELECT DISTINCT group_id FROM slips s JOIN slip_sense ss ON s.auto_id = ss.slip_id WHERE ss.sense_id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		$this->_groupId = $results[0]["group_id"];
	}

	//GETTERS

	public function getId() {
		return $this->_id;
	}

	public function getName() {
		return $this->_name;
	}

	public function getDescription() {
		return $this->_description;
	}

	public function getHeadword() {
		return $this->_headword;
	}

	public function getWordclass() {
		return $this->_wordclass;
	}

	public function getGroupId() {
		return $this->_groupId;
	}
}