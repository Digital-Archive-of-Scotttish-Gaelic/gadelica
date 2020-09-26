<?php

class BrowseCorpusController2 {

    public $model = null;

    public function __construct() {
        $this->model = new BrowseCorpusModel();
        new BrowseCorpusView2($this->model->textList);
    }


}

?>
