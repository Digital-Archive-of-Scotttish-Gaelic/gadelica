<?php

namespace controllers;

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
define("DB", "briathradan");
define("DB_HOST", "130.209.99.241");
define("DB_USER", "corpas");
define("DB_PASSWORD", "XmlCraobh2020");
//autoload classes
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$module = isset($_GET["m"]) ? $_GET["m"] : "";
$action = isset($_GET["a"]) ? $_GET["a"] : "";

echo <<<HTML
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
HTML;
  if ($_GET["search"]) {
		echo "<title>🏛 \"" . $_GET["search"] . "\" ??</title>";
	}
	else if ($_GET["mhw"]) {
		echo "<title>🏛 " . $_GET["mhw"] . "</title>";
	}
	else {
		echo "<title>🏛 Am Briathradan</title>";
	}


echo <<<HTML
	<style>
      td { width: 50%; }
	</style>
</head>
<body style="padding-top: 20px;">
  <div class="container-fluid">
HTML;

switch ($module) {
	case "entry":
		$controller = new entry(); // view entry
		break;
	default:
		$controller = new search(); // show search box
}
$controller->run($action);

echo <<<HTML
      <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
		    <a class="navbar-brand" href="index.php">🏛 Am Briathradan</a>
		    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			    <div class="navbar-nav">
				    <a class="nav-item nav-link" href="?m=entry&a=random" data-toggle="tooltip" title="View random entry">sonas</a>
			    </div>
		    </div>
	    </nav>
	  </div>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
	</body>
</html>
HTML;
