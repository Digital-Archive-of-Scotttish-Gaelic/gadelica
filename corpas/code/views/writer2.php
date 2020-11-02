<?php

namespace views;
use models;

class writer2
{

	private $_model;   // an instance of models\writer2

	public function __construct($model) {
		$this->_model = $model;
	}

  public function show() {
			$writer = $this->_model;
			$html = <<<HTML
			  <ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
				  <li class="nav-item"><div class="nav-link active">browse</div></li>
				  <li class="nav-item"><a class="nav-link" href="?m=writers&a=edit&id={$this->_model->getId()}">edit</a></li>
			  </ul>
				<table class="table">
					<tbody>
						{$this->_getSurnameHtml($writer)}
						{$this->_getForenamesHtml($writer)}
						{$this->_getNicknameHtml($writer)}
						{$this->_getYearOfBirthHtml($writer)}
						{$this->_getYearOfDeathHtml($writer)}
						{$this->_getDistrict1Html($writer)}
						{$this->_getTextsHtml($writer)}
					</tbody>
				</table>
HTML;
			echo $html;
	}

	public function edit() {
		$writer = $this->_model;
		$this->_writeForm($writer);
	}

	private function _writeForm($writer) {
		$preferredNameOptions = array("gd"=>"Gaelic", "en"=>"English", "nk"=>"Nickname");
		$preferredNameHtml = "";
		foreach ($preferredNameOptions as $abbr => $option) {
			$selected = ($abbr == $writer->getPreferredName()) ? "selected" : "";
			$preferredNameHtml .= <<<HTML
				\n<option value="{$abbr}" {$selected}>{$option}</option>
HTML;
		}
		$html = <<<HTML
			<form action="index2.php?m=writers&a=save" method="post">
				<div class="form-group">
					<label for="surname_gd">Surname (Gaelic)</label>
					<input type="text" name="surname_gd" id="surname_gd" value="{$writer->getSurnameGD()}">
				</div>
				<div class="form-group">
					<label for="forenames_gd">Forename(s) (Gaelic)</label>
					<input type="text" name="forenames_gd" id="forenames_gd" value="{$writer->getForenamesGD()}">
				</div>
				
				<div class="form-group">
					<label for="surname_en">Surname (English)</label>
					<input type="text" name="surname_en" id="surname_en" value="{$writer->getSurnameEN()}">
				</div>
				<div class="form-group">
					<label for="forenames_gd">Forename(s) (English)</label>
					<input type="text" name="forenames_en" id="forenames_en" value="{$writer->getForenamesEN()}">
				</div>
				
				<div class="form-group">
					<label for="preferred_name">Preferred Name</label>
					<select id="preferred_name" name="preferred_name">
						{$preferredNameHtml}
					</select>
				</div>
				
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" name="title" id="title" value="{$writer->getTitle()}">
				</div>
				
				<div class="form-group">
					<label for="nickname">Nickname</label>
					<input type="text" name="nickname" id="nickname" value="{$writer->getNickname()}">
				</div>
				
				<div class="form-group">
					<label for="yob">Year of Birth</label>
					<input type="text" name="yob" id="yob" value="{$writer->getYearOfBirth()}">
				</div>
				
				<div class="form-group">
					<label for="yod">Year of Death</label>
					<input type="text" name="yod" id="yod" value="{$writer->getYearOfDeath()}">
				</div>
				
				{$this->_getDistrictsHtml($writer)}
				
				<div class="form-group">
					<label for="notes">Notes</label>
					<textarea id="notes" name="notes">{$writer->getNotes()}</textarea>
				</div>
				
				<input type="hidden" name="id" value="{$writer->getId()}">
				<button type="submit" class="btn btn-primary">save</button>
			</form>
HTML;
		echo $html;
	}

	private function _getDistrictsHtml($writer) {
		$districts = models\writers2::getDistrictInfo();
		$html = <<<HTML
			<div class="form-group">
				<label for="district_1_id">District 1</label>
				<select name="district_1_id">
					<option value="">-----</option>
HTML;
		foreach ($districts as $district) {
			$selected = "";
			if ($district["id"] == $writer->getDistrict1Id()) {
				$selected = "selected";
			}
			$html .= <<<HTML
				<option value="{$district["id"]}" {$selected}>{$district["name"]}</option>
HTML;
		}
		$html .= <<<HTML
				</select>
			</div>
			<div class="form-group">
				<label for="district_2_id">District 2</label>
				<select name="district_2_id">
					<option value="">-----</option>
HTML;
		foreach ($districts as $district) {
			$selected = "";
			if ($district["id"] == $writer->getDistrict2Id()) {
				$selected = "selected";
			}
			$html .= <<<HTML
				<option value="{$district["id"]}" {$selected}>{$district["name"]}</option>
HTML;
		}
		$html .= <<<HTML
				</select>
			</div>
HTML;
		return $html;
	}

	private function _getSurnameHtml($writer) {
		$snEN = $writer->getSurnameEN();
		$snGD = $writer->getSurnameGD();
		if ($snEN != "" && $snGD != "") {
			$sn = $snEN . " / " . $snGD;
		}
    else if ($snEN != "") {
			$sn = $snEN;
		}
		else {
			$sn = $snGD;
		}
		$html = <<<HTML
			<tr>
				<td>surname</td>
				<td>{$sn}</td>
			</tr>
HTML;
		return $html;
	}

	private function _getForenamesHtml($writer) {
		$fnEN = $writer->getForenamesEN();
		$fnGD = $writer->getForenamesGD();
		if ($fnEN != "" && $fnGD != "") {
			$fn = $fnEN . " / " . $fnGD;
		}
		else if ($fnEN != "") {
			$fn = $fnEN;
		}
		else {
			$fn = $fnGD;
		}
		$html = <<<HTML
			<tr>
				<td>forenames</td>
				<td>{$fn}</td>
			</tr>
HTML;
		return $html;
	}

	private function _getNicknameHtml($writer) {
		$html = "";
		if (empty($writer->getNickname())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>nickname</td>
					<td>{$writer->getNickname()}</td>
				</tr>
HTML;
		}
		return $html;
	}

	private function _getYearOfBirthHtml($writer) {
		$html = "";
		if (empty($writer->getYearOfBirth())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>birth</td>
					<td>{$writer->getYearOfBirth()}</td>
				</tr>
HTML;
		}
		return $html;
	}

	private function _getYearOfDeathHtml($writer) {
		$html = "";
		if (empty($writer->getYearOfDeath())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>death</td>
					<td>{$writer->getYearOfDeath()}</td>
				</tr>
HTML;
		}
		return $html;
	}

	private function _getDistrict1Html($writer) {
		$html = "";
		if (empty($writer->getDistrict1Id())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>origin</td>
					<td>{$writer->getDistrict1Id()}</td>
				</tr>
HTML;
		}
		return $html;
	}

	private function _getTextsHtml($writer) {
		$html = "";
		if ($texts = $writer->getTexts()) {
			$html = "<tr><td>works</td><td><div class='list-group list-group-flush'>";
			foreach ($texts as $text) {
				$html .= <<<HTML
					<div class="list-group-item list-group-item-action">
						<a href="?m=corpus&a=browse&id={$text->getId()}">
							{$text->getTitle()}
						</a>
					</div>
HTML;
			}
			$html .= "</div></td></tr>";
		}
		return $html;
	}
}
