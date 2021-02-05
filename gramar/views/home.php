<?php

namespace views;
use models;

class home {

	private $_model;

	public function __construct($model) {
		$this->_model = $model;
		echo "<ul>";
    foreach ($this->_model->getGds() as $nextGd) {
    	echo "<li><a href=\"?gd=" . $nextGd . "\">". $nextGd . "</a></li>";
    }
    echo "<li><a href=\"?en=the\">the</a></li>";
		echo "</ul>";
	}

}
