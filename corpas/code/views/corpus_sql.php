<?php


namespace views;


class corpus_sql
{
	public function writeTable($textsInfo) {
		echo <<<HTML
        <table class="table">
            <tbody>
HTML;
		foreach ($textsInfo as $textInfo) {
			$this->_writeRow($textInfo);
		}
		echo <<<HTML
            </tbody>
        </table>
HTML;
	}

	private function _writeRow($textInfo) {
		$writerHtml = $this->_formatWriters($textInfo);
		echo <<<HTML
        <tr>
            <td>#{$textInfo["id"]}</td>
            <td class="browseListTitle">
                <a href="?m=text&a=view&textId={$textInfo["id"]}">{$textInfo["title"]}</a>
            </td>
            <td>{$writerHtml}</td>
            <td>{$textInfo["date"]}</td>
        </tr>
HTML;
	}

	private function _formatWriters($textInfo) {
		$writersInfo = $textInfo["writers"];
		if (empty($writersInfo)) {
			return;
		}
		$writerList = [];
		foreach ($writersInfo as $writerInfo) {
			$nickname = (empty($writerInfo["nickname"])) ? "" : " (" . $writerInfo["nickname"] . ")";
			$writerList[] = <<<HTML
            <a href="?m=writer&a=view&writerId={$writerInfo["id"]}">
							{$writerInfo["forenames_gd"]} {$writerInfo["surname_gd"]}
						</a> {$nickname}
HTML;

		}
		return implode(", ", $writerList);
	}
}