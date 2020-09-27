<?php

class BrowseCorpusController2 {

    public $corpus = null;

    public function __construct() {
        $this->corpus = new CorpusModel();
        new BrowseCorpusView2($this->corpus);
    }


}

?>
