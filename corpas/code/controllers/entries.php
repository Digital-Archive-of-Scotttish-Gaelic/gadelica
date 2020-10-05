<?php

namespace controllers;
use models, views;

class entries
{
  private $_view;

  public function __construct() {
	  $this->_view = new views\entries();
  }

  public function run($action) {
	  if (empty($action)) {
		  $action = "browse";
	  }
	  switch ($action) {
		  case "browse":
			  $entriesData = models\entries::getAllEntries();
			  $this->_view->writeBrowseTable($entriesData);
			  break;
		  case "view":
			  $entry = models\entries::getEntry($_GET["headword"], $_GET["wordclass"]);   //this is just for testing
			  $this->_view->writeEntry($entry);
			  break;
	  }
  }
}