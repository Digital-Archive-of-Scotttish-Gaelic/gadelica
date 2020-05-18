<?php


class XmlFileHandler
{
  private $_filename, $_xml;

  public function __construct($filename) {
    $this->_filename = $filename;
    $this->_xml = simplexml_load_file(INPUT_FILEPATH . $this->_filename);
    $this->_xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
  }

  public function getFilename() {
    return $this->_filename;
  }

  public function getContext($id, $preScope = 12, $postScope = 12) {
    $context = array();
    $context["id"] = $id;
    $context["filename"] = $this->getFilename();
    $xpath = '/dasg:text/@ref';
    $out = $this->_xml->xpath($xpath);
    $context["uri"] = (string)$out[0];
    $xpath = "//dasg:w[@id='{$id}']/preceding::*";
    $words = $this->_xml->xpath($xpath);
    $context["pre"] = ($preScope == 0) ? "" : implode(' ', array_slice($words,-$preScope));
    $xpath = "//dasg:w[@id='{$id}']";
    $word = $this->_xml->xpath($xpath);
    $context["word"] = (string)$word[0];
    $xpath = "//dasg:w[@id='{$id}']/following::*";
    $words = $this->_xml->xpath($xpath);
    $context["post"] = ($postScope == 0) ? "": implode(' ', array_slice($words,0, $postScope));
    return $context;
  }
}