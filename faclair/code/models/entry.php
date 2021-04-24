<?php

namespace models;

class entry {

  private $_mhw;
  private $_mpos;
  private $_msub;
  private $_instances = array(); // an array of entry_instance models
  private $_parts = array();
  private $_compounds = array();
  private $_db;   // an instance of models\database

	public function __construct($mhw,$mpos,$msub,$db) {
    if ($mhw!=null) {
      $this->_mhw = $mhw;
      $this->_mpos = $mpos;
      $this->_msub = $msub;
      if ($db) { // check the database?
        $this->_db = isset($this->_db) ? $this->_db : new database();
    		$this->_load();
      }
    } else {
      $this->_db = isset($this->_db) ? $this->_db : new database();
      $sql = <<<SQL
      	SELECT `m-hw`, `m-pos`, `m-sub`
      		FROM `lexemes`
          ORDER BY RAND()
          LIMIT 1
SQL;
      $results = $this->_db->fetch($sql);
      $this->_mhw = $results[0]["m-hw"];
      $this->_mpos = $results[0]["m-pos"];
      $this->_msub = $results[0]["m-sub"];
      $this->_load();
    }

	}

  private function _load() {
    $sql = <<<SQL
    	SELECT `id`
    		FROM `lexemes`
    		WHERE `m-hw` = :mhw
    		AND `m-pos` = :mpos
    		AND `m-sub` = :msub
        ORDER BY LENGTH(`hw`), `hw`, `source`
SQL;
    $results = $this->_db->fetch($sql, array(":mhw" => $this->_mhw, ":mpos" => $this->_mpos, ":msub" => $this->_msub));
    foreach ($results as $nextResult) {
      $this->_instances[] = new entry_instance($nextResult["id"]);
    }
    $sql = <<<SQL
    	SELECT `m-p-hw`, `m-p-pos`, `m-p-sub`, `id`
    		FROM `parts`
    		WHERE `m-hw` = :mhw
    		AND `m-pos` =  :mpos
    		AND `m-sub` =  :msub
SQL;
    $results = $this->_db->fetch($sql, array(":mhw" => $this->_mhw, ":mpos" => $this->_mpos, ":msub" => $this->_msub));
    foreach ($results as $nextResult) {
      $this->_parts[] = [$nextResult["m-p-hw"], $nextResult["m-p-pos"], $nextResult["m-p-sub"], $nextResult["id"]];
    }
    $sql = <<<SQL
    	SELECT `m-hw`, `m-pos`, `m-sub`
    		FROM `parts`
    		WHERE `m-p-hw` = :mhw
    		AND `m-p-pos` = :mpos
    		AND `m-p-sub` = :msub
        ORDER BY LENGTH(`m-hw`)
SQL;
    $results = $this->_db->fetch($sql, array(":mhw" => $this->_mhw, ":mpos" => $this->_mpos, ":msub" => $this->_msub));
    foreach ($results as $nextResult) {
    	$this->_compounds[] = [$nextResult["m-hw"], $nextResult["m-pos"], $nextResult["m-sub"]];
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

  public function getParts() {
    return $this->_parts;
  }

  public function getCompounds() {
    return $this->_compounds;
  }

  public static function getPosInfo($pos) {
    switch ($pos) {
      case "m":
        return ['fir.', 'ainmear fireann', 'masculine noun'];
        break;
      case "f":
        return ['boir.', 'ainmear boireann', 'feminine noun'];
        break;
      case "ff":
        return ['boir.', 'ainm boireann', 'feminine proper noun'];
        break;
      case "n":
        return ['ainm.', 'ainmear (fireann/boireann)', 'noun (masculine/feminine)'];
        break;
      case "v":
        return ['gn.', 'gnìomhair', 'verb'];
        break;
      case "a":
        return ['bua.', 'buadhair', 'adjective'];
        break;
      case "x":
        return ['', '', ''];
        break;
      default:
        return [$pos, $pos, $pos];
    }
  }

}
