<?php

namespace views;
use models;

class english {

	private $_model;

	public function __construct($model) {
		$this->_model = $model;
    echo $this->_model->getHtml();
	}

}
