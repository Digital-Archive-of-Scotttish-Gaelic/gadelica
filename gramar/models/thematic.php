<?php

namespace models;

class thematic {

  private $_html;

  public function __construct($xx) {
    $file = 'xx/' . $xx . '.xml';
    $xml = new \SimpleXMLElement($file,0,true);
    $xsl = new \DOMDocument;
    $xsl->load('module.xsl');
    $proc = new \XSLTProcessor;
    $proc->importStyleSheet($xsl);
    $this->_html = $proc->transformToXML($xml);
  }

  public function getHtml() {
		return $this->_html;
	}

}
