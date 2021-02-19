<?php

namespace views;

class entry_instance {

	private $_model;   // an instance of models\entry_instance

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo '<li>';
		echo '[' . $this->_model->getSource() . '] ';
		echo '<strong>' . $this->_model->getHw() . '</strong> ';
		echo '<em>' . $this->_model->getPos() . '</em> ';
		echo '<small><a href="editLexeme.php?id=' . $this->_model->getId() . '">[edit]</a></small>';
    echo '<ul>';
		if ($this->_model->getForms()) {
      echo '<li>Forms:<ul>';
      foreach ($this->_model->getForms() as $nextForm) {
    	  echo '<li>' . $nextForm[0] . ' <em>' . $nextForm[1] . '</em></li>';
      }
		  echo '</ul></li>';
	  }
		if ($this->_model->getTranslations()) {
      echo '<li>Translations:<ul>';
      foreach ($this->_model->getTranslations() as $nextTranslation) {
    	  echo '<li>‘' . $nextTranslation . '’</li>';
      }
		  echo '</ul></li>';
	  }
		if ($this->_model->getNotes()) {
      echo '<li>Notes:<ul>';
      foreach ($this->_model->getNotes() as $nextNote) {
    	  echo '<li>' . $nextNote . '</li>';
      }
		  echo '</ul></li>';
	  }
		echo '</ul>';
		echo '</li>';
	}

}
