<?php


class XmlFileHandler
{
  private $_filename, $_xml, $_collocateIds;

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
    $context["word"] = $this->_getCollocateDropdown($word, $word[0]->attributes()["id"]);
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
		$this->_collocateIds = Lemmas::getCollocateIds($this->getFilename());
    foreach ($chunk as $i => $token) {
	    $isWord = ($wordId = $token->attributes()["id"]) ? true : false;
      $followingWord = ($i < (count($chunk)-1)) ? $chunk[$i+1] : null;
      $followingJoin = $followingWord ? $followingWord->attributes()["join"] : "";
      $attributes = $token->attributes();
      if ($i == 0) {
        $startJoin = (string)$attributes["join"];
      } else if ($i == (count($chunk) -1)) {
        $endJoin = (string)$attributes["join"];
      }
      $token = $isWord ? $this->_getCollocateDropdown($token, $wordId) : $token[0];
      switch ($attributes["join"]) {
        case "left":
          $output .= $followingJoin == "right" || $followingJoin == "both" ? $token : $token . ' ';
          $rightJoin = false;
          break;
        case "right":
          $output .= ' ' . $token;
          $rightJoin = true;
          break;
        case "both":
          $output .= $token;
          $rightJoin = true;
          break;
        default:
          $output .= $rightJoin ? $token : ' ' . $token;
          $rightJoin = false;
      }
    }
    return array("output" => $output, "startJoin" => $startJoin, "endJoin" => $endJoin);
  }

	/**
	 * @param $token:
	 * @param $wordId
	 * @return string : the HTML required for dropdown options for the given word (collocate)
	 */
  private function _getCollocateDropdown($word, $wordId) {
  	$existingCollocate = in_array($wordId, $this->_collocateIds) ? "existingCollocate" : "";
	  return <<<HTML
			<div class="dropdown show d-inline collocate" data-wordid="{$wordId}">
		    <a class="dropdown-toggle collocateLink {$existingCollocate}" href="#" id="dropdown_{$wordId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$word[0]}</a>		
			  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_{$wordId}">
			    <div class="dropdown-header">
			      <h5><span class="collocateHeadword"></span></h5>
					</div>
					<div class="dropdown-divider"></div>  
			    <a id="subject_{$wordId}" class="dropdown-item collocateGrammar" href="#">subject</a>
			    <a id="direct_object_{$wordId}" class="dropdown-item collocateGrammar" href="#">direct object</a>
			    <a id="indirect_object_{$wordId}" class="dropdown-item collocateGrammar" href="#">indirect object</a>
			  </div>
			</div>
HTML;
  }
}