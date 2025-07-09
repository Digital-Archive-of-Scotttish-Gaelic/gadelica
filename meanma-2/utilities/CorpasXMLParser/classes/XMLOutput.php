<?php


class XMLOutput
{
  public function getHeader($textId) {
    $textId = str_replace(".txt", "", $textId);
    $output = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<?xml-model href="gold.rnc" schematypens="https://relaxng.org/ns/structure/1.0?>
<!-- ?xml-stylesheet type="text/xsl" href="../../code/corpus.xsl"? -->
<text ref="_{$textId}" xmlns="https://dasg.ac.uk/corpus/" status="raw" xmlns:xi="http://www.w3.org/2001/XInclude">
XML;
    return $output;
  }

  public function getFooter() {
    $output = <<<XML
  </p>
</text>
XML;
    return $output;
  }
}