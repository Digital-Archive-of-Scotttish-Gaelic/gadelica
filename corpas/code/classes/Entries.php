<?php


class Entries
{
  public static function getAllEntries() {
    $entries = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT DISTINCT lemma, wordclass FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
            ORDER BY lemma ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute();
      while ($row = $sth->fetch()) {
        $entries[] = $row;
      }
      return $entries;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}