<?php
namespace controllers;
use models, views;

class home
{

  public function __construct() {
    $model = new models\home();
    new views\home($model);
  }

}
