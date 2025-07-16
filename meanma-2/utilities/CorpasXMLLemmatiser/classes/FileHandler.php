<?php

/**
 * Class FileHandler
 *
 * !! This has been deprecated - remains just in case it's needed for future reference !!
 */
class FileHandler
{
  private $_filename;

  public function __construct($filename) {
    $this->_filename = $filename;
    echo "\n\n" . $this->_filename;
  }

  //Import XML
  public function getXml() {
    $xml = file_get_contents(TXT_FILEPATH . $this->_filename);
    return $xml;
  }

  //Export XML
  public function saveXml($xml) {
    file_put_contents(XML_FILEPATH . $this->_filename, $xml);
  }

}