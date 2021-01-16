<?php
namespace controllers;
use models, views;

class gaelic
{

  public function __construct() {
    $gd = isset($_GET["gd"]) ? $_GET["gd"] : "";
    $model = new models\gaelic($gd);
    new views\gaelic($model);
  }

}
