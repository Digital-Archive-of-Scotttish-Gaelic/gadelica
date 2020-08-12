<?php


class SenseCategories
{
  public static function saveCategory($slipId, $catName)
  {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("INSERT INTO senseCategory VALUES(:slip_id, :cat_name)");
      $sth->execute(array(":slip_id" => $slipId, ":cat_name" => $catName));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function deleteCategory($slipId, $catName)
  {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("DELETE FROM senseCategory WHERE slip_id = :slip_id AND category = :cat_name");
      $sth->execute(array(":slip_id" => $slipId, ":cat_name" => $catName));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Fetches all the categories not used by the given slip
   * @param $slipId
   * @return array
   */
  public static function getAllUnusedCategories($slipId, $lemma, $wordclass) {
    $categories = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT DISTINCT category FROM senseCategory sc
        JOIN slips s ON s.auto_id = sc.slip_id
        JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
        WHERE lemma = :lemma AND wordclass = :wordclass
            AND slip_id != :slip_id
            ORDER BY category ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute(array(":slip_id" => $slipId, ":lemma"=>$lemma, ":wordclass"=>$wordclass));
      while ($row = $sth->fetch()) {
        $categories[] = $row["category"];
      }
      return $categories;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}