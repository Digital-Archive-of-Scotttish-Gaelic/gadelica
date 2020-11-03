<?php

namespace views;
use models;

class writers2
{

	private $_model;   // an instance of models\writers2

	public function __construct($model) {
		$this->_model = $model;
	}

  public function show() {
			$html = "";
			foreach ($this->_model->getAllWriters() as $writer) {
				$origin = $writer->getOrigin();
				$origin2 = $writer->getOrigin2();
				$districtHtml = "";
				if (isset($origin)) {
					$district = new models\district2($origin);
					$districtHtml = $district->getName();
					if (isset($origin2)) {
						$district2 = new models\district2($origin2);
						$districtHtml .= " / " . $district2->getName();
					}
				}
				$html .= <<<HTML
					<tr>
						<td><a href="?m=writers&a=browse&id={$writer->getId()}">@{$writer->getId()}</a></td>
						<td>{$writer->getFullNameEN()}</td>
						<td>{$writer->getFullnameGD()}</td>
						<td>{$writer->getNickname()}</td>
						<td>{$writer->getLifeSpan()}</td>
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

}
