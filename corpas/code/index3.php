<?php
namespace controllers;

require_once "includes/htmlHeader.php";

$param = isset($_GET["p"]) ? $_GET["p"] : "";
$module = isset($_GET["m"]) ? $_GET["m"] : "";
$controller = null;

switch ($module) {
	case "corpus":
		$controller = new corpus3();
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
		$controller = new index3();
}

$controller->run($param);

require_once "includes/htmlFooter.php";
