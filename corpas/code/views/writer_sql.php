<?php


namespace views;

class writer_sql
{
	/**
	 * Outputs a table of all writers
	 * @param $writers an array of models\writer objects
	 */
	public function listWriters($writers) {
		$html = "";
		foreach ($writers as $writer) {
			$html .= <<<HTML
				<tr>
					<td>{$writer->getForenamesGD()} {$writer->getSurnameGD()}</td>
					<td>{$writer->getNickname()}</td>
					<td>{$writer->getForenamesEN()} {$writer->getSurnameEN()}</td>
					<td>{$writer->getYearOfBirth()} - {$writer->getYearOfDeath()}</td>
					<td>{$writer->getOrigin()}</td>
				</tr>
HTML;
		}
		echo <<<HTML
			<table class="table">
				<thead>
					<tr>
						<th>Gaelic name</th>
						<th>Nickname</th>
						<th>English name</th>
						<th>Years</th>
						<th>Origin</th>
					</tr>
				</thead>
				<tbody>
					{$html}
				</tbody>
			</table>
HTML;
	}

	/**
	 * @param $writer an instance of models\writer
	 */
	public function printWriter($writer) {
		$html = <<<HTML
			<table class="table">
				<tbody>
					<tr>
						<td>firstname(s) (gaelic)</td>
						<td>{$writer->getForenamesGD()}</td>
					</tr>
					<tr>
						<td>surname (gaelic}</td>
						<td>{$writer->getSurnameGD()}</td>
					</tr>
					{$this->_getEnglishNamesHtml($writer)}
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

	private function _getEnglishNamesHtml($writer) {
		$html = "";
		if (empty($writer->getSurnameEN())) {
			return $html;
		} else {
			$html = <<<HTML
				<tr>
					<td>firstname(s) (english)</td>
					<td>{$writer->getForenamesEN()}</td>
				</tr>
				<tr>
					<td>firstname(s) (english)</td>
					<td>{$writer->getSurnameEN()}</td>
				</tr>
HTML;
		}
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
					<td>year of birth</td>
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
					<td>year of death</td>
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
			$html = "<tr><td>texts</td><td><ul>";
			foreach ($texts as $text) {
				$html .= <<<HTML
					<li>
						<a href="?m=text&a=view&textId={$text->getId()}">
							{$text->getTitle()}
						</a>
					</li>
HTML;
			}
			$html .= "</ul></td></tr>";
		}
		return $html;
	}
}