<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

$html = "<div class=\"list-group list-group-flush\">";
$query = <<<SQL
	SELECT DISTINCT  `m-hw` ,  `m-pos` ,  `m-sub`
		FROM  `lexemes`
		ORDER BY  `lexemes`.`m-hw` ASC
SQL;
$results = $db->fetch($query);
foreach ($results as $result) {
	$url = "viewMhw.php?mhw={$result["m-hw"]}&mpos={$result["m-pos"]}&msub={$result["m-sub"]}";
	$html .= <<<HTML
		<a href="{$url}" class="list-group-item list-group-item-action">{$result["m-hw"]}</a>
HTML;
}
$html .= "</div>";
echo $html;

require_once 'includes/htmlFooter2.php';
