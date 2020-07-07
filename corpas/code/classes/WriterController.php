<?php


class WriterController
{
  public function __construct($action = "list") {

    switch ($action) {
      case "view":
        $writer = new Writer($_GET["uri"]);
        $view = new WriterView();
        $view->printWriter($writer);
        break;
      case "list":
        $writers = Writers::getWriters();
        $view = new WriterView();
        $view->listWriters($writers);
        break;
    }
  }
}