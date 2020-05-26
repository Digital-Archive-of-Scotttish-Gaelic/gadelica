<?php


class Slip
{
  private $_filename, $_id, $_db;
  private $_starred, $_translation, $_notes;
  private $_preContextScope, $_postContextScope, $_lastUpdated;
  private $_isNew;

  public function __construct($filename, $id, $preScope = 20, $postScope = 20) {
    $this->_filename = $filename;
    $this->_id = $id;
    if (!isset($this->_db)) {
      $this->_db = new Database();
    }
    $this->_loadSlip($preScope, $postScope);
  }

  private function _loadSlip($preScope, $postScope) {
    $sql = <<<SQL
        SELECT * FROM slips 
        WHERE filename = ? AND id = ?
SQL;
    $result = $this->_db->fetch($sql, array($this->_filename, $this->_id));
    if (count($result)) {
      $slipData = $result[0];
      $this->_populateClass($slipData);
      return $this;
    } else {
      $this->_isNew = true;
      $sql = <<<SQL
        INSERT INTO slips (filename, id, preContextScope, postContextScope) VALUES (?, ?, ?, ?);
SQL;
      $this->_db->exec($sql, array($this->_filename, $this->_id, $preScope, $postScope));
    }
    return $this;
  }

  public function getFilename() {
    return $this->_filename;
  }

  public function getId() {
    return $this->_id;
  }

  public function getIsNew() {
    return $this->_isNew;
  }

  public function getStarred() {
    return $this->_starred;
  }

  public function getTranslation() {
    return $this->_translation;
  }

  public function getNotes() {
    return $this->_notes;
  }

  public function getPreContextScope() {
    return $this->_preContextScope;
  }

  public function getPostContextScope() {
    return $this->_postContextScope;
  }

  public function getLastUpdated() {
    return $this->_lastUpdated;
  }

  private function _populateClass($params) {
    $this->_isNew = false;
    $this->_starred = $params["starred"];
    $this->_translation = $params["translation"];
    $this->_notes = $params["notes"];
    $this->_preContextScope = $params["preContextScope"];
    $this->_postContextScope = $params["postContextScope"];
    $this->_lastUpdated = $params["lastUpdated"];

    return $this;
  }

  public function saveSlip($params) {
    $sql = <<<SQL
        UPDATE slips 
            SET starred = ?, translation = ?, notes = ?, preContextScope = ?, postContextScope = ?
            WHERE filename = ? AND id = ?
SQL;
    $this->_db->exec($sql, array($params["starred"], $params["translation"], $params["notes"],
      $params["preContextScope"], $params["postContextScope"], $params["filename"], $params["id"]));
    return $this;
  }
}