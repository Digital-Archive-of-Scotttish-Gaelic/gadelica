<?php

namespace views;
use models;

class entry {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($action) {
		$this->_writeInfo();
	}

  private function _writeInfo() {
		echo '<h1>' . $this->_model->getMhw() . '</h1>';
		echo '<p><em class="text-muted" data-toggle="tooltip" title="' . models\entry::getPosInfo($this->_model->getMpos())[2] . '">' . models\entry::getPosInfo($this->_model->getMpos())[1] . '</em></p>';
		$ps = $this->_model->getParts();
    if ($ps) {
		  echo '<p>↗️ ';
		  foreach ($ps as $nextPart) {
			  echo '<a href="?m=entry&mhw=' . $nextPart[0] . '&mpos=' . $nextPart[1] . '&msub=' . $nextPart[2] . '">' . $nextPart[0] . '</a> <em>' . models\entry::getPosInfo($nextPart[1])[0] . '</em> ';
			  if ($nextPart!=end($ps)) { echo ' | '; }
		  }
		  echo '</p>';
    }
		echo '<div class="list-group">';
		foreach ($this->_model->getInstances() as $nextInstance) {
			$view = new entry_instance($nextInstance);
			$view->show();
		}
		echo '</div>';
		echo '<p> </p>';
		$cs = $this->_model->getCompounds();
		if ($cs) {
			echo '<p>↘️ ';
			foreach ($cs as $nextCompound) {
				echo '<a href="?m=entry&mhw=' . $nextCompound[0] . '&mpos=' . $nextCompound[1] . '&msub=' . $nextCompound[2] . '">' . $nextCompound[0] . '</a> <em>' . models\entry::getPosInfo($nextCompound[1])[0] . '</em>';
        if ($nextCompound!=end($cs)) { echo ' <span class="text-muted">|</span> '; }
			}
			echo '</p>';
		}
	}

}
