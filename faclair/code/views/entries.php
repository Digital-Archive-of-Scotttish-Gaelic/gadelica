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
    	$html .= '<a href="' . $url . '" class="list-group-item list-group-item-action">' . $nextEntry[0] . '</a>';
    }
    $html .= '</div>';
		echo $html;
	}

}
