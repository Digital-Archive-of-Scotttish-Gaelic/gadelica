<?php

namespace models;

class source {

  private $_id;
  private $_instances = array();
  private $_db;   // an instance of models\database

	public function __construct($id) {
    $this->_id = $id;
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `id`, `hw`, `pos`, `sub`
    		FROM `lexemes`
    		WHERE `source` = :source
        ORDER BY `hw`
SQL;
    $results = $this->_db->fetch($sql, array(":source" => $this->_id));
    foreach ($results as $nextResult) {
      $this->_instances[] = [$nextResult["id"], $nextResult["hw"], $nextResult["pos"]];
    }
	}

  public function getId() {
    return $this->_id;
	}

  public function getInstances() {
    return $this->_instances;
	}

}
