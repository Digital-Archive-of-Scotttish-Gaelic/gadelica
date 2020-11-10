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
				$origin = $writerInfo["district_1_id"];
				$origin2 = $writerInfo["district_2_id"];
				$districtHtml = "";
				if (isset($origin)) {
					$district = new models\district($origin);
					$districtHtml = '<a href="?m=districts&a=browse&id=' . $origin . '">' . $district->getName() . '</a>';
					if (isset($origin2) && $origin2 != "0") {
						$district2 = new models\district($origin2);
						$districtHtml .= ' / <a href="?m=districts&a=browse&id=' . $origin2 . '">' . $district2->getName() . '</a>';
					}
				}
				$nameHtml = $writerInfo["title"] . " " . $writerInfo["forenames_en"] . " <strong>" . $writerInfo["surname_en"] . "</strong>";
        $nameGdHtml = $writerInfo["forenames_gd"] . " " . $writerInfo["surname_gd"];
        $nkname = $writerInfo["nickname"];
				if ($nameGdHtml!=" " || $nkname!="") {
					$nameHtml .= ' <span class="text-muted">(';
					if ($nameGdHtml!=" ") {
						$nameHtml .= $nameGdHtml;
						if ($nkname!="") {
							$nameHtml .= " / " . $nkname;
						}
					}
					else {
						$nameHtml .= $nkname;
					}
					$nameHtml .= ")</span>";
				}
				$html .= <<<HTML
					<tr>
						<td><a href="?m=writers&a=browse&id={$writerInfo["id"]}">@{$writerInfo["id"]}</a></td>
						<td>{$nameHtml}</td>
						<td>{$this->_getLifeSpan($writerInfo)}</td>
						<td>{$districtHtml}</td>
					</tr>
HTML;
			}
			echo <<<HTML
			  <ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
				  <li class="nav-item"><div class="nav-link active">view writers</div></li>
				  <li class="nav-item"><a class="nav-link" href="?m=writers&a=edit">add writer</a></li>
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
