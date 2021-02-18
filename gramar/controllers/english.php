<?php
namespace controllers;
use models, views;

class english
{

  public function __construct() {
    $en = isset($_GET["en"]) ? $_GET["en"] : "";
    $model = new models\english($en);
    new views\english($model);
  }

}