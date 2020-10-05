<?php

namespace controllers;
use models, views;

class writer
{
  public function run($action) {
  	if (empty($action)) {
  		$action = "list";
	  }
    switch ($action) {
      case "view":
        $writer = new models\writer($_GET["uri"]);
        $view = new views\Writer();
        $view->printWriter($writer);
        break;
      case "list":
        $writers = models\writers::getWriters();
        $view = new views\Writer();
        $view->listWriters($writers);
        break;
    }
  }
}