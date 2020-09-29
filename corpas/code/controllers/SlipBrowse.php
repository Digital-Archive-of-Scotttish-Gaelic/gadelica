<?php

namespace controllers;
use views;

class SlipBrowse
{
  public function __construct() {
    $view = new views\SlipBrowse();
    $view->writeBrowseTable();
  }
}