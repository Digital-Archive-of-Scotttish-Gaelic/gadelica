<?php


class FileHandler
{
  private $_filename, $_filenameElems, $_textId;

  public function __construct($filename)
  {
    $this->_filename = $filename;
  }

  public function parseFile() {
    $fileContents = "";
    $this->_filenameElems = explode(' ', $this->_filename);
    $this->_textId = $this->_filenameElems[0];

    $id = str_replace(".txt", "", $this->_textId);

    $xml = new XMLOutput();

    //$fileContents = htmlspecialchars($header->get($textId), ENT_HTML5, ENT_NOQUOTES, 'UTF-8') ;
    $fileContents .= $xml->getHeader($this->_textId);
    $text = file_get_contents(INPUT_FILEPATH . $this->_filename);
    $tokeniser = new Tokeniser();
    $fileContents .= $tokeniser->run($text, $id);
    $fileContents .= $xml->getFooter();

    //run the transformation(s)
  //  $fileContents = $this->applyXSLT($fileContents);
    file_put_contents(OUTPUT_FILEPATH . $id . ".xml", $fileContents);
  }

  private function applyXSLT($text) {
      // Load XML into DOMDocument
      libxml_use_internal_errors(true);

      $xmlDoc = new DOMDocument();
  $xmlDoc->loadXML($text); /*
      if (!$xmlDoc->loadXML($text)) {
          foreach (libxml_get_errors() as $error) {
              echo $error->message;
          }
          libxml_clear_errors();
          exit;
      }*/

        // Load XSLT stylesheet
      $xslDoc = new DOMDocument();
      $xslDoc->load('../hyphens_apostrophes.xsl');

        // Configure the transformer
      $proc = new XSLTProcessor();
      $proc->importStylesheet($xslDoc);

        // Perform the transformation
      $transformedXml = $proc->transformToXML($xmlDoc);

        // Output or save the result
      return $transformedXml;
  }

  private function _getOutputFilename()
  {
    $outputFilename = $this->_textId;
    foreach ($this->_filenameElems as $elem) {
      if ($elem == $this->_textId) {
        continue;
      }
      $outputFilename .= '_' . $elem;
    }
    $outputFilename = str_replace(
      array("_teacsa", "'", "à", "è", "ì", "ò", "ù", "À", "È", "Ì", "Ò", "Ù"),
      array("", "_", "aa", "ee", "ii", "oo", "uu", "AA", "EE", "II", "OO", "UU"),
      $outputFilename);
    return str_replace(".txt", ".xml", $outputFilename);
  }
}