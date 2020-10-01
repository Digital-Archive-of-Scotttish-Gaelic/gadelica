<?php

namespace controllers;
use models, views;

class Corpus
{
	private $_model;

  public function __construct() {
	  $this->_model = new models\Corpus();
  }

	public function run($action) {
  	if (empty($action)) {
  		$action = "browse";
	  }
  	switch ($action) {
		  case "browse":
			  $textList = $this->_model->getTextList();
			  $view = new views\Corpus();
			  $view->writeTable($textList);
			  break;
	  }
	}
}