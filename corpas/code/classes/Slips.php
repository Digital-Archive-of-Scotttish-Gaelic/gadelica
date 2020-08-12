<?php


class Slips
{
  /**
   * SB: This would be the preferred option as it returns objects but has performance issues
   * Leaving it here in case it becomes useful
   *
   * @return array An array of Slip objects
   */
  public static function getAllSlips() {
    $slips = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, wordform FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            ORDER BY auto_id ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute();
      while ($row = $sth->fetch()) {
        $slips[$row["auto_id"]] = new Slip($row["filename"], $row["id"], $row["auto_id"], $row["pos"]);
        $slips[$row["auto_id"]]->setLemma($row["lemma"]);
        $slips[$row["auto_id"]]->setWordform($row["wordform"]);
      }
      return $slips;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Get the slip info required for a browse table from the DB
   *
   * @return array of DB results
   */
  public static function getAllSlipInfo() {
    $slipInfo = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, wordform, firstname, lastname,
                s.lastUpdated as lastUpdated, category as senseCat
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            LEFT JOIN senseCategory sc ON sc.slip_id = auto_id
            LEFT JOIN user u ON u.email = s.updatedBy
            ORDER BY auto_id ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute();
      while ($row = $sth->fetch()) {
        $slipId = $row["auto_id"];
        if (!isset($slipInfo[$slipId])) {
          $slipInfo[$slipId] = $row;
          $slipInfo[$slipId]["category"] = array();
          $slipInfo[$slipId]["category"][] = $row["senseCat"];
        } else {
          $slipInfo[$slipId]["category"][] = $row["senseCat"];
        }
      }
      return $slipInfo;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}