<?php

namespace controllers;

require_once 'includes/htmlHeader2.php';

$module = isset($_GET["m"]) ? $_GET["m"] : "";
$action = isset($_GET["a"]) ? $_GET["a"] : "";

switch ($module) {
	case "entries":
		$controller = new entries();
		break;
	case "entry":
		$controller = new entry();
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
		$controller = new home();
}

$controller->run($action);

require_once "includes/htmlFooter2.php";
