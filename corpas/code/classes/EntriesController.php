<?php


class EntriesController
{
  private $_view;

  public function __construct() {
    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "browse";
    }
    $this->_view = $view = new EntriesView();

    switch ($_REQUEST["action"]) {
      case "browse":
        $entriesData = Entries::getAllEntries();
        $this->_view->writeBrowseTable($entriesData);
        break;
      case "view":
        $entry = $_GET;   //this is just for testing
        $this->_view->writeEntry($entry);
        break;
    }

  }
}