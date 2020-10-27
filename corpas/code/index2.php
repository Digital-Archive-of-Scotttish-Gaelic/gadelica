<?php
namespace controllers;

require_once "includes/htmlHeader.php";

//$action = isset($_GET["a"]) ? $_GET["a"] : "";
$module = isset($_GET["m"]) ? $_GET["m"] : "";
//$controller = null;

switch ($module) {
	case "corpus":
		new corpus2();
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
		new index2();
}

//$controller->run($action);

require_once "includes/htmlFooter.php";

?>
