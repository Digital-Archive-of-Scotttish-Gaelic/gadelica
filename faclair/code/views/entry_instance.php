<?php

namespace views;
use models;

class entry_instance {

	private $_model;   // an instance of models\entry_instance

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($embedded) {
		if ($embedded) {
		  echo '<div class="list-group-item">';
		  echo '<strong>' . $this->_model->getHw() . '</strong> ';
		  echo '<em>' . $this->_model->getPos() . '</em> ';
		  echo '<small><a href="editLexeme.php?id=' . $this->_model->getId() . '" target="_new">[edit]</a></small>';
		  echo '<ul style="list-style-type:none;">';
		  if ($this->_model->getForms()) {
        echo '<li>';
        foreach ($this->_model->getForms() as $nextForm) {
    	    echo ' ' . $nextForm[0] . ' <em>' . $nextForm[1] . '</em> ';
        }
		    echo '</li>';
	    }
		  $trs = $this->_model->getTranslations();
		  if ($trs) {
        echo '<li class="text-muted">';
        foreach ($trs as $nextTranslation) {
    	    echo '<mark>' . $nextTranslation . '</mark>';
				  if ($nextTranslation!=end($trs)) { echo ' | '; }
        }
		    echo '</li>';
	    }
		  if ($this->_model->getNotes()) {
        echo '<li>Notes:<ul>';
        foreach ($this->_model->getNotes() as $nextNote) {
    	    echo '<li>' . $nextNote . '</li>';
        }
		    echo '</ul></li>';
	    }
		  echo '<li><small>[' . models\sources::getRef($this->_model->getSource()) . ']</small></li>';
		  echo '</ul>';
		  echo '</div>';
	  }
		else {
			echo '<h1>' . $this->_model->getHw() . '</h1>';
			echo '<div class="list-group list-group-flush">';
			echo '<div class="list-group-item"><em class="text-muted">' . models\entries::getLongGd($this->_model->getPos()) . '</em></div>';
			foreach ($this->_model->getForms() as $nextForm) {
				echo '<div class="list-group-item"><strong>' . $nextForm[0] . '</strong> <em>' . $nextForm[1] . '</em></div>';
			}
      foreach ($this->_model->getTranslations() as $nextTranslation) {
    	  echo '<div class="list-group-item text-muted">' . $nextTranslation . '</div>';
      }
      foreach ($this->_model->getNotes() as $nextNote) {
    	  echo '<div class="list-group-item">' . $nextNote . '</div>';
      }
			echo '<div class="list-group-item">[' . models\sources::getRef($this->_model->getSource()) . '] <small><a href="editLexeme.php?id=' . $this->_model->getId() . '" target="_new">[edit]</a></small></div>';
			echo '<div class="list-group-item">➡️ <a href="?m=entry&mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">' . $this->_model->getMhw() . ' <em>' . models\entries::getShortGd($this->_model->getMpos()) . '</em></a></div>';
			echo '</div>';
		}
	}

}
