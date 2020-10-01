<?php

namespace controllers;
use models, views;

class Slip
{
  private $_db, $_slip;

  public function __construct($slipId) {
	  $this->_slip = new models\Slip($_GET["filename"], $_GET["id"], $slipId, $_GET["pos"]);
  }

  public function run($action) {
	  if (empty($action)) {
		  $action = "edit";
	  }
	  switch ($action) {
		  case "edit":
			  $slipView = new views\Slip($this->_slip);
			  $slipView->writeEditForm();
			  break;
	  }
  }
}