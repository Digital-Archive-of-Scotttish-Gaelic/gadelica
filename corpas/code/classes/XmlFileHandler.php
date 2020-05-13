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

  public function getContext($id, $scope = 12) {
    $context = array();
    $xpath = '/dasg:text/@ref';
    $out = $this->_xml->xpath($xpath);
    $context["uri"] = $out[0];
    $xpath = "//dasg:w[@id='{$id}']/preceding::*";
    $words = $this->_xml->xpath($xpath);
    $context["pre"] = implode(' ', array_slice($words,-$scope));
    $xpath = "//dasg:w[@id='{$id}']";
    $word = $this->_xml->xpath($xpath);
    $context["word"] = $word[0];
    $xpath = "//dasg:w[@id='{$id}']/following::*";
    $words = $this->_xml->xpath($xpath);
    $context["post"] = implode(' ', array_slice($words,0, $scope));
    return $context;
  }
}