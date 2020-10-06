<?php


namespace views;


class corpus_sql
{
	public function writeTable($texts) {
		echo <<<HTML
        <table class="table">
            <tbody>
HTML;
		foreach ($texts as $text) {
			$this->_writeRow($text);
		}
		echo <<<HTML
            </tbody>
        </table>
HTML;
	}

	private function _writeRow($text) {
		$writerHtml = $this->_formatWriters($text);
		echo <<<HTML
        <tr>
            <td>#{$text->getId()}</td>
            <td class="browseListTitle">
                <a href="?m=text&a=view&textId={$text->getId()}">{$text->getTitle()}</a>
            </td>
            <td>{$writerHtml}</td>
            <td>{$text->getDate()}</td>
        </tr>
HTML;
	}

	private function _formatWriters($text) {
		$writers = $text->getWriters();
		if (empty($writers)) {
			return;
		}
		$writerList = [];
		foreach ($writers as $writer) {
			$nickname = (empty($writer->getNickname())) ? "" : " (" . $writer->getNickname() . ")";
			$writerList[] = <<<HTML
            <a href="?m=writer&a=view&id={$writer->getId()}">
							{$writer->getForenamesGD()} {$writer->getSurnameGD()}
						</a> {$nickname}
HTML;

		}
		return implode(", ", $writerList);
	}
}