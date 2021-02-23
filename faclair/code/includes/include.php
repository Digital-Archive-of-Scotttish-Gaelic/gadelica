<?php
session_start();

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

define("DB", "briathradan");
define("DB_HOST", "130.209.99.241");
define("DB_USER", "corpas");
define("DB_PASSWORD", "XmlCraobh2020");

//autoload classes
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$isSuperuser = isset($_SESSION["email"])
	&& ($_SESSION["email"] == "Mark.McConville@glasgow.ac.uk" || $_SESSION["email"] == "stephen.barrett@glasgow.ac.uk");
define("SUPERUSER", $isSuperuser);