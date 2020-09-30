<?php

namespace controllers;
use models, views;

class Text
{
  public function __construct() {
    $show = isset($_GET["show"]) ? $_GET["show"] : "view";
    switch ($show) {
      case "view":
        $text = new models\CorpusText($_GET["uri"]);
        $view = new views\Text($text);
        $view->printText();
        break;
      case "search":
        $origin = "viewText.php?uri={$_GET["uri"]}";   //the originating script
        new CorpusSearch($origin);
        break;
    }
  }
}