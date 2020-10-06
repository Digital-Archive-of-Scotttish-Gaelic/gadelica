<?php

namespace controllers;
use models, views;

class text_sql
{
	private $_model;

	public function __construct($textId) {
		$this->_model = new models\text_sql($textId);
	}

	public function run($action) {
		if (empty($action)) {
			$action = "view";
		}
		switch ($action) {
			case "view":
				$view = new views\text_sql($this->_model);
				$view->printText();
				break;
			case "search":
				$origin = "?m=text&a=view&textId={$_GET["textId"]}";   //the originating script
				$controller = new corpussearch($origin);
				$controller->run("runSearch");
				break;
		}
	}
}