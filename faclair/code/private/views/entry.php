<?php

namespace views;
use models;

class entry {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($action) {
		switch ($action) {
			case "add_part":
				$this->_writeAddPartForm();
				break;
			case "delete_part":
				//$controller = new entry();
				break;
			default:
			  $this->_writeInfo();
		  }
	}

  private function _writeInfo() {
		echo '<h1>' . $this->_model->getMhw() . '</h1>';
		echo '<p><em class="text-muted" data-toggle="tooltip" title="' . models\search::getLongEn($this->_model->getMpos()) . '">' . models\search::getLongGd($this->_model->getMpos()) . '</em></p>';
		$ps = $this->_model->getParts();
    if (SUPERUSER || $ps) {
		  echo '<p>↗️ ';
		  foreach ($ps as $nextPart) {
			  echo '<a href="?m=entry&mhw=' . $nextPart[0] . '&mpos=' . $nextPart[1] . '&msub=' . $nextPart[2] . '">' . $nextPart[0] . '</a> <em>' . models\search::getShortGd($nextPart[1]) . '</em> ';
        if (SUPERUSER) {
			    echo '<small><a href="?m=entry&a=delete_part&id=' . $nextPart[3] . '&mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">[delete]</a></small>';
        }
			  if ($nextPart!=end($ps)) { echo ' | '; }
		  }
		  if (SUPERUSER) {
		    echo ' <span class="text-muted">|</span> <small><a href="?m=entry&a=add_part&mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">[add]</a></small>';
      }
		  echo '</p>';
    }
		echo '<div class="list-group">';
		foreach ($this->_model->getInstances() as $nextInstance) {
			$view = new entry_instance($nextInstance);
			$view->show('embedded');
		}
		if (SUPERUSER) {
		  echo '<div class="list-group-item"><small><a href="?m=entry_instance&a=add&mhw=' . $this->_model->getMhw() . '&mpos=' . $this->_model->getMpos() . '&msub=' . $this->_model->getMsub() . '">[add]</a></small></div>';
    }
		echo '</div>';
		echo '<p> </p>';
		$cs = $this->_model->getCompounds();
		if ($cs) {
			echo '<p>↘️ ';
			foreach ($cs as $nextCompound) {
				echo '<a href="?m=entry&mhw=' . $nextCompound[0] . '&mpos=' . $nextCompound[1] . '&msub=' . $nextCompound[2] . '">' . $nextCompound[0] . '</a> <em>' . models\search::getShortGd($nextCompound[1]) . '</em>';
        if ($nextCompound!=end($cs)) { echo ' <span class="text-muted">|</span> '; }
			}
			echo '</p>';
		}
	}

	private function _writeAddPartForm() {
		$html = <<<HTML
			<form method="get">
				<div class="form-group">
					<label for="id">mhw</label>
					<input type="text" id="mhw" disabled value="{$this->_model->getMhw()}">
					<input type="hidden" name="mhw" value="{$this->_model->getMhw()}">
				</div>
				<div class="form-group">
					<label for="id">mpos</label>
					<input type="text" id="mpos" disabled value="{$this->_model->getMpos()}">
					<input type="hidden" name="mpos" value="{$this->_model->getMpos()}">
				</div>
				<div class="form-group">
					<label for="id">msub</label>
					<input type="text" id="msub" disabled value="{$this->_model->getMsub()}">
					<input type="hidden" name="msub" value="{$this->_model->getMsub()}">
				</div>
				<div class="form-group">
					<label for="mphw">mphw</label>
					<input type="text" name="mphw" id="mphw">
				</div>
				<div class="form-group">
					<label for="mppos">mppos</label>
					<input type="text" name="mppos" id="mppos">
				</div>
				<div class="form-group">
					<label for="mpsub">mpsub</label>
					<input type="text" name="mpsub" id="mpsub">
				</div>
				<div>
					<input type="hidden" name="a" value="save_part">
					<input type="hidden" name="m" value="entry">
					<input type="submit" class="btn btn-primary" value="save"></input>
					<button type="button" class="btn btn-secondary windowClose">cancel</button>
				</div>
			</form>
		<script>
			$(function () {
			  $('.windowClose').on('click', function () {
		      window.close();
		    });
			});
		</script>
HTML;
		echo $html;
	}

}
