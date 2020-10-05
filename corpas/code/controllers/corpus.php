<?php

namespace controllers;
use models, views;

class corpus
{
	private $_model;

  public function __construct() {
	  $this->_model = new models\corpus();
  }

	public function run($action) {
  	if (empty($action)) {
  		$action = "browse";
	  }
  	switch ($action) {
		  case "browse":
			  $textList = $this->_model->getTextList();
			  $view = new views\corpus();
			  $view->writeTable($textList);
			  break;
	  }
	}
}