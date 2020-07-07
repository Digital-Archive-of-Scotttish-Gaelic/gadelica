<?php


class CorpusText
{
  private $_uri;
  private $_id, $_title, $_date, $_publisher, $_rating, $_superuri, $_supertitle, $_transformedText;
  private $_writers, $_media, $_genres, $_subURIs = [];  //arrays

  public function __construct($uri) {
    $this->_uri = $uri;
    $this->_load();
  }

  private function _load() {
    $uri = $this->_uri;
    $query = <<<SPQR
      SELECT DISTINCT ?title ?id ?suburi ?suburiTitle ?suburiRank ?xml ?medium ?genre ?writer ?surname ?forenames ?nick ?date ?publisher ?rating ?superuri ?supertitle
      WHERE
      {
      <{$uri}> dc:title ?title .
      <{$uri}> dc:identifier ?id .
      OPTIONAL { <{$uri}> :xml ?xml . }
      OPTIONAL {
        ?suburi dc:isPartOf <{$uri}> .
        ?suburi dc:title ?suburiTitle .
        ?suburi dc:identifier ?suburiRank .
      }
      OPTIONAL { <{$uri}> :medium ?medium . }
      OPTIONAL { <{$uri}> :genre ?genre . }
      OPTIONAL {
        <{$uri}> dc:creator ?writer .
        OPTIONAL {
          ?writer :surnameGD ?surname .
          ?writer :forenamesGD ?forenames .
          OPTIONAL {
            ?writer :nickname ?nick .
          }
        }
      }
      OPTIONAL { <{$uri}> dc:date ?date . }
      OPTIONAL { <{$uri}> dc:publisher ?publisher . }
      OPTIONAL { <{$uri}> :rating ?rating . }
      OPTIONAL {
        <{$uri}> dc:isPartOf ?superuri .
        ?superuri dc:title ?supertitle .
        }
      }
      ORDER BY ?suburiRank
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    $this->_id = $results[0]->id->value;
    $this->_title = $results[0]->title->value;
    $this->_setWriters($results);
    $this->_setMedia($results);
    $this->_setGenres($results);
    $this->_date = $results[0]->date->value;
    $this->_publisher = $results[0]->publisher->value;
    $this->_rating = $results[0]->rating->value;
    $this->_superuri = $results[0]->superuri->value;
    $this->_supertitle = $results[0]->supertitle->value;
    $this->_setSubURIs($results);
    $this->_transformedText = $this->_applyXSLT($results);
  }

  private function _setWriters($results) {
    $writers = [];
    foreach ($results as $nextResult) {
      $nextWriter = $nextResult->writer->value;
      if ($nextWriter!='') {
        $name = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
        if ($nextResult->nick->value!='') {
          $name .= ' (' . $nextResult->nick->value . ')';
        }
        $writers[$nextWriter] = $name;
      }
    }
    if (count($writers)==0) {
      $writers = $this->_getSuperWriters($this->_uri);
    }
    $this->_writers = $writers;
  }

  private function _setMedia($results) {
    $media = [];
    foreach ($results as $nextResult) {
      $nextMedium = $nextResult->medium->value;
      if ($nextMedium!='') {
        $media[] = $nextMedium;
      }
    }
    $media = array_unique($media);
    if (count($media)==0) {
      $media = $this->_getSuperMedia($this->_uri);
    }
    $this->_media = $media;
  }

  private function _setGenres($results) {
    $genres = [];
    foreach ($results as $nextResult) {
      $nextGenre = $nextResult->genre->value;
      if ($nextGenre!='') {
        $genres[] = $nextGenre;
      }
    }
    $genres = array_unique($genres);
    if (count($genres)==0) {
      $genres = $this->_getSuperGenres($this->_uri);
    }
    $this->_genres = $genres;
  }

  private function _setSubURIs($results) {
    $subURIs = [];
    foreach ($results as $nextResult) {
      $nextSubURI = $nextResult->suburi->value;
      if ($nextSubURI!='') {
        $subURIs[] = $nextSubURI;
      }
    }
    $subURIs = array_unique($subURIs);
    if (count($subURIs)>0) {
      $i = -1;
      foreach ($subURIs as $nextSubURI) {
        $i++;
        $this->_subURIs[$i]["title"] = 'Poo';
        $this->_subURIs[$i]["rank"] = 0;
        foreach ($results as $nextResult) {
          $nextSubURI2 = $nextResult->suburi->value;
          if ($nextSubURI2 == $nextSubURI) {
            $this->_subURIs[$i]["uri"] = $nextSubURI;
            $this->_subURIs[$i]["title"] = $nextResult->suburiTitle->value;
            $this->_subURIs[$i]["rank"] = $nextResult->suburiRank->value;
            break;
          }
        }
      }
    }
  }

  private function _applyXSLT($results) {
    // XML:
    $xml = $results[0]->xml->value;
    if ($xml != '') {
      $text = new SimpleXMLElement("../xml/" . $xml, 0, true);
      $xsl = new DOMDocument;
      $xsl->load('corpus.xsl');
      $proc = new XSLTProcessor;
      $proc->importStyleSheet($xsl);
      return $proc->transformToXML($text);
    }
  }

  private function _getSuperMedia($uri) {
    $query = <<<SPQR
      SELECT DISTINCT ?superuri ?medium
      WHERE
      {
        <{$uri}> dc:isPartOf ?superuri .
        OPTIONAL {
          ?superuri :medium ?medium .
        }
      }
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    if (count($results)==0) {
      return [];
    }
    $media = [];
    foreach ($results as $nextResult) {
      $media[] = $nextResult->medium->value;
    }
    if (count($media)==0) {
      return $this->_getSuperMedia($results[0]->superuri->value);
    }
    return array_unique($media);
  }

  private function _getSuperGenres($uri) {
    $query = <<<SPQR
      SELECT DISTINCT ?superuri ?genre
      WHERE
      {
        <{$uri}> dc:isPartOf ?superuri .
        OPTIONAL {
          ?superuri :genre ?genre .
        }
      }
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    if (count($results)==0) {
      return [];
    }
    $genres = [];
    foreach ($results as $nextResult) {
      $genres[] = $nextResult->genre->value;
    }
    if (count($genres)==0) {
      return $this->_getSuperGenres($results[0]->superuri->value);
    }
    return array_unique($genres);
  }

  private function _getSuperWriters($uri) {
    $query = <<<SPQR
      SELECT DISTINCT ?superuri ?writer ?surname ?forenames
      WHERE
      {
        <{$uri}> dc:isPartOf ?superuri .
        OPTIONAL {
          ?superuri dc:creator ?writer .
          OPTIONAL {
            ?writer :surnameGD ?surname .
            ?writer :forenamesGD ?forenames .
          }
        }
      }
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    if (count($results)==0) {
      return [];
    }
    $writers = [];
    foreach ($results as $nextResult) {
      $writers[$nextResult->writer->value] = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
    }
    if (count($writers)==0) {
      return $this->_getSuperWriters($results[0]->superuri->value);
    }
    return $writers;
  }

  public function getURI() {
    return $this->_uri;
  }

  public function getId() {
    return $this->_id;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getDate() {
    return $this->_date;
  }

  public function getPublisher() {
    return $this->_publisher;
  }

  public function getRating() {
    return $this->_rating;
  }

  public function getSuperURI() {
    return $this->_superuri;
  }

  public function getSuperTitle() {
    return $this->_supertitle;
  }

  public function getWriters() {
    return $this->_writers;
  }

  public function getMedia() {
    return $this->_media;
  }

  public function getGenres() {
    return $this->_genres;
  }

  public function getSubURIs() {
    return $this->_subURIs;
  }

  public function getTransformedText() {
    return $this->_transformedText;
  }
}