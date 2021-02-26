<?php

namespace models;

class entries {

  private $_search = ""; // the search term
  private $_entries = array(); // an array of hw-pos-sub triples
  private $_db;   // an instance of models\database

  public function __construct() {
    if (isset($_GET["search"])) {
      $this->_search = $_GET["search"];
    }
    $this->_db = isset($this->_db) ? $this->_db : new database();
    $this->_load();
  }

  private function _load() {
    $results = [];
    if ($this->_search) { // there is a search term
      $results = $this->_englishExactSearch();
      $results2 = array_unique(array_merge($results,$this->_gaelicExactHwSearch()));
      $results3 = array_unique(array_merge($results2,$this->_gaelicExactFormSearch()));
      $results4 = array_unique(array_merge($results3,$this->_englishPrefixSearch()));
      $results = $results4;
    }
    else if (isset($_GET["m"])) {
      $results = $this->_allEntriesSearch();
    }
		foreach ($results as $nextResult) {
			$this->_entries[] = explode('|',$nextResult);
		}
	}

  private function _englishExactSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` = :en
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"];
    }
    return $oot;
  }

  private function _gaelicExactHwSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
      FROM `lexemes`
      WHERE `hw` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"];
    }
    return $oot;
  }

  private function _gaelicExactFormSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
      FROM `lexemes` l
      JOIN `forms` f ON l.`id` = f.`lexeme_id`
      WHERE f.`form` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"];
    }
    return $oot;
  }

  private function _englishPrefixSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` LIKE :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_search . '%'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"];
    }
    return $oot;
  }

  private function _allEntriesSearch() {
    $query = <<<SQL
      SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`
        FROM `lexemes`
        ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($query);
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"];
    }
    return $oot;
  }

  public function getSearch() {
    return $this->_search;
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
