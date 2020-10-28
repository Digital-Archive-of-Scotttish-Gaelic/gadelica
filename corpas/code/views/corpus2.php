<?php

namespace views;

class corpus2 extends search
{
	private $_text;   //an instance of models\text

	public function __construct($text) {
		$this->_text = $text;
	}

	public function show() {
		echo <<<HTML
        <h3>{$this->_text->getTitle()}</h3>
        <table class="table" id="meta" data-hi="{$_GET["id"]}">
          <tbody>
            {$this->_getWritersHtml()}
            {$this->_getDateHtml()}
            {$this->_getParentHtml()}
            {$this->_getMetadataLinkHtml()}
          </tbody>
        </table>
        <p>&nbsp;</p>
        {/*$this->_getSearchFormHtml()*/}
        {$this->_getChildHtml()}
        {$this->_text->getTransformedText()}
HTML;
		$this->_writeJavascript();
	}

	private function _getSearchFormHtml() {
		$html = <<<HTML
        <form>
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="search"/>
              <div class="input-group-append">
                <input type="hidden" name="textId" value="{$_GET["textId"]}">
                <input type="hidden" name="filename" value="{$this->_text->getFilepath()}">
                <input type="hidden" name="a" value="search"/>
                <input type="hidden" name="m" value="text"/>
                <input type="hidden" name="show" value="search">
                <button name="submit" class="btn btn-primary" type="submit">search this text</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="mode" id="headwordRadio" value="headword" checked>
              <label class="form-check-label" for="headwordRadio">headword</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="mode" id="wordformRadio" value="wordform">
              <label class="form-check-label" for="wordformRadio">wordform</label>
            </div>
          </div>
          <div id="wordformOptions" class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="caseSensitiveRadio" name="case" value="sensitive">
                <label class="form-check-label" for="caseSensitiveRadio">case sensitive</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="accentSensitiveRadio" name="accent" value="sensitive">
              <label class="form-check-label" for="accentSensitiveRadio">accent sensitive</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="lenitionSensitiveRadio" name="lenition" value="sensitive">
              <label class="form-check-label" for="lenitionSensitiveRadio">mutation sensitive</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="view" id="corpusViewRadio" value="corpus" checked>
              <label class="form-check-label" for="corpusViewRadio">corpus view</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="view" id="dictionaryViewRadio" value="dictionary">
              <label class="form-check-label" for="dictionaryViewRadio">dictionary view</label>
            </div>
          </div>
          {$this->_getSelectPosHtml()}
        </form>
HTML;
		return $html;
	}

	private function _getDateHtml() {
		if (!$this->_text->getDate()) {
			return "";
		}
		return "<tr><td>publication year</td><td>{$this->_text->getDate()}</td></tr>";
	}

	private function _getParentHtml() {
		if (!$this->_text->getParent()) {
			return "";
		}
		$html = '<tr><td>part of</td><td>';
		$html .= '<a href="?m=text&a=view&textId=' . $this->_text->getParent()->getId() . '">';
		$html .= $this->_text->getParent()->getTitle();
		$html .= '</a></td></tr>';
		return $html;
	}

	private function _getWritersHtml() {
		if (!count($this->_text->getWriters())) {
			return "";
		}
		$html = '<tr><td>writer</td><td>';
		foreach ($this->_text->getWriters() as $writer) {
			$html .= '<a href="?m=writer&a=view&writerId=' . $writer->getId() . '">';
			$html .= $writer->getForenamesGD() . ' ' . $writer->getSurnameGD();
			$html .= '</a>';
			$html .= ', ';
		}
		$html = rtrim($html);
		$html = trim($html, ",");
		$html .= '</td></tr>';
		return $html;
	}

	private function _getChildHtml() {
		//echo count($this->_text->getChildInfo()); // MMMMMMM
		//echo ' ';
		echo $this->_text->getId(); ///////
		if (!count($this->_text->getChildInfo())) {
			return "";
		}
		else {
			$html = '<div class="list-group list-group-flush">';
			foreach ($this->_text->getChildInfo() as $childId => $childTitle) {
				$html .= '<div class="list-group-item list-group-item-action">';
				$html .= '#' . $childId .
					': <a href="?m=text&a=view&textId=' . $childId .'">' . $childTitle;
				$html .= '</a></div>';
			}
			$html .= '</div>';
		}
		return $html;
	}

	private function  _getMetadataLinkHtml() {
		$textId = $this->_text->getId();
		$html = <<<HTML
			<tr>
				<td colspan="2">
					<a href="https://dasg.ac.uk/corpus/textmeta.php?text={$textId}&uT=y" target="_blank">more info</a>
				</td>
			</tr>
HTML;
		return $html;
	}

	private function _writeJavascript() {
		echo <<<HTML
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        hi = $('#meta').attr('data-hi');
        $('#'+hi).css('background-color', '#fcf8e3');
       //$('body').animate({scrollTop: $('#'+hi).offset().top - 180},500);
        document.getElementById(hi).scrollIntoView({behavior: 'smooth', block: 'center'})
      });
    </script>
HTML;
	}
}
