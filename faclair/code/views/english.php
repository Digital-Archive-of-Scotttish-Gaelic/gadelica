<?php

namespace views;
use models;

class english {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($action) {
		echo '<h1>' . $this->_model->getEn() . '</h1>';
		echo '<div class="list-group list-group-flush">';
		foreach ($this->_model->getEntries() as $nextEntry) {
			$view = new entry_instance($nextEntry);
		  $view->show('embedded');
		}
		echo '</div>';
	}

}
