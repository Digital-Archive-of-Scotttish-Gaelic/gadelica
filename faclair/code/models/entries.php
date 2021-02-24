<?php

namespace models;

class entries {

  private $_entries = array(); // an array of hw-pos-sub triples
  private $_db;   // an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
		$query = <<<SQL
			SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
				FROM `lexemes`
				ORDER BY `m-hw`
SQL;
		$results = $this->_db->fetch($query);
		foreach ($results as $nextResult) {
			$this->_entries[] = [$nextResult["m-hw"], $nextResult["m-pos"], $nextResult["m-sub"]];
		}
	}

  public function getEntries() {
    return $this->_entries;
	}

  public static function getShortGd($pos) {
    switch ($pos) {
      case "m":
        return 'fir.';
        break;
      case "f":
        return 'boir.';
        break;
      case "F":
        return 'boir.';
        break;
      case "n":
        return 'ainm.';
        break;
      case "v":
        return 'gn.';
        break;
      case "a":
        return 'bua.';
        break;
      case "x":
        return '';
        break;
      default:
        return $pos;
    }
  }

  public static function getLongGd($pos) {
    switch ($pos) {
      case "m":
        return 'ainmear fireann';
        break;
      case "f":
        return 'ainmear boireann';
        break;
      case "F":
        return 'ainm boireann';
        break;
      case "n":
        return 'ainmear (fireann/boireann)';
        break;
      case "v":
        return 'gnìomhair';
        break;
      case "a":
        return 'buadhair';
        break;
      case "x":
        return '';
        break;
      default:
        return $pos;
    }
  }

  public static function getLongEn($pos) {
    switch ($pos) {
      case "m":
        return 'masculine noun';
        break;
      case "f":
        return 'feminine noun';
        break;
      case "F":
        return 'feminine proper noun';
        break;
      case "n":
        return 'noun (masculine/feminine)';
        break;
      case "v":
        return 'verb';
        break;
      case "a":
        return 'adjective';
        break;
      case "x":
        return '';
        break;
      default:
        return $pos;
    }
  }

}
