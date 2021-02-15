<?php

namespace controllers;

require_once 'includes/htmlHeader.php';

$module = isset($_GET["m"]) ? $_GET["m"] : "";
$action = isset($_GET["a"]) ? $_GET["a"] : "";

switch ($module) {
	default:
		$controller = new home();
}

$controller->run($action);

require_once "includes/htmlFooter.php";
