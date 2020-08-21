<?php

require_once "include.php";

$name = "";
//check login state
$loggedInHide = "hide";


if ($_SESSION["user"] || ($_SESSION["email"] && $_POST["loginAction"] == "savePassword")) {

	$user = Users::getUser($_SESSION["email"]);
	$name = $user->getFirstName() . ' ' . $user->getLastName();
	$loggedInHide = "";
}

echo <<<HTML

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/simplePagination.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.17.1/dist/bootstrap-table.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <title>Aidhleags</title>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
	<script src="https://unpkg.com/bootstrap-table@1.17.1/dist/bootstrap-table.min.js"></script>
	<script src="https://cdn.ckeditor.com/4.14.1/basic/ckeditor.js"></script>
	<script src="https://kit.fontawesome.com/0b481d2098.js" crossorigin="anonymous"></script>
</head>
<body style="padding-top: 80px;">
  <div class="container-fluid">
    <nav class="navbar navbar-dark bg-primary fixed-top navbar-expand-lg">
      <a class="navbar-brand" href="index.php">Aidhleags</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link" title="browse corpus" href="browseCorpus.php">browse</a>
          <a class="nav-item nav-link" title="search corpus" href="search.php?action=newSearch">search</a>
          <a class="nav-item nav-link" title="browse slips" href="slipBrowse.php">slips</a>
          <a class="nav-item nav-link" title="browse entries" href="entries.php">entries</a>
					<a class="nav-item nav-link" title="read the fucking manual" href="docs.php">docs</a>
          <span class="loggedIn {$loggedInHide}">
            <form method="post">
              <button id="logoutLink" class="btn btn-link nav-link nav-item" role="link" type="submit" name="loginAction" value="logout">logout</button>
            </form>  
					</span>
        </div>
        <div class="navbar-nav ml-auto">
          <span class="loggedIn {$loggedInHide}">
            <a id="loggedInAs" class="nav-link disabled" href="#">logged in as {$name}</a>
          </span>       
        </div>
      </div>
    </nav>
HTML;

$loginControl = new LoginController();
$loginControl->runAction();
