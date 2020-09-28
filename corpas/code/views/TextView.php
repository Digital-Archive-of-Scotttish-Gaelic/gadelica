<?php

class TextView {

    public function __construct($textModel) {
        echo <<<HTML
            <tr>
                <td><a href="index2.php?module=viewText&&id={$textModel->getId()}">{$textModel->getId()}</a></td>
                <td>
                    <table class="table">
                        <tbody>
HTML;
        foreach ($textModel->getSubTextModels() as $nextSubTextModel) {
          new TextView($nextSubTextModel);
        }
        echo <<<HTML
                        </tbody>
                    </table>
                </td>
                <!--
                <td class="browseListTitle">
                    <a href="viewText.php?uri={$result["textUri"]}">{$result["title"]}</a>
                </td>
                <td>{$writerHtml}</td>
                <td>{$result["date"]}</td>
              -->
            </tr>
HTML;
    }


/*
    private function _writeRow($rank, $result) {
        $writerHtml = $this->_formatWriters($result["writer"]);
        echo <<<HTML
            <tr>
                <td>#{$rank}</td>
                <td class="browseListTitle">
                    <a href="viewText.php?uri={$result["textUri"]}">{$result["title"]}</a>
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
                    <a href="viewWriter.php?uri={$writer["writerUri"]}">{$writer["forenames"]} {$writer["surname"]}</a> {$nickname}
HTML;
            }
        }
        return implode(", ", $writerList);
    }
*/


}
