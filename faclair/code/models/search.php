<?php

namespace models;

class search {

  private $_search = ""; // the search term
  private $_entries = array(); // an array of hw-pos-sub triples
  private $_db;   // an instance of models\database

  public function __construct() {
    if (isset($_GET["search"])) {
      $this->_search = $_GET["search"];
      $this->_db = isset($this->_db) ? $this->_db : new database();
      $this->_load();
    }
  }

  private function _load() {
    $results = [];
    $results = $this->_englishExactSearch();
    $results2 = array_merge($results,$this->_gaelicExactHwSearch());
    $results3 = array_merge($results2,$this->_gaelicExactFormSearch());
    $results4 = array_merge($results3,$this->_englishPrefixSpaceSearch());
    $results5 = array_merge($results4,$this->_gaelicPrefixHwSpaceSearch());
    $results6 = array_merge($results5,$this->_englishPrefixNoSpaceSearch());
    $results7 = array_merge($results6,$this->_gaelicPrefixHwNoSpaceSearch());
    $results8 = array_merge($results7,$this->_englishSuffixSpaceSearch());
    $results9 = array_merge($results8,$this->_gaelicSuffixHwSpaceSearch());
    $results10 = array_merge($results9,$this->_englishSuffixNoSpaceSearch());
    $results11 = array_merge($results10,$this->_gaelicSuffixHwNoSpaceSearch());
    $results12 = array_merge($results11,$this->_englishInfixSpaceBothSearch());
    $results13 = array_merge($results12,$this->_gaelicInfixHwSpaceBothSearch());
    $results14 = array_merge($results13,$this->_englishInfixSpaceLeftSearch());
    $results15 = array_merge($results14,$this->_gaelicInfixHwSpaceLeftSearch());
    $results16 = array_merge($results15,$this->_englishInfixSpaceRightSearch());
    $results17 = array_merge($results16,$this->_gaelicInfixHwSpaceRightSearch());
    // GD forms as infixes etc??
    // GD lenition on suffixes and infixes??
    $results = $results17;
		foreach ($results as $nextResult) {
			$this->_entries[] = explode('|',$nextResult);
		}
	}

  private function _englishExactSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` = :en
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicExactHwSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicExactFormSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, f.`form`, f.`morph`
      FROM `lexemes` l
      JOIN `forms` f ON l.`id` = f.`lexeme_id`
      WHERE f.`form` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["form"] . ' <em>' . $nextResult["morph"] . '</em>';
    }
    return $oot;
  }

  private function _englishPrefixSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '^' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicPrefixHwSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '^' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishPrefixNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '^' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicPrefixHwNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '^' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishSuffixSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicSuffixHwSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '.*[ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishSuffixNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[^ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicSuffixHwNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '.*[^ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishInfixSpaceBothSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceBothSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '.*[ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishInfixSpaceLeftSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceLeftSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '.*[ -]' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _englishInfixSpaceRightSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[^ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceRightSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` REGEXP :gd
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => '.*[^ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
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
