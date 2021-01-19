<?php

namespace models;

class slip
{
	const SCOPE_DEFAULT = 80;

  private $_auto_id, $_filename, $_id, $_pos, $_db;
  private $_starred, $_translation, $_notes, $_locked, $_ownedBy;
  private $_preContextScope, $_postContextScope, $_wordClass, $_lastUpdatedBy, $_lastUpdated;
  private $_isNew;
  private $_wordClasses = array(
    'noun' => array("n", "nx", "ns", "N", "Nx"),
    "verb" => array("v", "V", "vn"),
    "adjective" => array("a", "ar"),
    "preposition" => array("p", "P"),
    "adverb" => array("A"),
    "other" => array("d", "c", "z", "o", "D", "Dx", "ax", "px", "q"));
  private $_slipMorph;  //an instance of SlipMorphFeature
  private $_senseCategories = array();
  private $_lemma, $_wordform;

  public function __construct($filename, $id, $auto_id = null, $pos, $preScope = self::SCOPE_DEFAULT, $postScope = self::SCOPE_DEFAULT) {
    $this->_filename = $filename;
    $this->_id = $id;
    //test if a slip already exists (if there is a slip with the same groupId, filename, id combination)
    $this->_auto_id = $auto_id ? $auto_id : collection::slipExists($_SESSION["groupId"], $filename, $id);
    $this->_pos = $pos;
    if (!isset($this->_db)) {
      $this->_db = new database();
    }
    $this->_loadSlip($preScope, $postScope);
  }

  private function _loadSlip($preScope, $postScope) {
    $this->_slipMorph = new slipmorphfeature($this->_pos);
    if (!$this->getAutoId()) {  //create a new slip entry
      $this->_isNew = true;
      $this->_extractWordClass($this->_pos);
      $sql = <<<SQL
        INSERT INTO slips (filename, id, group_id, preContextScope, postContextScope, wordClass, ownedBy) VALUES (?, ?, ?, ?, ?, ?, ?);
SQL;
      $this->_db->exec($sql, array($this->_filename, $this->_id, $_SESSION["groupId"], $preScope, $postScope, $this->getWordClass(),
	      $_SESSION["user"]));
      $this->_auto_id = $this->_db->getLastInsertId();
      $this->_saveSlipMorph();    //save the defaults to the DB
    }
    $sql = <<<SQL
        SELECT * FROM slips 
        WHERE auto_id = :auto_id
SQL;
    $result = $this->_db->fetch($sql, array(":auto_id" => $this->_auto_id));
    $slipData = $result[0];
    $this->_populateClass($slipData);
    $this->_loadSlipMorph();  //load the slipMorph data from the DB
    $this->_loadSenseCategories(); //load the senseCategory data from the DB
    return $this;
  }

  private function _loadSenseCategories() {
    $sql = <<<SQL
        SELECT category from senseCategory 
        WHERE slip_id = :auto_id 
SQL;
    $results = $this->_db->fetch($sql, array(":auto_id"=>$this->getAutoId()));
    if ($results) {
      foreach ($results as $key => $value)
      $this->_senseCategories[] = $value["category"];
    }
    return $this;
  }

  private function _loadSlipMorph() {
    $this->_slipMorph->resetProps();
    $sql = <<<SQL
        SELECT * FROM slipMorph WHERE slip_id = ?
SQL;
    $results = $this->_db->fetch($sql, array($this->getAutoId()));
    foreach ($results as $result) {
      $this->_slipMorph->setProp($result["relation"], $result["value"]);
    }
  }
  
  private function _saveSlipMorph() {
    $props = $this->_slipMorph->getProps();
    foreach ($props as $relation => $value) {
      $sql = <<<SQL
        INSERT INTO slipMorph(slip_id, relation, value) VALUES(?, ?, ?)
SQL;
      $this->_db->exec($sql, array($this->getAutoId(), $relation, $value));
    }
  }

  private function _clearSlipMorphEntries() {
    $sql = <<<SQL
      DELETE FROM slipMorph WHERE slip_id = ?
SQL;
    $this->_db->exec($sql, array($this->_auto_id));
  }

  /**
   * Updates the results stored in the SESSION with the new auto_id
   * TODO: this no longer works within the new search engine - need to revisit SB
   */
  public function updateResults($index) {
    $_SESSION["results"][$index]["auto_id"] = $this->getAutoId();
  }

  private function _extractWordClass($pos) {
    foreach ($this->_wordClasses as $class => $posArray) {
      if (in_array($pos, $posArray)) {
        $this->_wordClass = $class;
      }
    }
  }

  public function getScopeDefault() {
  	return self::SCOPE_DEFAULT;
  }

  public function getSlipMorph() {
    return $this->_slipMorph;
  }

  public function getAutoId() {
    return $this->_auto_id;
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

  public function getWordClass() {
    return $this->_wordClass;
  }

  public function getSenseCategories() {
    return $this->_senseCategories;
  }

  public function getLocked() {
  	return $this->_locked;
  }

  public function getIsLocked() {
  	return $this->_locked == 1;
  }

  public function getOwnedBy() {
  	return $this->_ownedBy;
  }

  public function getLastUpdatedBy() {
    return $this->_lastUpdatedBy;
  }

  public function getLastUpdated() {
    return $this->_lastUpdated;
  }

  private function _populateClass($params) {
    $this->_auto_id = $this->getAutoId() ? $this->getAutoId() : $params["auto_id"];
    $this->_isNew = false;
    $this->_starred = $params["starred"] ? 1 : 0;
    $this->_translation = $params["translation"];
    $this->_notes = $params["notes"];
    $this->_preContextScope = $params["preContextScope"];
    $this->_postContextScope = $params["postContextScope"];
    $this->_wordClass = $params["wordClass"];
    $this->_locked = $params["locked"];
    $this->_ownedBy = $params["ownedBy"];
    $this->_lastUpdatedBy = $params["updatedBy"];
    $this->_lastUpdated = isset($params["lastUpdated"]) ? $params["lastUpdated"] : "";
    return $this;
  }

  public function saveSlip($params) {
    $params["updatedBy"] = $_SESSION["user"];
    $this->_populateClass($params);
    $this->_clearSlipMorphEntries();
    $this->_slipMorph->setType($this->getWordClass());
    $this->_slipMorph->populateClass($params);
    $this->_saveSlipMorph();
    $sql = <<<SQL
        UPDATE slips 
            SET group_id = {$_SESSION["groupId"]}, locked = ?, starred = ?, translation = ?, notes = ?, preContextScope = ?, postContextScope = ?,
                wordClass = ?, updatedBy = ?, lastUpdated = now()
            WHERE auto_id = ?
SQL;
    $this->_db->exec($sql, array($this->getLocked(), $this->getStarred(), $this->getTranslation(),
	    $this->getNotes(), $this->getPreContextScope(), $this->getPostContextScope(), $this->getWordClass(),
      $this->getLastUpdatedBy(),
      $this->getAutoId()));
    return $this;
  }

  /**
   * Returns the array of word classes
   * @return array
   */
  public function getWordClasses() {
    return $this->_wordClasses;
  }
}