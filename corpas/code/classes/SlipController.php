<?php


class SlipController
{
  private $_db;

  public function __construct() {
    if (!isset($this->_db)) {
      $this->_db = new Database();
    }

    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "show";
    }

    switch ($_REQUEST["action"]) {
      case "show":
        $slip = new Slip($_GET["filename"], $_GET["id"]);
        $slipView = new SlipView($slip);
        $slipView->writeEditForm();
        break;
      case "save":
        $slip = new Slip($_POST["filename"], $_POST["id"]);
        $slip->saveSlip($_POST);
        $slipView = new SlipView($slip);
        $slipView->writeSavedState();
        break;
    }
  }
}