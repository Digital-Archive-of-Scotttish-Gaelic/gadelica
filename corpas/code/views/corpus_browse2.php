<?php

namespace views;

class corpus_browse2
{
	private $_model;   // an instance of models\corpus_browse2

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
    echo <<<HTML
		<ul class="nav nav-pills nav-justified">
		  <li class="nav-item"><a class="nav-link active" href="#">browse</a></li>
		  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id={$this->_model->getId()}">search</a></li>
		</ul>
HTML;
    if ($this->_model->getId() == "0") {
			$this->_showCorpus();
		}
		else {
      $this->_showText();
		}
		$this->_writeJavascript();
	}

  private function _showCorpus() {
		echo <<<HTML
			<table class="table">
				<tbody>
HTML;
    $texts = $this->_model->getTextList();
		foreach ($texts as $text) {
			$this->_writeRow($text);
		}
		echo <<<HTML
				</tbody>
			</table>
HTML;
  }

	private function _writeRow($text) {
		$writerHtml = $this->_formatWriters($text);
		echo <<<HTML
      <tr>
        <td>#{$text["id"]}</td>
        <td class="browseListTitle">
          <a href="?m=corpus&a=browse&id={$text["id"]}">{$text["title"]}</a>
        </td>
        <td>{$writerHtml}</td>
        <td>{$text["date"]}</td>
      </tr>
HTML;
	}

	private function _formatWriters($text) {
		$writersInfo = $text["writers"];
		if (empty($writersInfo)) {
			return;
		}
		$writerList = [];
		foreach ($writersInfo as $writerInfo) {
			$nickname = (empty($writerInfo["nickname"])) ? "" : " (" . $writerInfo["nickname"] . ")";
			$writerList[] = <<<HTML
            <a href="?m=writers&a=browse&id={$writerInfo["id"]}">
							{$writerInfo["forenames_gd"]} {$writerInfo["surname_gd"]}
						</a> {$nickname}
HTML;
		}
		return implode(", ", $writerList);
	}

  private function _showText() {
		echo <<<HTML
		  <h3>{$this->_model->getTitle()}</h3>
			<table class="table" id="meta" data-hi="{$_GET["id"]}">
				<tbody>
					{$this->_getWritersHtml()}
					{$this->_getDateHtml()}
					{$this->_getParentTextHtml()}
					{$this->_getMetadataLinkHtml()}
				</tbody>
			</table>
			<p>&nbsp;</p>
			{$this->_getChildTextsHtml()}
			{$this->_model->getTransformedText()}
HTML;
	}

	private function _getDateHtml() {
		if (!$this->_model->getDate()) {
			return "";
		}
		return "<tr><td>publication year</td><td>{$this->_model->getDate()}</td></tr>";
	}

	private function _getParentTextHtml() {
		if (!$this->_model->getParentText()) {
			return "";
		}
		$html = '<tr><td>part of</td><td>';
		$html .= '<a href="?m=corpus&a=browse&id=' . $this->_model->getParentText()->getId() . '">';
		$html .= $this->_model->getParentText()->getTitle();
		$html .= '</a></td></tr>';
		return $html;
	}

	private function _getWritersHtml() {
		if (!count($this->_model->getWriters())) {
			return "";
		}
		$html = '<tr><td>writer</td><td>';
		foreach ($this->_model->getWriters() as $writer) {
			$html .= '<a href="?m=writers&a=browse&id=' . $writer->getId() . '">';
			$html .= $writer->getForenamesGD() . ' ' . $writer->getSurnameGD();
			$html .= '</a>';
			$html .= ', ';
		}
		$html = rtrim($html);
		$html = trim($html, ",");
		$html .= '</td></tr>';
		return $html;
	}

	private function _getChildTextsHtml() {
		if (!count($this->_model->getChildTexts())) {
			return "";
		}
		else {
			$html = '<div class="list-group list-group-flush">';
			foreach ($this->_model->getChildTexts() as $childId => $childTitle) {
				$html .= '<div class="list-group-item list-group-item-action">';
				$html .= '#' . $childId .
					': <a href="?m=corpus&a=browse&id=' . $childId .'">' . $childTitle;
				$html .= '</a></div>';
			}
			$html .= '</div>';
		}
		return $html;
	}

	private function  _getMetadataLinkHtml() {
		$textId = $this->_model->getId();
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
