<?php
namespace controllers;

require_once "includes/htmlHeader.php";

$module = isset($_GET["m"]) ? $_GET["m"] : ""; // this doesn't do anything surely
$action = isset($_GET["a"]) ? $_GET["a"] : "";

switch ($module) {
	case "corpus":
		$controller = new corpus();
		$controller->run($action);
		break;
	case "writers":
		$controller = new writers();
		$controller->run($action);
		break;
	case "districts":
		$controller = new districts();
		$controller->run($action);
		break;
	case "collection":
		$controller = new collection(); // START HERE
		$controller->run($action);
		break;
  /*
	case "dictionary":
		$controller = new dictionary();
		break;
	case "documentation":
		$controller = new documentation();
		break;
	*/
	case "slips":
		$controller = new slipbrowse();
		$controller->run($action);
		break;
	case "slip":
		$slipId = !empty($_GET["auto_id"]) ? $_GET["auto_id"] : false;
		$controller = new slip($slipId);
		$controller->run($action);
		break;
	case "entries":
		$controller = new entries();
		$controller->run($action);
		break;
	/**
	**/
	default:
		$controller = new home();
		$controller->run($action);
}

require_once "includes/htmlFooter.php"; // ditto
