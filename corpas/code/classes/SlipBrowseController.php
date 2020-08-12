<?php


class SlipBrowseController
{
  public function __construct() {
    $view = new SlipBrowseView();
    $view->writeBrowseTable();
  }
}