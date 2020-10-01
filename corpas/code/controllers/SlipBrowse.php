<?php

namespace controllers;
use views;

class SlipBrowse
{
  public function run($action) {
    $view = new views\SlipBrowse();
    switch ($action) {
	    case "browse":
		    $view->writeBrowseTable();
		    break;
	    default:
		    $view->writeBrowseTable();  //TODO: revisit as this is a duplicate SB
    }
  }
}