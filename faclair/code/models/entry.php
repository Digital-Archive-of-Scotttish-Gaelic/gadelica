<?php

namespace models;

class entry {

  private $_mhw;
  private $_mpos;
  private $_msub;

  private $_db;   // an instance of models\database

	public function __construct($mhw,$mpos,$msub) {
    $this->_mhw = $mhw;
    $this->_mpos = $mpos;
    $this->_msub = $msub;
    $this->_instances = array();
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `id`, `source`, `hw`, `pos`, `sub`
    		FROM `lexemes`
    		WHERE `m-hw` = :mhw
    		AND `m-pos` = :mpos
    		AND `m-sub` = :msub
SQL;
    $results = $this->_db->fetch($sql, array(":mhw" => $this->_mhw, ":mpos" => $this->_mpos, ":msub" => $this->_msub));
    foreach ($results as $nextResult) {
      $this->_instances[] = [$nextResult["source"],$nextResult["hw"],$nextResult["pos"],$nextResult["sub"],$nextResult["id"]];
    }
	}

  public function getMhw() {
    return $this->_mhw;
	}

  public function getMpos() {
    return $this->_mpos;
  }

  public function getMsub() {
    return $this->_msub;
  }

  public function getInstances() {
    return $this->_instances;
  }


}
