<?php


class Lemmatiser
{
  private $_inputXml;
  private $_dictEntries;

  public function __construct($xml) {
    $this->_inputXml = $xml;
    //open JSON lemmas file
    $json = file_get_contents("./json/lemmas.json");
    $this->_dictEntries = json_decode($json, TRUE);
  }

  public function getProcessedXml() {
    $outputXml = "";

    $text = new SimpleXMLElement($this->_inputXml);
    foreach ($text->p as $p) {
      if (isset($p->w)) {
        foreach ($p->w as $word) {
          $wordform = (string)$word;
  //return var_dump($this->_dictEntries);
          foreach ($this->_dictEntries as $entry => $e) {
            if ($wordform == $entry->wordform) {
              $outputXml .= "\n{$wordform} : " . $e["lemma"];
            }
          }

        }
      }
    }
    return $outputXml;
  }
}