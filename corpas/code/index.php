<?php
namespace controllers;

require_once "includes/htmlHeader.php";

$action = isset($_GET["a"]) ? $_GET["a"] : "";
$module = isset($_GET["m"]) ? $_GET["m"] : "";
$controller = null;

switch ($module) {
	case "corpus":
		$controller = new Corpus();
		break;
	case "text":
		$uri = $_GET["uri"];
		$controller = new Text($uri);
		break;
	case "writer":
		$controller = new Writer();
		break;
	case "search":
		$origin = "index.php?m=search";
		$controller = new CorpusSearch($origin);
		break;
	case "slips":
		$controller = new SlipBrowse();
		break;
	case "slip":
		$slipId = !empty($_GET["auto_id"]) ? $_GET["auto_id"] : false;
		$controller = new Slip($slipId);
		break;
	case "entries":
		$controller = new Entries();
		break;
	case "docs":
		$controller = new Documentation();
		break;
	default:
		$controller = new Index();
}

$controller->run($action);

require_once "includes/htmlFooter.php";

?>
