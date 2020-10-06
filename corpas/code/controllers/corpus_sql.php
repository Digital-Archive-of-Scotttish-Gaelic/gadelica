<?php

namespace controllers;
use models, views;

class corpus_sql
{
	private $_model;

	public function __construct() {
		$this->_model = new models\corpus_sql();
	}

	public function run($action) {
		if (empty($action)) {
			$action = "browse";
		}
		switch ($action) {
			case "browse":
				$texts = $this->_model->getTextList();
				$view = new views\corpus_sql();
				$view->writeTable($texts);
				break;
		}
	}
}