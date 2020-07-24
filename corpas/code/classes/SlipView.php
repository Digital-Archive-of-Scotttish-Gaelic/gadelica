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
        <div class="form-group" id="slipChecked">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="starred" id="slipStarred" {$checked}>
            <label class="form-check-label" for="slipStarred">checked</label>
          </div>
        </div>
HTML;
    $this->_writeHeader();
    echo <<<HTML
      <div>
        {$this->_writeContext()}
      </div>
      <form method="post">
        <div class="form-group">
          <label for="translation">English translation:</label>
          <textarea class="form-control" name="translation" id="translation" rows="3">{$this->_slip->getTranslation()}</textarea>
          <script>
            CKEDITOR.replace('translation');
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
            <input type="hidden" name="auto_id" value="{$_REQUEST["auto_id"]}">
            <input type="hidden" name="pos" value="{$_REQUEST["pos"]}">
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

  private function _writeHeader() {
    echo <<<HTML
        <div>
            headword: <span id="slipHeadword">{$_REQUEST["headword"]}</span>
            {$this->_writeWordClassesSelect()}
        </div>
HTML;
  }

  private function _writeFooter() {
    $pos = new PartOfSpeech($_REQUEST["pos"]);
    $label = $_REQUEST["pos"] ? " ({$pos->getLabel()})" : "";
    echo <<<HTML
        <div>
            POS tag:<span id="slipPOS">{$_REQUEST["pos"]}{$label}</span><br><br>
            filename: <span id="slipFilename">{$this->_slip->getFilename()}</span><br>
            id: <span id="slipId">{$this->_slip->getId()}</span><br>
        </div>
HTML;
  }

  private function _writeWordClassesSelect(){
    $classes = $this->_slip->getWordClasses();
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
    $this->_writeHeader();
    $editUrl = "slipEdit.php?action=show&filename={$this->_slip->getFilename()}&id={$this->_slip->getId()}&headword={$_GET["headword"]}&pos={$_GET["pos"]}";
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
                <span><a href="#" class="updateContext" id="incrementPre">+</a></span>
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
}