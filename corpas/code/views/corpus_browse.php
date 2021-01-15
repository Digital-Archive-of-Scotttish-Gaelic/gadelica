<?php

namespace views;

use models;

class corpus_browse
{
	private $_model;   // an instance of models\corpus_browse

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($action = null) {
		if ($action == "edit") {
			$this->_writeEditForm();
			return;
		}
		$user = models\users::getUser($_SESSION["user"]);
    echo <<<HTML
		<ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
HTML;
    if ($this->_model->getId()=="0") {
			echo <<<HTML
			  <li class="nav-item"><div class="nav-link active">viewing corpus</div></li>
		    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id=0">search corpus</a></li>
HTML;
      if ($user->getSuperuser()) {
				echo <<<HTML
			    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id=0">add text</a></li>
HTML;
      }
			echo <<<HTML
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id=0">corpus wordlist</a></li>
HTML;
		}
		else {
			echo <<<HTML
			<li class="nav-item"><div class="nav-link active">viewing text #{$this->_model->getId()}</div></li>
		  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id={$this->_model->getId()}">search text #{$this->_model->getId()}</a></li>
HTML;
      if ($user->getSuperuser()) {
				echo <<<HTML
			    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id={$this->_model->getId()}">edit text #{$this->_model->getId()}</a></li>
HTML;
      }
      echo <<<HTML
			<li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id={$this->_model->getId()}">text #{$this->_model->getId()} wordlist</a></li>
HTML;
		}
		echo <<<HTML
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

	private function _writeEditForm() {
		$user = models\users::getUser($_SESSION["user"]);
		if (!$user->getSuperuser()) {
			$this->show();
			return;
		}
		echo <<<HTML
		<ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
HTML;
		if ($this->_model->getId()=="0") {
			echo <<<HTML
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=browse&id=0">view corpus</a></li>
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id=0">search corpus</a></li>
				<li class="nav-item"><div class="nav-link active">adding text</div></li>
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id=0">corpus wordlist</a></li>
HTML;
		}
		else {
			echo <<<HTML
			  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=browse&id={$this->_model->getId()}">view text #{$this->_model->getId()}</a></li>
			  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id={$this->_model->getId()}">search text #{$this->_model->getId()}</a></li>
			  <li class="nav-item"><div class="nav-link active">editing text #{$this->_model->getId()}</div></li>
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id={$this->_model->getId()}">text #{$this->_model->getId()} wordlist</a></li>
HTML;
		}
		echo <<<HTML
		</ul>
		<hr/>
HTML;

		if ($this->_model->getID() == "0") {
			$formHtml = $this->_getFormSubTextSectionHtml();
		} else if ($this->_model->getChildTextsInfo()) { //text has subTexts
			$formHtml = $this->_getFormMetadataSectionHtml() . $this->_getFormSubTextSectionHtml();
		} else if ($this->_model->getFilepath()) { //text has a filepath
			$formHtml = $this->_getFormMetadataSectionHtml() . $this->_getFormFilepathSectionHtml();
		} else {
		  $formHtml = $this->_getFormMetadataSectionHtml() . $this->_getFormSubTextSectionHtml() . $this->_getFormFilepathSectionHtml();
		}
		echo <<<HTML
			<form id="corpusEdit" action="index.php?m=corpus&a=save&id={$this->_model->getID()}" method="post">
				{$formHtml}
				<button type="submit" class="btn btn-success">edit</button>
			</form>

		<!-- check to see if a user tries to leave the page without saving changes -->
		<script>
			let formChanged = false;
			let corpusEdit = document.getElementById('corpusEdit');
			corpusEdit.addEventListener('change', () => formChanged = true);
			window.addEventListener('beforeunload', (event) => {
        if (formChanged) {
          event.returnValue = 'You have unsaved changes!';
        }
			});
		</script>
HTML;
	}

	private function _getFormMetadataSectionHtml() {
		$writersHtml = $this->_getWritersFormHtml();
		$levelHtml = <<<HTML
			<select name="textLevel" id="textLevel">
HTML;
		for ($i=1; $i<4; $i++) {
			$selected = $this->_model->getLevel() == $i ? "selected" : "";
			$levelHtml .= <<<HTML
				<option value="{$i}" {$selected}>{$i}</option>
HTML;
		}
		$levelHtml .= "</select>";
		$html = <<<HTML
					<div>
						Text ID : {$this->_model->getID()}
					</div>
					<div class="form-group">
						<label for="textTtle">Title</label>
						<input class="form-control" type="text" name="textTitle" id="textTitle" value="{$this->_model->getTitle()}">
					</div>
					<div>
						<h4>Writers</h4>
							{$writersHtml}
					</div>
					<div class="form-group">
						<label for="textDate">Date</label>
						<input class="form-control" type="text" name="textDate" id="textDate" value="{$this->_model->getDate()}">
					</div>
					<div class="form-group">
						<label for="textLevel">Text Level</label>
						{$levelHtml}
					</div>
					<div class="form-group">
						<label for="textNotes">Text Notes</label>
						<textarea id="textNotes" name="textNotes">{$this->_model->getNotes()}</textarea>
					</div>
HTML;
		return $html;
	}

	private function _getFormSubTextSectionHtml() {
		$prefix = ($this->_model->getID() == 0) ? "" : $this->_model->getID() . "-";
		$defaultLevel = $this->_model->getLevel() ? $this->_model->getLevel() : 3;
		$levelHtml = <<<HTML
			<select name="subTextLevel" id="subTextextLevel">
HTML;
		for ($i=1; $i<4; $i++) {
			$selected = $defaultLevel == $i ? "selected" : "";
			$levelHtml .= <<<HTML
				<option value="{$i}" {$selected}>{$i}</option>
HTML;
		}
		$levelHtml .= "</select>";
		$html = <<<HTML
				<div class="form-group">
					<label for="subTextId">SubText ID</label>
					{$prefix}<input class="form-control"  type="text" name="subTextId" id="subTextId">
				</div>
				<div class="form-group">
					<label for="subTextTitle">SubText Title</label>
					<input class="form-control" type="text" name="subTextTitle" id="subTextTitle">
				</div>
				<div class="form-group">
					<label for="subTextDate">SubText Date</label>
					<input class="form-control" type="text" name="subTextDate" id="subTextDate">
				</div>
				<div class="form-group">
					<label for="subTextLevel">SubText Level</label>
					{$levelHtml}
				</div>
				<div class="form-group">
					<label for="textNotes">SubText Notes</label>
					<textarea id="subTextNotes" name="subTextNotes"></textarea>
				</div>
HTML;
		return $html;
	}

	private function _getFormFilepathSectionHtml() {
		$html = <<<HTML
				<div class="form-group">
					<label for="filepath">Filepath</label>
					<input class="form-control" type="text" name="filepath" id="filepath" value="{$this->_model->getFilepath()}">
				</div>
HTML;
		return $html;
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
		$levelColours = array(1 => "gold", 2 => "silver", 3 => "bronze");
		$levelHtml = $text["level"] == 0 ? "" : <<<HTML
			<i class="fas fa-star {$levelColours[$text["level"]]}"></i>
HTML;
		echo <<<HTML
      <tr>
        <td>#{$text["id"]}</td>
        <td>{$levelHtml}</td>
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
							{$writerInfo["forenames_en"]} {$writerInfo["surname_en"]}
						</a> {$nickname}
HTML;
		}
		return implode(", ", $writerList);
	}

	/**
	 * Generates alist of existing writers and an input field for a new writer ID
	 * @return string $html
	 */
	private function _getWritersFormHtml() {
		$html = "<ul>";
		$writerIds = $this->_model->getWriterIds();
		foreach($writerIds as $writerId) {
			$writer = new models\writer($writerId);
			$name = empty($writer->getForenamesGD()) || empty($writer->getSurnameGD())
				? $writer->getForenamesEN() . " " . $writer->getSurnameGD()
				: $writer->getForenamesGD() . " " . $writer->getSurnameGD();
			if (!empty($writer->getNickname())) {
				$name .= " - " . $writer->getNickname();
			}
			$html .= <<<HTML
				<li>{$name} ({$writerId})</li>
HTML;
		}
		$html .= <<<HTML
			<div class="form-group">
				<label for="writerId">New writer ID</label>
				<input class="form-control" type="text" id="writerId" name="writerId">
			</div>
HTML;

		return $html;
	}

  private function _showText() {
		echo <<<HTML
			<table class="table" id="meta" data-hi="{$_GET["id"]}">
				<tbody>
					<tr><td>title</td><td>{$this->_model->getTitle()}</td></tr>
					{$this->_getWritersHtml()}
					{$this->_getDateHtml()}
					{$this->_getLevelHtml()}
					{$this->_getNotesHtml()}
					{$this->_getParentTextHtml()}
					{$this->_getMetadataLinkHtml()}
					{$this->_getChildTextsHtml()}
				</tbody>
			</table>
			{$this->_model->getTransformedText()}
HTML;
	}

	private function _getLevelHtml() {
		if (!$this->_model->getLevel()) {
			return "";
		}
		$levelColours = array(1 => "gold", 2 => "silver", 3 => "bronze");
		$level = $this->_model->getLevel();
		$levelHtml = <<<HTML
			<i class="fas fa-star {$levelColours[$level]}"></i>
HTML;
		return "<tr><td>level</td><td>{$levelHtml}</td></tr>";
	}

	private function _getNotesHtml() {
		if (!$this->_model->getNotes()) {
			return "";
		}
		return "<tr><td>notes</td><td>{$this->_model->getNotes()}</td></tr>";
	}


	private function _getDateHtml() {
		if (!$this->_model->getDate()) {
			return "";
		}
		return "<tr><td>date</td><td>{$this->_model->getDate()}</td></tr>";
	}

	private function _getParentTextHtml() {
		$parentText = $this->_model->getParentText();
		$pid = $parentText->getId();
		if ($pid == "0") {
			return "";
		}
		$html = '<tr><td>parent text</td><td>';
		$html .= '<a href="?m=corpus&a=browse&id=' . $pid . '">';
		$html .= $parentText->getTitle();
		$html .= '</a></td></tr>';
		return $html;
	}

	private function _getWritersHtml() {
		if (!count($this->_model->getWriters())) {
			return "";
		}
		$html = '<tr><td>writers</td><td>';
		foreach ($this->_model->getWriters() as $writer) {
			$html .= '<a href="?m=writers&a=browse&id=' . $writer->getId() . '">';
			$html .= $writer->getForenamesEN() . ' ' . $writer->getSurnameEN();
			$html .= '</a>';
			$html .= ', ';
		}
		$html = rtrim($html);
		$html = trim($html, ",");
		$html .= '</td></tr>';
		return $html;
	}

	private function _getChildTextsHtml() {
		if (!count($this->_model->getChildTextsInfo())) {
			return "";
		}
		else {
			$html = '<tr><td>contents</td><td>';
			$html .= '<div class="list-group list-group-flush">';
			foreach ($this->_model->getChildTextsInfo() as $childId => $childTitle) {
				$html .= '<div class="list-group-item list-group-item-action">';
				$html .= '#' . $childId .
					': <a href="?m=corpus&a=browse&id=' . $childId .'">' . $childTitle;
				$html .= '</a></div>';
			}
			$html .= '</div></td></tr>';
		}
		return $html;
	}

	private function _getMetadataLinkHtml() {
		$textId = $this->_model->getId();
		$html = <<<HTML
			<tr>
				<td colspan="2">
					<small><a href="https://dasg.ac.uk/corpus/textmeta.php?text={$textId}&uT=y" target="_blank">more</a></small>
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
        hi = '{$_GET["wid"]}';
        $('#'+hi).addClass('mark');
        document.getElementById(hi).scrollIntoView({behavior: 'smooth', block: 'center'})
      });
    </script>
HTML;
	}
}
