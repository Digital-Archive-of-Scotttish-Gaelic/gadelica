<?php

namespace views;

class CorpusView {

    public function __construct($corpusModel) {
        echo <<<HTML
            <table class="table">
                <tbody>
HTML;
        foreach ($corpusModel->textModels as $nextTextModel) {
            new TextView($nextTextModel);
        }
        echo <<<HTML
                </tbody>
            </table>
HTML;
    }

}


?>
