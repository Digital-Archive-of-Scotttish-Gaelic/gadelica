<?php

namespace controllers;
use models, views;

class ViewText
{
  public function __construct() {
    $show = isset($_GET["show"]) ? $_GET["show"] : "view";
    switch ($show) {
      case "view":
        $text = new models\CorpusText($_GET["uri"]);
        $view = new views\ViewText($text);
        $view->printText();
        break;
      case "search":
        $origin = "viewText.php?uri={$_GET["uri"]}";   //the originating script
        $searchController = new SearchController($origin);
        break;
    }
  }

}