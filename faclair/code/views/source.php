<?php

namespace views;
use models;

class source {

	private $_model;   // an instance of models\source

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo '<h1>' . models\Sources::getEmoji($this->_model->getId()). ' ' . models\Sources::getRef($this->_model->getId()) . '</h1>';
    echo '<div class="list-group list-group-flush">';
		$link = models\Sources::getExtLink($this->_model->getId());
		if ($link) {
			echo '<li class="list-group-item"><small><a href="' . $link . '" target="_new">[làrach lìn]</a></small></li>';
		}
		echo '<li class="list-group-item"><small><a href="#">[add]</a></small></li>';
    foreach ($this->_model->getInstances() as $nextInstance) {
			$url = '?m=entry_instance&id=' . $nextInstance[0];
    	echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>' . $nextInstance[1] . '</strong> <em>' . $nextInstance[2] . '</em></a>';
    }
		echo '</div>';
	}

}
