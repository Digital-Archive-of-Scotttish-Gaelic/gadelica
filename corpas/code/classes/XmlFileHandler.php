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

  public function getUri() {
    $xpath = '/dasg:text/@ref';
    $out = $this->_xml->xpath($xpath);
    return (string)$out[0];
  }

  public function getContext($id, $preScope = 12, $postScope = 12, $normalisePunc = true) {
    $context = array();
    $context["id"] = $id;
    $context["filename"] = $this->getFilename();
    $xpath = '/dasg:text/@ref';
    $out = $this->_xml->xpath($xpath);
    $context["uri"] = (string)$out[0];
    $xpath = "//dasg:w[@id='{$id}']/preceding::*";
    $words = $this->_xml->xpath($xpath);
    /* preContext processing */
    $context["pre"] = "";
    if ($preScope) {
      $pre = array_slice($words, -$preScope);
      //check if we're one token away from the start of the document
      $nextIndex = $preScope + 1;
      $limitCheck = array_slice($words, -$nextIndex);
      if (count($limitCheck) != count($pre)+1) {
        $context["prelimit"] = count($pre);
      }
      if ($normalisePunc) {
        $context["pre"] = $this->_normalisePunctuation($pre);
      } else {
        $context["pre"]["output"] = implode(' ', $pre);
      }
    }
    /* -- */
    $xpath = "//dasg:w[@id='{$id}']";
    $word = $this->_xml->xpath($xpath);
    $context["word"] = (string)$word[0];
    $xpath = "//dasg:w[@id='{$id}']/following::*";
    $words = $this->_xml->xpath($xpath);
    /* postContext processing */
    $context["post"] = "";
    if ($postScope) {
      $post = array_slice($words,0, $postScope);
      //check if we're one token away from the end of the document
      $nextIndex = $postScope + 1;
      $limitCheck = array_slice($words, 0, $nextIndex);
      if (count($limitCheck) != count($post)+1) {
        $context["postlimit"] = count($post);
      }
      if ($normalisePunc) {
        $context["post"] = $this->_normalisePunctuation($post);
      } else {
        $context["post"]["output"] = implode(' ', $post);
      }
      //check if the scope has reached the end of the document
      if (count($post) < $postScope) {
        $context["post"]["limit"] = count($post);
      }
    }
    return $context;
  }

  /**
   * Parses an array of SimpleXML objects and formats the punctuation
   * @param array $chunk : array of SimpleXML objects
   * @return array : an array containing output string and flags for start and end joins
   */
  private function _normalisePunctuation (array $chunk) {
    $output = $startJoin = $endJoin = "";
    $rightJoin = true;
    foreach ($chunk as $i => $word) {
      $followingWord = ($i < (count($chunk)-1)) ? $chunk[$i+1] : null;
      $followingJoin = $followingWord ? $followingWord->attributes()["join"] : "";
      $attributes = $word->attributes();
      if ($i == 0) {
        $startJoin = (string)$attributes["join"];
      } else if ($i == (count($chunk) -1)) {
        $endJoin = (string)$attributes["join"];
      }
      switch ($attributes["join"]) {
        case "left":
          $output .= $followingJoin == "right" || $followingJoin == "both" ? $word[0] : $word[0] . ' ';
          $rightJoin = false;
          break;
        case "right":
          $output .= ' ' . $word[0];
          $rightJoin = true;
          break;
        case "both":
          $output .= $word[0];
          $rightJoin = true;
          break;
        default:
          $output .= $rightJoin ? $word[0] : ' ' . $word[0];
          $rightJoin = false;
      }
    }
    return array("output" => $output, "startJoin" => $startJoin, "endJoin" => $endJoin);
  }
}