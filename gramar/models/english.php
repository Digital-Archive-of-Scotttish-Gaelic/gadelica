<?php

namespace models;

class english {

  private $_html;

  public function __construct($en) {
    $file = 'en/' . $en[0] . '/' . $en . '.xml';
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
