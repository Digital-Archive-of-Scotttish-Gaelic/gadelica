<?php


class FileHandler
{
  private $_filename;

  public function __construct($filename)
  {
    $this->_filename = $filename;
  }

  public function getXml() {
    $xml = file_get_contents(INPUT_FILEPATH . $this->_filename);
    return $xml;
  }

  public function saveXml($xml) {
    file_put_contents(OUTPUT_FILEPATH . $this->_filename, $xml);
    echo "\n" . $this->_filename;
  }

}