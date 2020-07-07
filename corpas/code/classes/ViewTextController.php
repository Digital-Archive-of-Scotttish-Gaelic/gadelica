<?php


class ViewTextController
{
  public function __construct() {
    $action = isset($_GET["action"]) ? $_GET["action"] : "view";
    switch ($action) {
      case "view":
        $text = new CorpusText($_GET["uri"]);
        $view = new ViewTextView($text);
        $view->printText();
        break;
    }
  }

}