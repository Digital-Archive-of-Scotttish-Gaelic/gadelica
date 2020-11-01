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
						{$this->_getOriginHtml($writer)}
						{$this->_getTextsHtml($writer)}
					</tbody>
				</table>
HTML;
			echo $html;
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

	private function _getOriginHtml($writer) {
		$html = "";
		if (empty($writer->getOrigin())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>origin</td>
					<td>{$writer->getOrigin()}</td>
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
