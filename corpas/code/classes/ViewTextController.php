<?php


class ViewTextController
{
  public function __construct() {
    $show = isset($_GET["show"]) ? $_GET["show"] : "view";
    switch ($show) {
      case "view":
        $text = new CorpusText($_GET["uri"]);
        $view = new ViewTextView($text);
        $view->printText();
        break;
      case "search":
        $origin = "viewText2.php?uri={$_GET["uri"]}";   //the originating script
        $searchController = new SearchController($origin);
        break;
    }
  }

}