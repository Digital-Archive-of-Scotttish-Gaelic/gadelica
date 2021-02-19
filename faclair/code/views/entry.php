<?php

namespace views;

class entry {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		echo '<h1>' . $this->_model->getMhw() . ' <em>' . $this->_model->getMpos() . '</em></h1>';
		echo '<h5>Sources</h5><ul>';
    foreach ($this->_model->getInstances() as $nextInstance) {
      $view = new entry_instance($nextInstance);
			$view->show();
    }
		echo '<li><small><a href="addLexeme.php?mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">[add]</a></small></li>';
		echo '</ul>';
		echo '<h5>Parts</h5><ul>';
    foreach ($this->_model->getParts() as $nextPart) {
      echo '<li><a href="?m=entry&mhw=' . $nextPart[0] . '&mpos=' . $nextPart[1] . '&msub=' . $nextPart[2] . '">' . $nextPart[0] . '</a> <em>' . $nextPart[1] . '</em> <small><a href="deletePart.php?id=' . $nextPart[3] . '">[delete]</a></small></li>';
		}
		echo '<li><small><a href="addPart.php?mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">[add]</a></small></li>';
		echo '</ul>';
		if ($this->_model->getCompounds()) {
		  echo '<h5>Compounds</h5><ul>';
		  foreach ($this->_model->getCompounds() as $nextCompound) {
			  echo '<li><a href="?m=entry&mhw=' . $nextCompound[0] . '&mpos=' . $nextCompound[1] . '&msub=' . $nextCompound[2] . '">' . $nextCompound[0] . '</a> <em>' . $nextCompound[1] . '</em></li>';
		  }
		  echo '</ul>';
	  }
		echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
	}

}
