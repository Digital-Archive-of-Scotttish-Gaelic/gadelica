<?php

namespace views;
use models;

class thematic {

	private $_model;

	public function __construct($model) {
		$this->_model = $model;
    echo $this->_model->getHtml();
	}

}
