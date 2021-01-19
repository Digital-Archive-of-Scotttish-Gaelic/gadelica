<?php

namespace models;

class home {

  private $_gds;

  public function __construct() {
    $this->_gds = ["airson", "aon", "fad", "faighnich", "iarr"];
  }

  public function getGds() {
		return $this->_gds;
	}

}
