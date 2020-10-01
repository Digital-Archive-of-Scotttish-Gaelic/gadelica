<?php


namespace controllers;
use models, views;

class Documentation
{
	private $_model;  //an instance of models\Documentation

	public function __construct() {
		$this->_model = new models\Documentation();
	}

	public function run($action) {
		if (empty($action)) {
			$action = "view";
		}
		switch ($action) {
			case "view":
				$html = $this->_model->getManualHtml();
				$view = new views\Documentation();
				$view->show($html);
		}
	}
}