<?php

class BrowseCorpusController2 {

  public $model = null;

  public function __construct() {
      $this->model = new BrowseCorpusModel();
      $textList = $this->model->textList;
      new BrowseCorpusView2($textList);
  }


}

?>
