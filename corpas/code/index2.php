<?php
namespace controllers;

require_once "includes/htmlHeader.php";

//$action = isset($_GET["a"]) ? $_GET["a"] : "";
$module = isset($_GET["m"]) ? $_GET["m"] : "";
$controller = null;

switch ($module) {
	case "corpus":
		$controller = new corpus2();
		break;

	/*
	case "text":
		$textId = $_GET["textId"];
		$controller = new text_sql($textId);
		break;
	case "writer":
		$controller = new writer_sql();
		break;
	case "search":
		$origin = "index.php?m=search";
		$controller = new corpussearch($origin);
		break;
	case "slips":
		$controller = new slipbrowse();
		break;
	case "slip":
		$slipId = !empty($_GET["auto_id"]) ? $_GET["auto_id"] : false;
		$controller = new slip($slipId);
		break;
	case "entries":
		$controller = new entries();
		break;
	case "docs":
		$controller = new documentation();
		break;
	*/
	default:
		$controller = new index2();
}

//$controller->run($action);

require_once "includes/htmlFooter.php";

?>
