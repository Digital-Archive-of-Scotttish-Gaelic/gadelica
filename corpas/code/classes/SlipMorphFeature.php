<?php

class SlipMorphFeature
{
  private $_abbr, $_type;
  private $_props = array();
  private $_propTitles = array("noun"=>array("numgen", "case"), "verb"=>array("status", "tense", "mood"));

  public function __construct($abbr) {
    $this->_abbr = $abbr;
    //set defaults based on abbreviation
    switch ($this->_abbr) {
      case "n":
        $this->_type = "noun";
        $this->_props["numgen"] = "singular (gender unclear)";
        $this->_props["case"] = "nominative";
        break;
      case "ns":
        $this->_type = "noun";
        $this->_props["numgen"] = "plural";
        $this->_props["case"] = "nominative";
        break;
      case "nx":
        $this->_type = "noun";
        $this->_props["numgen"] = "singular (gender unclear)";
        $this->_props["case"] = "genitive";
        break;
      case "v":
        $this->_type = "verb";
        $this->_props["status"] = "dependent";
        $this->_props["tense"] = "unclear";
        $this->_props["mood"] = "active";
        break;
      case "vn":
        $this->_type = "verb";
        $this->_props['status'] = "verbal noun";
        break;
      case "V":
        $this->_type = "verb";
        $this->_props["status"] = "independent";
        $this->_props["tense"] = "unclear";
        $this->_props["mood"] = "active";
        break;
    }
  }

  public function getType() {
    return $this->_type;
  }

  public function setType($type) {
    $this->_type = $type;
  }

  public function getProps() {
    return $this->_props;
  }

  public function setProp($relation, $value) {
    $this->_props[$relation] = $value;
  }

  public function resetProps() {
    $this->_props = [];
  }

  public function populateClass($params) {
    $this->resetProps();
    foreach ($this->_propTitles[$this->_type] as $relation) {
      if (!empty($params[$relation])) {
        $this->setProp($relation, $params[$relation]);
      }
    }
  }
}