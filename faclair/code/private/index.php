<?php

namespace controllers;

require_once 'includes/htmlHeader.php';

$module = isset($_GET["m"]) ? $_GET["m"] : "";
$action = isset($_GET["a"]) ? $_GET["a"] : "";

switch ($module) {
	case "entry":
		$controller = new entry(); // view entry
		break;
	case "sources":
		$controller = new sources();
		break;
	case "source":
		$controller = new source();
		break;
	case "entry_instance":
		$controller = new entry_instance();
		break;
	case "englishes":
		$controller = new englishes();
		break;
	case "english":
		$controller = new english();
		break;
	case "admin":
		$controller = new admin();
		break;
	default:
		$controller = new search(); // show search box
}

$controller->run($action);

require_once "includes/htmlFooter.php";
