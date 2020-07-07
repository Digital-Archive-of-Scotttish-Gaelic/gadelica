<?php


class BrowseCorpusView
{
  public function writeTable($results) {
    echo <<<HTML
        <table class="table">
            <tbody>
HTML;
    foreach ($results as $rank => $result) {
      $this->_writeRow($rank, $result);
    }
    echo <<<HTML
            </tbody>
        </table>
HTML;
  }

  private function _writeRow($rank, $result) {
    $writerHtml = $this->_formatWriters($result["writer"]);
    echo <<<HTML
        <tr>
            <td>#{$rank}</td>
            <td class="browseListTitle">
                <a href="viewText2.php?uri={$result["textUri"]}">{$result["title"]}</a>
            </td>
            <td>{$writerHtml}</td>
            <td>{$result["date"]}</td>
        </tr>
HTML;

  }

  private function _formatWriters($writers) {
    if (!isset($writers)) {
      return;
    }
    $writerList = [];
    foreach ($writers as $index => $writer) {
      if (empty($writer["writerUri"])) {
        $writerList[$index] = $writer["surname"];
      } else {
        $nickname = (empty($writer["nickname"])) ? "" : " (" . $writer["nickname"] . ")";
        $writerList[$index] = <<<HTML
            <a href="viewWriter2.php?uri={$writer["writerUri"]}">{$writer["forenames"]} {$writer["surname"]}</a> {$nickname}
HTML;
      }
    }
    return implode(", ", $writerList);
  }

}