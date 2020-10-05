<?php

namespace controllers;
use models, views;

class text
{
	private $_model;

  public function __construct($uri) {
  	$this->_model = new models\text($uri);
  }

  public function run($action) {
	  if (empty($action)) {
	  	$action = "view";
	  }
	  switch ($action) {
		  case "view":
			  $view = new views\text($this->_model);
			  $view->printText();
			  break;
		  case "search":
			  $origin = "?m=text&a=view&uri={$_GET["uri"]}";   //the originating script
			  $controller = new corpussearch($origin);
			  $controller->run("runSearch");
			  break;
	  }
  }
}