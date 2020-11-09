<?php
namespace controllers;

require_once "includes/htmlHeader.php"; // what do we think?

$module = isset($_GET["m"]) ? $_GET["m"] : ""; // this doesn't do anything surely
$action = isset($_GET["a"]) ? $_GET["a"] : "";
//$controller = null;

switch ($module) {
	case "corpus":
		$controller = new corpus();
		$controller->run($action);
		break;
	case "writers":
		$controller = new writers();
		$controller->run($action);
		break;
	/*
	// TO COME
	case "collection":
		$controller = new collection();
		break;
	case "dictionary":
		$controller = new dictionary();
		break;
	case "documentation":
		$controller = new documentation();
		break;
	*/
	default:
		$controller = new home();
		$controller->run($action);
}

require_once "includes/htmlFooter.php"; // ditto
