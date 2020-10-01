<?php

namespace controllers;
use models, views;

class Entries
{
  private $_view;

  public function __construct() {
	  $this->_view = new views\Entries();
  }

  public function run($action) {
	  if (empty($action)) {
		  $action = "browse";
	  }
	  switch ($action) {
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