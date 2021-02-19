<?php

namespace views;

class source {

	private $_model;   // an instance of models\source

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo '<h1>' . $this->_model->getId() . '</h1>';
    echo '<div class="list-group list-group-flush">';
    foreach ($this->_model->getInstances() as $nextInstance) {
			$url = '?m=entry_instance&id=' . $nextInstance[0];
    	echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>' . $nextInstance[1] . '</strong> <em>' . $nextInstance[2] . '</em></a>';
    }
		echo '</div>';
	}

}
