<?php

namespace views;
use models;

class englishes {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
    $html = '<div class="list-group list-group-flush">';
    foreach ($this->_model->getTerms() as $nextTerm) {
			$url = '?m=english&en=' . $nextTerm;
    	$html .= '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>' . $nextTerm . '</strong></a>';
    }
    $html .= '</div>';
		echo $html;
	}

}
