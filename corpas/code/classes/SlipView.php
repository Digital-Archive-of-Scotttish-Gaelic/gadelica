<?php


class SlipView
{
  private $_slip;   //an instance of the Slip class

  public function __construct($slip) {
    $this->_slip = $slip;
  }

  public function writeEditForm() {
    $checked = $this->_slip->getStarred() ? "checked" : "";
    echo <<<HTML
        <div>
            {$this->_writeContext()}
        </div>
        <form method="post">
        <div class="form-group" id="slipChecked">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="starred" id="slipStarred" {$checked}>
            <label class="form-check-label" for="slipStarred">checked</label>
          </div>
        </div>
         <div>
            headword: <span id="slipHeadword">{$_REQUEST["headword"]}</span>
        </div>
HTML;
    $this->_writePartOfSpeechSelects();
    echo <<<HTML
        <div class="form-group">
          <label for="translation">English translation:</label>
          <textarea class="form-control" name="translation" id="translation" rows="3">{$this->_slip->getTranslation()}</textarea>
          <script>
            CKEDITOR.replace('translation', {
              customConfig: 'https://dasg.ac.uk/gadelica/corpas/code/js/ckConfig.js'
            });
          </script>
        </div>
        <div class="form-group">
          <label for="notes">Notes:</label>
          <textarea class="form-control" name="notes" id="notes" rows="3">{$this->_slip->getNotes()}</textarea>
          <script>
            CKEDITOR.replace('notes');
          </script>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input type="hidden" name="filename" value="{$_REQUEST["filename"]}">
            <input type="hidden" name="id" value="{$_REQUEST["id"]}">
            <input type="hidden" id="auto_id" name="auto_id" value="{$this->_slip->getAutoId()}">
            <input type="hidden" id="pos" name="pos" value="{$_REQUEST["pos"]}">
            <input type="hidden" id="preContextScope" name="preContextScope" value="{$this->_slip->getPreContextScope()}">
            <input type="hidden" id="postContextScope" name="postContextScope" value="{$this->_slip->getPostContextScope()}">
            <input type="hidden" name="action" value="save"/>
            <div class="input-group">
              <button name="submit" class="btn btn-primary" type="submit">save</button>
            </div>
          </div>
        </div>
      </form>
HTML;
    $this->_writeFooter();;
  }

  private function _writeFooter() {
    $pos = new PartOfSpeech($_REQUEST["pos"]);
    $label = $_REQUEST["pos"] ? " ({$pos->getLabel()})" : "";
    echo <<<HTML
        <div>
            slip ID:<span id="auto_id">{$_REQUEST["auto_id"]}</span><br>
            POS tag:<span id="slipPOS">{$_REQUEST["pos"]}{$label}</span><br><br>
            filename: <span id="slipFilename">{$this->_slip->getFilename()}</span><br>
            id: <span id="slipId">{$this->_slip->getId()}</span><br>
        </div>
HTML;
    $this->_writeJavascript();
  }

  private function _writePartOfSpeechSelects() {
    echo $this->_writeWordClassesSelect();
    $props = $this->_slip->getSlipMorph()->getProps();
    $numgenOptions = array("masculine singular", "feminine singular", "plural", "singular (gender unclear)",
      "feminine dual", "unclear");
    $caseOptions = array("nominative", "genitive", "dative", "unclear");
    $statusOptions = array("dependent", "independent", "relative", "verbal noun", "unclear");
    $tenseOptions = array("present", "future", "past", "conditional", "unclear");
    $moodOptions = array("active", "passive", "unclear");
    $numgenOptionHtml = $caseOptionHtml = $statusOptionHtml = $tenseOptionHtml = $moodOptionHtml = "";
    foreach ($numgenOptions as $numgen) {
      $selected = $numgen == $props["numgen"] ? "selected" : "";
      $numgenOptionHtml .= <<<HTML
        <option value="{$numgen}" {$selected}>{$numgen}</option>
HTML;
    }
    foreach ($caseOptions as $case) {
      $selected = $case == $props["case"] ? "selected" : "";
      $caseOptionHtml .= <<<HTML
        <option value="{$case}" {$selected}>{$case}</option>
HTML;
    }
    foreach ($statusOptions as $status) {
      $selected = $status == $props["status"] ? "selected" : "";
      $statusOptionHtml .= <<<HTML
        <option value="{$status}" {$selected}>{$status}</option>
HTML;
    }
    foreach ($tenseOptions as $tense) {
      $selected = $tense== $props["tense"] ? "selected" : "";
      $tenseOptionHtml .= <<<HTML
        <option value="{$tense}" {$selected}>{$tense}</option>
HTML;
    }
    foreach ($moodOptions as $mood) {
      $selected = $mood == $props["mood"] ? "selected" : "";
      $moodOptionHtml .= <<<HTML
        <option value="{$mood}" {$selected}>{$mood}</option>
HTML;
    }
    $nounSelectHide = $this->_slip->getWordClass() == "noun" ? "" : "hide";
    $verbSelectHide = $this->_slip->getWordClass() == "verb" ? "" : "hide";
    $verbalNounHide = $props["status"] == "verbal noun" ? "hide" : "";
    echo <<<HTML
        <div>
            <div id="nounSelects" class="{$nounSelectHide}">
                <label for="posNumberGender">Number:</label>
                <select name="numgen" id="posNumberGender" class="form-control col-2">      
                  {$numgenOptionHtml}
                </select>  
                <label for="posCase">Case:</label>
                <select name="case" id="posCase" class="form-control col-2">      
                  {$caseOptionHtml}
                </select>      
            </div>
            <div id="verbSelects" class="{$verbSelectHide}">
                <label for="posStatus">Status:</label>
                <select name="status" id="posStatus" class="form-control col-2">      
                  {$statusOptionHtml}
                </select>  
                <span id="nonVerbalNounOptions" class="{$verbalNounHide}">
                  <label for="posTense">Tense:</label>
                  <select name="tense" id="posTense" class="form-control col-2">      
                    {$tenseOptionHtml}
                  </select>   
                  <label for="posMood">Mood:</label>
                  <select name="mood" id="posMood" class="form-control col-2">      
                    {$moodOptionHtml}
                  </select>
                </span>      
            </div>
        </div>
HTML;

  }

  private function _writeWordClassesSelect(){
    $classes = $this->_slip->getWordClasses();
    echo "<h1>{$this->_slip->getWordClass()}</h1>";
    $optionHtml = "";
    foreach ($classes as $class => $posArray) {
      $selected = $class == $this->_slip->getWordClass() ? "selected" : "";
      $optionHtml .= <<<HTML
        <option value="{$class}" {$selected}>{$class}</option>
HTML;
    }
    $html = <<<HTML
        <div id="wordClassSelect" class="form-group">
          <label for="wordClass">Part-of-speech:</label>
          <select name="wordClass" id="wordClass" class="form-control col-3">      
            {$optionHtml}
          </select>
      </div>
HTML;
    return $html;
  }

  public function writeSavedState() {
    $editUrl = "slipEdit.php?action=show&filename={$this->_slip->getFilename()}&id={$this->_slip->getId()}
      &headword={$_GET["headword"]}&pos={$_GET["pos"]}&auto_id={$this->_slip->getAutoId()}";
    echo <<<HTML
        <div>
            <h2>Slip saved</h2>
        </div>
        <div class="form-group">
            <div class="input-group">
                <a href="{$editUrl}" name="edit" id="savedEdit" class="btn btn-danger">edit</a>
                <button name="close" id="savedClose" class="btn btn-success">close</button>
            </div> 
        </div>
HTML;

  }

  private function _writeContext() {
    $handler = new XmlFileHandler($this->_slip->getFilename());
    $preScope = $this->_slip->getPreContextScope();
    $postScope = $this->_slip->getPostContextScope();
    $context = $handler->getContext($this->_slip->getId(), $preScope, $postScope);
    $preHref = "href=\"#\"";
    //check for start/end of document
    if (isset($context["prelimit"])) {
      $preScope = $context["prelimit"];
      $preHref = "";
    }
    $contextHtml = $context["pre"]["output"];
    if ($context["pre"]["endJoin"] != "right" && $context["pre"]["endJoin"] != "both") {
      $contextHtml .= ' ';
    }
    $contextHtml .= '<span id="slipWordInContext">' . $context["word"] . '</span>';
    if ($context["post"]["startJoin"] != "left" && $context["post"]["startJoin"] != "both") {
      $contextHtml .= ' ';
    }
    $contextHtml .= $context["post"]["output"];
    echo <<<HTML
            <div id="slipContextContainer">
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a {$preHref} class="updateContext" id="incrementPre">+</a></span>
              </div>
              <span data-precontextscope="{$preScope}" data-postcontextscope="{$postScope}" id="slipContext">
                {$contextHtml}
              </span>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div>
            </div>
HTML;
  }

  private function _writeJavascript() {
    echo <<<HTML
        <script>        
            $('#wordClass').on('change', function() {
              if($(this).val() == "verb") {
                $('#verbSelects').show();
                $('#nonVerbalNounOptions').show();
                $('#nounSelects').hide();
              } else if ($(this).val() == "noun") {
                $('#nounSelects').show();
                $('#verbSelects').hide();
              } else {
                $('#nounSelects').hide();
                $('#verbSelects').hide();
              }
            });
            
            $('#posStatus').on('change', function() {
              if($(this).val() == "verbal noun") {
                $('#nonVerbalNounOptions').hide();
              } else {
                $('#nonVerbalNounOptions').show();
              }
            });
        </script>
HTML;
  }
}