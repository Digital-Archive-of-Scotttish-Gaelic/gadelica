<?php

namespace controllers;
use models, views;

class Slip
{
  private $_db;

  public function __construct() {
    if (!isset($this->_db)) {
      $this->_db = new models\Database();
    }

    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "show";
    }

    switch ($_REQUEST["action"]) {
      case "show":
      	$slipId = !empty($_GET["auto_id"]) ? $_GET["auto_id"] : false;
        $slip = new models\Slip($_GET["filename"], $_GET["id"], $slipId, $_GET["pos"]);
        $slipView = new views\Slip($slip);
        $slipView->writeEditForm();
        break;
    }
  }
}