<?php
namespace controllers;

require_once "includes/htmlHeader.php";

$action = isset($_GET["a"]) ? $_GET["a"] : "";
$module = isset($_GET["m"]) ? $_GET["m"] : "";
$controller = null;

switch ($module) {
	case "search":
		$origin = "index.php?m=search";

		$controller = new CorpusSearch($origin);
		break;
	default:
		$controller = new Index();
}

$controller->run($action);

require_once "includes/htmlFooter.php";

?>
