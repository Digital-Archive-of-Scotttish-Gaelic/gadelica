<?php


namespace views;


class slow_search
{
	private $_model;  //an instance of models\slow_search

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo $_GET["xpath"];
	}
}