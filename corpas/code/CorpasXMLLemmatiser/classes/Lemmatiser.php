<?php


class Lemmatiser
{
  private $_inputXml;
  private $_dbh;

  public function __construct($xml) {
    $this->_inputXml = $xml;
    //connect to the database
    $this->_dbh = Database::getDatabaseHandle(DB_NAME);
  }

  public function getProcessedXml() {
    $text = new SimpleXMLElement($this->_inputXml);
    if (isset($text->text)) {             //deal with texts within texts
      $xml = "";
      foreach ($text->text as $subtext) {
        $xml .= "\n" . $this->_processTextXml($subtext);
      }
      return $xml;
    } else {
      return $this->_processTextXml($text);    //single text
    }
  }

  private function _processTextXml($text) {
    foreach ($text->p as $p) {
      if (isset($p->w)) {
        foreach ($p->w as $word) {
          $wordform = (string)$word;
          $lemmas = $this->_getLemmas($wordform);
          $lemmasGlued = implode(' ', $lemmas);
          $word["lemma"] = $lemmasGlued;
        }
      }
    }
    return $text->asXML();
  }

  private function _getLemmas($wordform) {
    $lemmas = array();
    //TODO: ask MM if we want to remove duplicates using DISTINCT
    $query = <<<SQL
        SELECT DISTINCT lemma FROM lemmas WHERE wordform = :wordform
SQL;
    $sth = $this->_dbh->prepare($query);
    $sth->execute(array(":wordform" => $wordform));
    $results = $sth->fetchAll();
    foreach ($results as $result) {
      $lemmas[] = $result["lemma"];
    }
    return $lemmas;
  }
}