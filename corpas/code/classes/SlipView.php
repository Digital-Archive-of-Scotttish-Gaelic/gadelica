<?php


class SlipView
{
  private $_slip; //an instance of the Slip class

  public function __construct($slip) {
    $this->_slip = $slip;
  }

  public function writeEditForm() {
    $this->_writeHeader();
    echo <<<HTML
      <div>
        {$this->_writeContext()}
      </div>
      <form>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="starred" id="slipStarred" checked>
            <label class="form-check-label" for="slipStarred">starred</label>
          </div>
        </div>
        <div class="form-group">
          <label for="translation">English translation:</label>
          <textarea class="form-control" id="translation" rows="3">
            {$this->_slip->getTranslation()}
          </textarea>
        </div>
        <div class="form-group">
          <label for="notes">Notes:</label>
          <textarea class="form-control" id="notes" rows="3">
            {$this->_slip->getNotes()}
          </textarea>
        </div>
        <div class="form-group">
          <div class="input-group">
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
            headword: <span id="slipHeadword">{$_GET["headword"]}</span><br>
            POS:<span id="slipPOS">{$_GET["pos"]}</span><br><br>
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