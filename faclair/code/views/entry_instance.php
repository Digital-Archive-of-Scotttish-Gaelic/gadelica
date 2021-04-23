<?php

namespace views;
use models;

class entry_instance {

	private $_model;   // an instance of models\entry_instance

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		$this->_writeEmbedded();
	}

  private function _writeEmbedded() {
		echo '<div class="list-group-item">';
		echo models\sources::getEmoji($this->_model->getSource());
		echo '&nbsp;&nbsp;<strong>' . $this->_model->getHw() . '</strong> ';
		echo '<em>' . $this->_model->getPos() . '</em> ';
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
		echo '<li><small>' . models\sources::getShortRef($this->_model->getSource()) . '</small></li>';
		echo '</ul>';
		echo '</div>';
	}

}
