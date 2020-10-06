<?php


namespace views;

class writer_sql
{
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
}