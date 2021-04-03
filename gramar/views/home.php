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
		echo "<li><a href=\"?xx=cardinals\">cardinal numbers</a></li>";
		echo "</ul>";
		echo <<<HTML
      <ul>
        <li><a href="?xx=nouns/nouns">nouns</a>
          <ul>
            <li><a href="?xx=nouns/common_nouns">common nouns</a></li>
						<li><a href="?xx=nouns/proper_nouns">proper nouns</a></li>
					</ul>
				</li>
				<li><a href="?xx=nouns/names">names</a>
          <ul>
            <li><a href="?xx=nouns/proper_nouns">proper nouns</a></li>
						<li><a href="?xx=nouns/name_descriptions">name-descriptions</a></li>
					</ul>
				</li>
			</ul>
HTML;


	}

}
