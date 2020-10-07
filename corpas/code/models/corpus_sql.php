<?php


namespace models;


class corpus_sql
{
	private $_db; //an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
	}

	/**
	 * Queries the DB for a list of text info
	 * @return array of text and writer information
	 */
	public function getTextList() {
		$sql = <<<SQL
			SELECT * FROM text WHERE partOf = '' ORDER BY CAST(id AS UNSIGNED) ASC
SQL;
		foreach ($this->_db->fetch($sql) as $textResult) {
			$textsInfo[$textResult["id"]] = $textResult;
			$sql = <<<SQL
				SELECT * FROM writer 
					JOIN text_writer ON writer_id = id
					WHERE text_id = :textId
SQL;
			$writerResults = $this->_db->fetch($sql, array(":textId" => $textResult["id"]));
			$textsInfo[$textResult["id"]]["writers"] = $writerResults;
		}
		return $textsInfo;
	}
}