<?php

require_once "includes/htmlHeader.php";

//TODO: change the script name from searchAjax.php when going live SB
$origin = $_GET["origin"] ? $_GET["origin"] : "searchAjax.php";   //originating script for back link

$controller = new CorpusSearchController($origin);

require_once "includes/htmlFooter.php";

