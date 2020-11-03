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
				$districtHtml = "";
				if (isset($origin)) {
					$district = new models\district($origin);
					$districtHtml = $district->getName();
				}
				$html .= <<<HTML
					<tr>
						<td><a href="?m=writers&a=browse&id={$writer->getId()}">@{$writer->getId()}</a></td>
						<td>{$writer->getForenamesGD()} {$writer->getSurnameGD()}</td>
						<td>{$writer->getNickname()}</td>
						<td>{$writer->getForenamesEN()} {$writer->getSurnameEN()}</td>
						<td>{$writer->getYearOfBirth()} - {$writer->getYearOfDeath()}</td>
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
					<thead>
						<tr>
							<th>id</th>
							<th>Gaelic name</th>
							<th>Nickname</th>
							<th>English name</th>
							<th>Years</th>
							<th>District</th>
						</tr>
					</thead>
					<tbody>
						{$html}
					</tbody>
				</table>
HTML;
	}

}
