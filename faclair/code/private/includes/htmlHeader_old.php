<?php

require_once "include.php";

$searchTerm = isset($_GET['searchTerm']) ? $_GET["searchTerm"] : "";
$gd = isset($_GET["gd"]) ? $_GET['gd'] : "";
$query = $_SERVER['QUERY_STRING'];
$snh = false;
$frp = false;
$seotal = false;
if (strpos($query,'lex=snh')>-1) { $snh = true; }
if (strpos($query,'lex=frp')>-1) { $frp = true; }
if (strpos($query,'lex=seotal')>-1) { $seotal = true; }

$gdChecked = $gd !="yes" ? "" : "checked";
$enChecked = $gdChecked ? "" : "checked";
$snhChecked = $snh || $searchTerm=='' ? "checked" : "";
$frpChecked = $frp || $searchTerm=='' ? "checked" : "";
$seotalChecked = $seotal || $searchTerm=='' ? "checked" : "";
$searchAutofocus = $searchTerm ? "" : "autofocus";
$searchTermHtml = $searchTerm ? 'value="' . $searchTerm . '"' : "";


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
	<title>Briathradan</title>
	<style>
      td { width: 50%; }
	</style>
</head>
<body style="padding-top: 20px;">
<div class="container-fluid">
	<form action="index.php" method="get" autocomplete="off" id="searchForm"> <!-- Search box -->
		<div class="form-group">
			<div class="input-group">
				<input id="searchBox" type="text" class="form-control active" name="searchTerm" data-toggle="tooltip" title="Enter search term here" autofocus="{$searchAutofocus}" $searchTermHtml>
				<div class="input-group-append">
					<button id="searchButton" class="btn btn-primary" type="submit" data-toggle="tooltip" title="Click to find entries">Siuthad</button>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Enter English term">
				<input class="form-check-input" type="radio" name="gd" id="enRadio" value="no" {$enChecked}>
				<label class="form-check-label" for="enRadio">Beurla</label>
			</div>
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Enter Gaelic term">
				<input class="form-check-input" type="radio" name="gd" id="gdRadio" value="yes" {$gdChecked}>
				<label class="form-check-label" for="gdRadio">Gàidhlig</label>
			</div>
		</div>
		<div class="form-group">
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Search Scottish Natural Heritage nature terms">
				<input class="form-check-input" type="checkbox" name="lex" id="snhCheck" value="snh" {$snhChecked}>
				<label class="form-check-label" for="snhCheck">Faclan Nàdair</label>
			</div>
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Search the Scottish Parliament dictionary and related resources">
				<input class="form-check-input" type="checkbox" name="lex" id="frpCheck" value="frp" {$frpChecked}>
				<label class="form-check-label" for="frpCheck">Faclair na Pàrlamaid</label>
			</div>
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Search Stòrlann’s terms for use in GME">
				<input class="form-check-input" type="checkbox" name="lex" id="seotalCheck" value="seotal" {$seotalChecked}>
				<label class="form-check-label" for="seotalCheck">Seotal</label>
			</div>
			<!--
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Search Dwelly">
				<input class="form-check-input" type="checkbox" name="dwelly" id="dwellyCheck" value="yes" checked>
				<label class="form-check-label" for="dwellyCheck">Dwelly</label>
			</div>
			<div class="form-check form-check-inline" data-toggle="tooltip" title="Search other resources">
				<input class="form-check-input" type="checkbox" name="others" id="othersCheck" value="yes" checked>
				<label class="form-check-label" for="othersCheck">Eile</label>
			</div>
			-->
		</div>
	</form>
	<table class="table table-hover" id="resultsTable">
		<tbody>
		</tbody>
	</table>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
HTML;


