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
        $slip = new Slip($_GET["filename"], $_GET["id"], $_GET["auto_id"], $_GET["pos"]);
        $slipView = new SlipView($slip);
        $slipView->writeEditForm();
        break;
      /* Currently handled by AJAX
       * case "save":
        $slip = new Slip($_POST["filename"], $_POST["id"], $_POST["auto_id"], $_POST["pos"]);
        $slip->saveSlip($_POST);
        break;
      */
    }
  }
}