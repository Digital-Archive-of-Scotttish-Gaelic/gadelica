<?php
namespace controllers;
use models, views;

class thematic
{

  public function __construct() {
    $xx = isset($_GET["xx"]) ? $_GET["xx"] : "";
    $model = new models\thematic($xx);
    new views\thematic($model);
  }

}
