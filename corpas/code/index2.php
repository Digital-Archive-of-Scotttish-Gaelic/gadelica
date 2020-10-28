<?php
namespace controllers;

require_once "includes/htmlHeader.php";

$module = isset($_GET["m"]) ? $_GET["m"] : ""; // this doesn't do anything!
$action = isset($_GET["a"]) ? $_GET["a"] : "";
$controller = null;

switch ($module) {
	case "corpus":
		$controller = new corpus2();
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
		$controller = new index2();
}

$controller->run($action);

require_once "includes/htmlFooter.php";
