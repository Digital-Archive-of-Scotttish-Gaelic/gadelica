<?php

namespace controllers;
use models, views;

class Entries
{
  private $_view;

  public function __construct() {
    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "browse";
    }
    $this->_view = $view = new views\Entries();

    switch ($_REQUEST["action"]) {
      case "browse":
        $entriesData = models\Entries::getAllEntries();
        $this->_view->writeBrowseTable($entriesData);
        break;
      case "view":
        $entry = models\Entries::getEntry($_GET["headword"], $_GET["wordclass"]);   //this is just for testing
        $this->_view->writeEntry($entry);
        break;
    }

  }
}