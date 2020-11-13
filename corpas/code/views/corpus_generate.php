<?php

namespace views;

use models;

class corpus_generate
{
	private $_model;   // an instance of models\corpus_generate

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		$user = models\users::getUser($_SESSION["user"]);
    echo <<<HTML
		<ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
HTML;
    if ($this->_model->getId()=="0") {
			echo <<<HTML
			  <li class="nav-item"><a class="nav-link active" href="?m=corpus&a=browse&id=0">view corpus</a></li>
		    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id=0">search corpus</a></li>
HTML;
      if ($user->getSuperuser()) {
				echo <<<HTML
			    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id=0">add text</a></li>
HTML;
      }
			echo <<<HTML
				<li class="nav-item"><div class="nav-link active">corpus wordlist</div></li>
HTML;
		}
		else {
			echo <<<HTML
			<li class="nav-item"><a class="nav-link" href="?m=corpus&a=browse&id={$this->_model->getId()}">view text #{$this->_model->getId()}</a></li>
		  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=search&id={$this->_model->getId()}">search text #{$this->_model->getId()}</a></li>
HTML;
      if ($user->getSuperuser()) {
				echo <<<HTML
			    <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id={$this->_model->getId()}">edit text #{$this->_model->getId()}</a></li>
HTML;
      }
      echo <<<HTML
			<li class="nav-item"><div class="nav-link active">text #{$this->_model->getId()} wordlist</div></li>
HTML;
		}
		echo <<<HTML
		  </ul>
HTML;
		foreach ($this->_model->getLexemes() as $nextLexeme) {
    	echo $nextLexeme . '<br/>';
    }

	}

}
