<?php

require_once "includes/htmlHeader.php";

include_once 'classes/controllers/BrowseCorpusController2.php';
include_once 'classes/controllers/SearchCorpusController.php';
include_once 'classes/models/BrowseCorpusModel.php';
include_once 'classes/models/SearchCorpusModel.php';
include_once 'classes/views/BrowseCorpusView2.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$module = isset($_GET['module']) ? $_GET['module'] : '';
//$id =

switch($module) {
    case 'browseCorpus':
        $controller = new BrowseCorpusController2();
        break;
    case 'searchCorpus':
        $controller = new SearchCorpusController();
        break;
    default:
      echo <<<HTML
        <div class="list-group list-group-flush">
          <a class="list-group-item list-group-item-action" href="index2.php?module=browseCorpus">browse corpus</a>
          <a class="list-group-item list-group-item-action" href="index2.php?module=searchCorpus&action=newSearch">search corpus</a>
        </div>
HTML;
}

require_once "includes/htmlFooter.php";

?>
