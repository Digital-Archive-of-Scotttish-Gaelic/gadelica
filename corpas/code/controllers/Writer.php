<?php

namespace controllers;
use models, views;

class Writer
{
  public function __construct($action = "list") {

    switch ($action) {
      case "view":
        $writer = new models\Writer($_GET["uri"]);
        $view = new views\Writer();
        $view->printWriter($writer);
        break;
      case "list":
        $writers = models\Writers::getWriters();
        $view = new views\Writer();
        $view->listWriters($writers);
        break;
    }
  }
}