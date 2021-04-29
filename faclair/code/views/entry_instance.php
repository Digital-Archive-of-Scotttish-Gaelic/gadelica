<?php

namespace views;
use models;

class entry_instance {

	private $_model;   // an instance of models\entry_instance

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo '<div class="list-group-item">';
		echo models\sources::getEmoji($this->_model->getSource());
		echo '&nbsp;&nbsp;<strong>' . $this->_model->getHw() . '</strong> ';
		echo '<em class="text-muted" data-toggle="tooltip" title="' . models\entry::getPosInfo($this->_model->getPos())[2] . '">' . models\entry::getPosInfo($this->_model->getPos())[0] . '</em> ';
		echo '<ul style="list-style-type:none;">';
		if ($this->_model->getForms()) {
			echo '<li>';
			foreach ($this->_model->getForms() as $nextForm) {
				echo ' ' . $nextForm[0] . ' <em class="text-muted" data-toggle="tooltip" title="' . models\entry::getPosInfo($nextForm[1])[2] . '">' . models\entry::getPosInfo($nextForm[1])[0] . '</em> ';
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
			echo '<li><small class="text-muted">[';
			foreach ($this->_model->getNotes() as $nextNote) {
				echo '' . $nextNote[0] . '';
			}
			echo ']</small></li>';
		}
		echo '<li><small data-toggle="tooltip" data-html="true" data-placement="bottom" title="' . models\sources::getRef($this->_model->getSource()) . '">' . models\sources::getShortRef($this->_model->getSource()) . '</small></li>';
		echo '</ul>';
		echo '</div>';
	}

}
