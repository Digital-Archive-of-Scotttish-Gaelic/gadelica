<?php

session_start();

//TODO: consider relocating this SB
if (!$_SESSION["printSlips"]) {
  $_SESSION["printSlips"] = array();
}

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

//TODO: consider relocating this SB
if (!$_SESSION["printSlips"]) {
  $_SESSION["printSlips"] = array();
}

//constants
define("INPUT_FILEPATH", "../xml/");

define("DB", "corpas");
define("DB_HOST", "130.209.99.241");
define("DB_USER", "corpas");
define("DB_PASSWORD", "XmlCraobh2020");

/* autoload classes anonymous function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

?>
