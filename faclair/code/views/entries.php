<?php


namespace views;

class entries
{

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
    $html = '<div class="list-group list-group-flush">';
    foreach ($this->_model->getEntries() as $nextEntry) {
			$url = '?m=entry&mhw=' . $nextEntry[0] . '&mpos=' . $nextEntry[1] . '&msub=' . $nextEntry[2];
    	$html .= '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>' . $nextEntry[0] . '</strong> <em>' . $nextEntry[1] . '</em></a>';
    }
    $html .= '</div>';
		echo $html;
	}

}
