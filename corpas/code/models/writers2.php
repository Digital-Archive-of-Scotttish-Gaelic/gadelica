<?php

namespace models;

class writers2
{

	private $_members;  //an array of models\writer2 objects

	private $_db; //an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

	/**
	 * Populates the object with info from the DB
	 */

	private function _load() {
		$writers = array();
		$sql = <<<SQL
			SELECT id, surname_en FROM writer ORDER by surname_en ASC
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

	/**
	 * Updates an existing writer record in the database or adds a new one if required
	 * @param $data the form data for the writer record
	 */
	public static function save($data) {
		$db = new database();
		//set null values if empty
		$data["district_1_id"] = $data["district_1_id"] == "" ? null : $data["district_1_id"];
		$data["district_2_id"] = $data["district_2_id"] == "" ? null : $data["district_2_id"];
		$sql = <<<SQL
			REPLACE INTO writer (id, surname_gd, forenames_gd, surname_en, forenames_en, preferred_name, title,
					nickname, yob, yod, district_1_id, district_2_id, notes)
				VALUES(:id, :surname_gd, :forenames_gd, :surname_en, :forenames_en, :preferred_name, :title,
					:nickname, :yob, :yod, :district_1_id, :district_2_id, :notes)
SQL;
		$db->exec($sql, array(":id"=>$data["id"], ":surname_gd"=>$data["surname_gd"], ":forenames_gd"=>$data["forenames_gd"],
			":surname_en"=>$data["surname_en"], ":forenames_en"=>$data["forenames_en"], ":preferred_name"=>$data["preferred_name"],
			":title"=>$data["title"], ":nickname"=>$data["nickname"], ":yob"=>$data["yob"], ":yod"=>$data["yod"],
			":district_1_id"=>$data["district_1_id"], ":district_2_id"=>$data["district_2_id"], ":notes"=>$data["notes"]));
	}

}
