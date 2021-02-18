<?php

namespace views;

class entry
{

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
    $html = '';
		$html .= '<h1>' . $this->_model->getMhw();
		$html .= ' <em>' . $this->_model->getMpos() . '</em></h1>';
		$html .= '<h5>Sources</h5><ul>';
    foreach ($this->_model->getInstances() as $nextInstance) {
    	$html .= '<li>';
			$html .= '[' . $nextInstance[0] . '] ';
			$html .= '<strong>' . $nextInstance[1] . '</strong> ';
			$html .= '<em>' . $nextInstance[2] . '</em> ';
			$html .= '</li>';
    }

		$html .= '</ul>';
		echo $html;
	}

}
