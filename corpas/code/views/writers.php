<?php

namespace views;
use models;

class writers
{

	private $_model;   // an instance of models\writers2

	public function __construct($model) {
		$this->_model = $model;
	}

  public function show() {
			$html = "";
			foreach ($this->_model->getAllWritersInfo() as $writerInfo) {
				$origin = $writerInfo["district_1"];
				$origin2 = $writerInfo["district_2"];
				$districtHtml = "";
				if (isset($origin)) {
					$district = new models\district($origin);
					$districtHtml = $district->getName();
					if (isset($origin2)) {
						$district2 = new models\district($origin2);
						$districtHtml .= " / " . $district2->getName();
					}
				}
				$html .= <<<HTML
					<tr>
						<td><a href="?m=writers&a=browse&id={$writerInfo["id"]}">@{$writerInfo["id"]}</a></td>
						<td>{$writerInfo["title"]} {$writerInfo["forenames_en"]} <strong>{$writerInfo["surname_en"]}</strong></td>
						<td>{$writerInfo["forenames_gd"]} <strong>{$writerInfo["surname_gd"]}</strong></td>
						<td>{$writerInfo["nickname"]}</td>
						<td>{$this->_getLifeSpan($writerInfo)}</td>
						<td>{$districtHtml}</td>
					</tr>
HTML;
			}
			echo <<<HTML
			  <ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
				  <li class="nav-item"><div class="nav-link active">browse</div></li>
				  <li class="nav-item"><a class="nav-link" href="?m=writers&a=add">add</a></li>
			  </ul>
				<table class="table">
					<tbody>
						{$html}
					</tbody>
				</table>
HTML;
	}

	private function _getLifeSpan($info) {
		if ($info["yob"] == "" && $info["yod"] == "") { return ""; }
		return $info["yob"] . '–' . $info["yod"];
	}
}
