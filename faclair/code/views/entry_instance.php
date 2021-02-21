<?php

namespace views;
use models;

class entry_instance {

	private $_model;   // an instance of models\entry_instance

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($action) {
		switch ($action) {
			case "embedded":
				$this->_writeEmbedded();
				break;
			case "edit":
				$this->_writeEditForm();
				break;
			case "add":
				$this->_writeAddForm();
				break;
			default:
			  $this->_writeInfo();
		}
	}

	private function _writeInfo() {
		echo '<h1>' . models\sources::getEmoji($this->_model->getSource()) . ' ' . $this->_model->getHw() . '</h1>';
		echo '<div class="list-group list-group-flush">';
		echo '<div class="list-group-item"><em class="text-muted">' . models\entries::getLongGd($this->_model->getPos()) . '</em></div>';
		foreach ($this->_model->getForms() as $nextForm) {
			echo '<div class="list-group-item"><strong>' . $nextForm[0] . '</strong> <em>' . $nextForm[1] . '</em></div>';
		}
		foreach ($this->_model->getTranslations() as $nextTranslation) {
			echo '<div class="list-group-item"><mark>' . $nextTranslation[0] . '</mark></div>';
		}
		foreach ($this->_model->getNotes() as $nextNote) {
			echo '<div class="list-group-item">' . $nextNote[0] . '</div>';
		}
		echo '<div class="list-group-item">[' . models\sources::getRef($this->_model->getSource()) . ']</div>';
		echo '<div class="list-group-item"><small><a href="?m=entry_instance&a=edit&id=' . $this->_model->getId() . '" target="_new">[edit]</a></small></div>';
		echo '<div class="list-group-item">➡️ <a href="?m=entry&mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">' . $this->_model->getMhw() . ' <em>' . models\entries::getShortGd($this->_model->getMpos()) . '</em></a></div>';
		echo '</div>';
	}

  private function _writeEmbedded() {
		echo '<div class="list-group-item">';
		echo models\sources::getEmoji($this->_model->getSource());
		echo ' <strong>' . $this->_model->getHw() . '</strong> ';
		echo '<em>' . $this->_model->getPos() . '</em> ';
		echo '<small><a href="?m=entry_instance&a=edit&id=' . $this->_model->getId() . '">[edit]</a></small>';
		echo '<ul style="list-style-type:none;">';
		if ($this->_model->getForms()) {
			echo '<li>';
			foreach ($this->_model->getForms() as $nextForm) {
				echo ' ' . $nextForm[0] . ' <em>' . $nextForm[1] . '</em> ';
			}
			echo '</li>';
		}
		$trs = $this->_model->getTranslations();
		if ($trs) {
			echo '<li class="text-muted">';
			foreach ($trs as $nextTranslation) {
				echo '<mark>' . $nextTranslation[0] . '</mark>';
				if ($nextTranslation!=end($trs)) { echo ' | '; }
			}
			echo '</li>';
		}
		if ($this->_model->getNotes()) {
			echo '<li>Notes:<ul>';
			foreach ($this->_model->getNotes() as $nextNote) {
				echo '<li>' . $nextNote[0] . '</li>';
			}
			echo '</ul></li>';
		}
		echo '<li><small><a href="?m=source&id=' . $this->_model->getSource() . '">' . models\sources::getShortRef($this->_model->getSource()) . '</a></small></li>';
		echo '</ul>';
		echo '</div>';
	}

	private function _writeEditForm() {
		$html = <<<HTML
			<form method="get">
				<div class="form-group">
					<label for="id">ID</label>
					<input type="text" id="id" disabled value="{$this->_model->getId()}">
					<input type="hidden" name="id" value="{$this->_model->getId()}">
				</div>
				<div class="form-group">
					<label for="source">source</label>
					<input type="text" name="source" id="source" disabled value="{$this->_model->getSource()}">
				</div>
				<div class="form-group">
					<label for="headword">hw</label>
					<input type="text" name="hw" id="hw" value="{$this->_model->getHw()}">
				</div>
				<div class="form-group">
					<label for="pos">pos</label>
					<input type="text" name="pos" id="pos" value="{$this->_model->getPos()}">
				</div>
				<div class="form-group">
					<label for="sub">sub</label>
					<input type="text" name="sub" id="sub" value="{$this->_model->getSub()}">
				</div>
				<div class="form-group">
					<label for="mhw">m-hw</label>
					<input type="text" name="mhw" id="mhw" value="{$this->_model->getMhw()}">
				</div>
				<div class="form-group">
					<label for="mpos">m-pos</label>
					<input type="text" name="mpos" id="mpos" value="{$this->_model->getMpos()}">
				</div>
				<div class="form-group">
					<label for="msub">m-sub</label>
					<input type="text" name="msub" id="msub" value="{$this->_model->getMsub()}">
				</div>
				<h2>Forms –</h2>
HTML;
		foreach ($this->_model->getForms() as $form) {
			$id = $form[2];
			$html .= <<<HTML
				<div class="form-group">
					<label for="form{$id}">form</label>
					<input type="text" name="form[{$id}]" id="form{$id}" value="{$form[0]}">
					<label for="delete-form{$id}">delete</label>
					<input type="checkbox" id="delete-form[{$id}]" name="delete-form[{$id}]">
				</div>
				<div class="form-group">
					<label for="morph{$id}">morph</label>
					<input type="text" name="morph[{$id}]" id="morph{$id}" value="{$form[1]}">
				</div>
HTML;
    }
		$html .= <<<HTML
				<button type="button" class="btn btn-success add-form">add</button>
				<div class="new-form">
					<div class="form-group">
				    <label for="form0">form</label>
						<input type="text" name="form[0]" id="form0">
					</div>
					<div class="form-group">
						<label for="morph0">morph</label>
						<input type="text" name="morph[0]" id="morph0">
					</div>
				</div>
				<h2>Translations –</h2>
HTML;
		foreach($this->_model->getTranslations() as $english) {
			$id = $english[1];
			$html .= <<<HTML
				<div class="form-group">
					<label for="en{$id}">en</label>
					<input type="text" name="en[{$id}]" id="en{$id}" value="{$english[0]}">
					<label for="delete-en{$id}">delete</label>
					<input type="checkbox" id="delete-en[{$id}]" name="delete-en[{$id}]">
				</div>
		HTML;
		}
		$html .= <<<HTML
				<button type="button" class="btn btn-success add-en">add</button>
				<div class="new-en">
					<div class="form-group">
				    <label for="en0">en</label>
						<input type="text" name="en[0]" id="en0">
					</div>
				</div>
				<h2>Notes –</h2>
HTML;
		foreach ($this->_model->getNotes() as $note) {
			$id = $note[1];
			$html .= <<<HTML
				<div class="form-group">
					<label for="note{$id}">note</label>
					<textarea name="note[{$id}]" id="note{$id}">{$note[0]}</textarea>
					<label for="delete-note{$id}">delete</label>
					<input type="checkbox" id="delete-note[{$id}]" name="delete-note[{$id}]">
				</div>
HTML;
		}
		$html .= <<<HTML
				<button type="button" class="btn btn-success add-note">add</button>
				<div class="new-note">
					<div class="form-group">
				    <label for="note0">note</label>
						<textarea name="note[0]" id="note0"></textarea>
					</div>
				</div>
HTML;
		$html .= <<<HTML
				<div>
					<input type="hidden" name="m" value="entry_instance">
					<input type="hidden" name="a" value="update">
					<input type="submit" class="btn btn-primary" value="save"></input>
					<button type="button" class="btn btn-secondary windowClose">cancel</button>
				</div>
			</form>

		<script>
			$(function () {
			  $('.windowClose').on('click', function () {
		      window.close();
		    });
			  $('.new-form').hide();
			  $('.new-en').hide();
			  $('.new-note').hide();
			  $('.add-form').on('click', function () {
			    $('.add-form').hide();
			    $('.new-form').show();
			  });
			  $('.add-en').on('click', function () {
			    $('.add-en').hide();
			    $('.new-en').show();
			  });
			  $('.add-note').on('click', function () {
			    $('.add-note').hide();
			    $('.new-note').show();
			  });
			});
		</script>
HTML;
		echo $html;
	}

   private function _writeAddForm() {
		 $html = <<<HTML
		 	<form method="get">
		 		<div class="form-group">
		 			<label for="id">mhw</label>
		 			<input type="text" id="mhw" disabled value="{$this->_model->getMhw()}">
		 			<input type="hidden" name="mhw" value="{$this->_model->getMhw()}">
		 		</div>
		 		<div class="form-group">
		 			<label for="id">mpos</label>
		 			<input type="text" id="mpos" disabled value="{$this->_model->getMpos()}">
		 			<input type="hidden" name="mpos" value="{$this->_model->getMpos()}">
		 		</div>
		 		<div class="form-group">
		 			<label for="id">msub</label>
		 			<input type="text" id="msub" disabled value="{$this->_model->getMsub()}">
		 			<input type="hidden" name="msub" value="{$this->_model->getMsub()}">
		 		</div>
		 		<div class="form-group">
		 			<label for="source">source</label>
		 			<input type="text" name="source" id="source">
		 		</div>
		 		<div class="form-group">
		 			<label for="hw">hw</label>
		 			<input type="text" name="hw" id="hw">
		 		</div>
		 		<div class="form-group">
		 			<label for="pos">pos</label>
		 			<input type="text" name="pos" id="pos">
		 		</div>
		 		<div class="form-group">
		 			<label for="sub">sub</label>
		 			<input type="text" name="sub" id="sub">
		 		</div>
HTML;
		 $html .= <<<HTML
		 		<div>
		 			<input type="hidden" name="a" value="save_new">
					<input type="hidden" name="m" value="entry_instance">
		 			<input type="submit" class="btn btn-primary" value="save"></input>
		 			<button type="button" class="btn btn-secondary windowClose">cancel</button>
		 		</div>
		 	</form>

		 <script>
		 	$(function () {
		 	  $('.windowClose').on('click', function () {
		       window.close();
		     });
		 	});
		 </script>
HTML;

		 echo $html;
	 }

}
