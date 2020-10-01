<?php
namespace controllers;

require_once "includes/include.php";

$controller = new PrintSlip();
$controller->run("writePDF");


