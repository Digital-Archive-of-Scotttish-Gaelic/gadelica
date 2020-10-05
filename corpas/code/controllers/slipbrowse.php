<?php

namespace controllers;
use views;

class slipbrowse
{
  public function run($action) {
    $view = new views\slipbrowse();
    switch ($action) {
	    case "browse":
		    $view->writeBrowseTable();
		    break;
	    default:
		    $view->writeBrowseTable();  //TODO: revisit as this is a duplicate SB
    }
  }
}