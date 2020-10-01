<?php

namespace controllers;
use models, views;

class Text
{
	private $_model;

  public function __construct($uri) {
  	$this->_model = new models\Text($uri);
  }

  public function run($action) {
	  if (empty($action)) {
	  	$action = "view";
	  }
	  switch ($action) {
		  case "view":
			  $view = new views\Text($this->_model);
			  $view->printText();
			  break;
		  case "search":
			  $origin = "?m=text&a=view&uri={$_GET["uri"]}";   //the originating script
			  new CorpusSearch($origin);
			  break;
	  }
  }
}