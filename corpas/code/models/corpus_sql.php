<?php


namespace models;


class corpus_sql
{
	private $_db; //an instance of models\database
	private $_texts = array();  //an array of models\text objects

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
	}

	public function getTextList() {
		$sql = <<<SQL
			SELECT id FROM text WHERE partOf = '' ORDER BY CAST(id AS UNSIGNED) ASC
SQL;
		$results = $this->_db->fetch($sql);
		foreach ($results as $result) {
			$this->_texts[] = new text_sql($result["id"]);
		}
		return $this->_texts;
	}
}