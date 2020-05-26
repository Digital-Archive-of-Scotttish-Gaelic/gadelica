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
            <input class="form-check-input" type="checkbox" name="starred" id="starredCheck" checked>
            <label class="form-check-label" for="starredCheck">starred</label>
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
            filename: {$this->_slip->getFilename()}<br>
            id: {$this->_slip->getId()}<br>
            headword: <br>
            POS:<br><br>
        </div>
HTML;
  }

  private function _writeContext() {
    $handler = new XmlFileHandler($this->_slip->getFilename());
    $context = $handler->getContext($this->_slip->getId(), $this->_slip->getPreContextScope(),
      $this->_slip->getPostContextScope());
    echo $context["pre"] . " <strong>{$context["word"]}</strong> " . $context["post"];
  }
}