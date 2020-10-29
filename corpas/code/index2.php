<?php
namespace controllers;

require_once "includes/htmlHeader.php"; // what do we think?

$module = isset($_GET["m"]) ? $_GET["m"] : ""; // this doesn't do anything!
$action = isset($_GET["a"]) ? $_GET["a"] : "";
$controller = null;

switch ($module) {
	case "corpus":
		$controller = new corpus2();
		$controller->run($action);
		break;
	/*
	// TO COME
	case "writers":
		$controller = new writers();
		break;
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
		$controller = new home2();
		$controller->run($action);
}

require_once "includes/htmlFooter.php"; // ditto
