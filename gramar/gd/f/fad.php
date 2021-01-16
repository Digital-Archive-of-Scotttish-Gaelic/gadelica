<?php
require_once "../../includes/htmlHeader.php";

$file = "fad.xml";
$xml = new SimpleXMLElement($file,0,true);
$xsl = new DOMDocument;
$xsl->load('module.xsl');
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($xml);

require_once "../../includes/htmlFooter.php";
?>

