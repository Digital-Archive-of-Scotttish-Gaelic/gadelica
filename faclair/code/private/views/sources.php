<?php

namespace views;
use models;

class sources {

	private $_model;   // an instance of models\sources

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		$html = '<div class="list-group list-group-flush">';
		foreach ($this->_model->getSources() as $nextSource) {
			$url = '?m=source&id=' . $nextSource;
			$html .= '<a href="' . $url . '" class="list-group-item list-group-item-action">' . models\Sources::getEmoji($nextSource) . '&nbsp;&nbsp;' . models\Sources::getRef($nextSource) . '</a>';
		}
		if (SUPERUSER) {
			$html .= '<div class="list-group-item"><small><a href="?m=entry_instance&a=add">[add]</a></small></div>';
		}
		$html .= '</div>';
		echo $html;
	}

}
