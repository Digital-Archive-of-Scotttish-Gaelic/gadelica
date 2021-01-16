<?php

namespace models;

class gaelic {

  private $_html;

  public function __construct($gd) {
    $file = 'gd/' . $gd[0] . '/' . $gd . '.xml';
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
