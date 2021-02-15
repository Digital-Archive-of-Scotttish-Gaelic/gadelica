<?php

require_once 'includes/htmlHeader.php';

$html = <<<HTML
	<h2>{$_GET["mhw"]} {$_GET["mpos"]}</h2>
	<ul>
HTML;
$db = new \models\database();
$sql = <<<SQL
	SELECT `id`, `source`, `hw`, `pos` 
		FROM `lexemes` 
		WHERE `m-hw` = :mhw
		AND `m-pos` = :mpos
		AND `m-sub` = :msub
SQL;
$results = $db->fetch($sql, array(":mhw" => $_GET["mhw"], ":mpos" => $_GET["mpos"], ":msub" => $_GET["msub"]));
foreach ($results as $result) {
	$html .= <<<HTML
		<li>{$result["id"]} {$result["source"]} {$result["hw"]} {$result["hw"]}</li>
HTML;
}
$html .= <<<HTML
    </ul>
    <h3>Parts –</h3>
    <ul>
HTML;

//Parts
$sql = <<<SQL
	SELECT `m-p-hw`, `m-p-pos`, `m-p-sub` 
		FROM `parts` 
		WHERE `m-hw` = :mhw
		AND `m-pos` =  :mpos
		AND `m-sub` =  :msub
SQL;
$results = $db->fetch($sql, array(":mhw" => $_GET["mhw"], ":mpos" => $_GET["mpos"], ":msub" => $_GET["msub"]));
foreach ($results as $result) {
	$url = "viewMhw.php?mhw={$result["m-p-hw"]}&mpos={$result["m-p-pos"]}&msub={$result["m-p-sub"]}";
	$html .= <<<HTML
		<li><a href="{$url}">{$result["m-p-hw"]}</a> {$result["m-p-pos"]} {$result["m-p-sub"]}</li>
HTML;
}
$html .= <<<HTML
	</ul>
	<h3>Compounds –</h3>
	<ul>
HTML;

//Compounds
$sql = <<<SQL
	SELECT `m-hw`, `m-pos`, `m-sub` 
		FROM `parts` 
		WHERE `m-p-hw` = :mhw
		AND `m-p-pos` = :mpos
		AND `m-p-sub` = :msub
SQL;
$results = $db->fetch($sql, array(":mhw" => $_GET["mhw"], ":mpos" => $_GET["mpos"], ":msub" => $_GET["msub"]));
foreach ($results as $result) {
	$url = "viewMhw.php?mhw={$result["m-hw"]}&mpos={$result["m-pos"]}&msub={$result["m-sub"]}";
	$html .= <<<HTML
		<li><a href="{$url}">{$result["m-hw"]}</a> {$result["m-pos"]} {$result["m-sub"]}</li>
HTML;
}
$html .= "</ul>";
echo $html;

require_once 'includes/htmlFooter.php';