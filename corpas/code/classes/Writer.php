<?php


class Writer
{
  private $_uri, $_forenames, $_surname, $_nickname, $_yearOfBirth, $_yearOfDeath, $_origin;
  private $_parent = [], $_children = [],  $_works = [];

  public function __construct($uri) {
    $this->_uri = $uri;
    $this->_load();
  }

  /**
   * Loads the info from Fuseki and sets the class properties accordingly
   */
  private function _load() {
    $uri = $this->_uri;
    $query = <<<SPQR
      SELECT DISTINCT ?surname ?forenames ?yob ?yod ?origin ?work ?title ?nickname ?parent ?psur ?pfore ?pnick ?sprog ?ssur ?sfore ?snick
      WHERE
      {
        <{$uri}> :surnameGD ?surname .
        <{$uri}> :forenamesGD ?forenames .
        OPTIONAL { <{$uri}> :nickname ?nickname . }
        OPTIONAL {
          <{$uri}> :parent ?parent .
          ?parent :surnameGD ?psur .
          ?parent :forenamesGD ?pfore .
          OPTIONAL { ?parent :nickname ?pnick . }
        }
        OPTIONAL { <{$uri}> :yob ?yob . }
        OPTIONAL { <{$uri}> :yod ?yod . }
        OPTIONAL { <{$uri}> :where ?origin . }
        OPTIONAL {
          ?work dc:creator <{$uri}> .
          ?work dc:title ?title .
        }
        OPTIONAL {
          ?sprog :parent <{$uri}> .
          ?sprog :surnameGD ?ssur .
          ?sprog :forenamesGD ?sfore .
          OPTIONAL { ?sprog :nickname ?snick . }
        }
      }
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    $this->_forenames = $results[0]->forenames->value;
    $this->_surname = $results[0]->surname->value;
    $this->_nickname = $results[0]->nickname->value;
    $this->_yearOfBirth = $results[0]->yob->value;
    $this->_yearOfDeath = $results[0]->yod->value;
    $this->_origin = $results[0]->origin->value;
    $this->_setParent($results);
    $this->_setChildren($results);
    $this->_setWorks($results);
  }

  private function _setParent($results) {
    if (!$results[0]->parent->value) {
      return;
    }
    $this->_parent["uri"] = $results[0]->parent->value;
    $this->_parent["forenames"] = $results[0]->pfore->value;
    $this->_parent["surname"] = $results[0]->psur->value;
    $this->_parent["nickname"] = $results[0]->pnick->value;
  }

  private function _setChildren($results) {
    foreach ($results as $nextResult) {
      if ($uri = $nextResult->sprog->value) {
        $this->_children[$uri]["forenames"] = $nextResult->sfore->value;
        $this->_children[$uri]["surname"] = $nextResult->ssur->value;
        if ($nextResult->snick->value!='') {
          $this->_children[$uri]["nickname"] = $nextResult->snick->value;
        }
      }
    }
  }

  private function _setWorks($results) {
    foreach ($results as $nextResult) {
      if ($nextResult->work->value!='') {
        $this->_works[$nextResult->work->value] = $nextResult->title->value;
      }
    }
  }

  //Getters

  public function getURI() {
    return $this->_uri;
  }

  public function getForenames() {
    return $this->_forenames;
  }

  public function getSurname() {
    return $this->_surname;
  }

  public function getNickname() {
    return $this->_nickname;
  }

  public function getYearOfBirth() {
    return $this->_yearOfBirth;
  }

  public function getYearOfDeath() {
    return $this->_yearOfDeath;
  }

  public function getOrigin() {
    return $this->_origin;
  }

  /**
   * @return array
   */
  public function getParent() {
    return $this->_parent;
  }

  /**
   * @return array indexed by URI
   */
  public function getChildren() {
    return $this->_children;
  }

  /**
   * @return array indexed by URI
   */
  public function getWorks() {
    return $this->_works;
  }
}