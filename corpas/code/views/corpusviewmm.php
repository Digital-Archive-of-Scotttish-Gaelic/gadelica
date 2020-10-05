<?php

namespace views;

class CorpusView {

    public function __construct($corpusModel) {
        echo <<<HTML
            <table class="table">
                <tbody>
HTML;
        foreach ($corpusModel->getTextModels() as $nextTextModel) {
            new TextView($nextTextModel);
        }
        echo <<<HTML
                </tbody>
            </table>
HTML;
    }

}
