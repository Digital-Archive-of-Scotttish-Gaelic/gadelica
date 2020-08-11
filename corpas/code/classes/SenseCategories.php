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

  public static function getAllCategories() {
    $categories = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT DISTINCT category FROM senseCategory ORDER BY category ASC");
      $sth->execute();
      while ($row = $sth->fetch()) {
        $categories[] = $row["category"];
      }
      return $categories;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}