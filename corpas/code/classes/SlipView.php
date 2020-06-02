<?php


class SlipView
{
  private $_slip;   //an instance of the Slip class

  public function __construct($slip) {
    $this->_slip = $slip;
  }

  public function writeEditForm() {
    $this->_writeHeader();
    $starred = $this->_slip->getStarred() ? "checked" : "";
    echo <<<HTML
      <div>
        {$this->_writeContext()}
      </div>
      <form method="post">
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="starred" id="slipStarred" {$starred}>
            <label class="form-check-label" for="slipStarred">starred</label>
          </div>
        </div>
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
  }

  private function _writeHeader() {
    echo <<<HTML
        <div>
            filename: <span id="slipFilename">{$this->_slip->getFilename()}</span><br>
            id: <span id="slipId">{$this->_slip->getId()}</span><br>
            headword: <span id="slipHeadword">{$_REQUEST["headword"]}</span><br>
            POS:<span id="slipPOS">{$_REQUEST["pos"]}</span><br><br>
        </div>
HTML;
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
    echo <<<HTML
            <div>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPre">+</a></span>
              </div>
              <span data-precontextscope="{$preScope}" data-postcontextscope="{$postScope}" id="slipContext">
                {$context["pre"]} <strong>{$context["word"]}</strong> {$context["post"]}
              </span>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div>
            </div>
HTML;
  }
}