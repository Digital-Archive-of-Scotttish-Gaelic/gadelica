<?php

require_once 'includes/htmlHeader.php';

$db = new models\database();

$html = "<ul>";
$query = <<<SQL
	SELECT DISTINCT  `m-hw` ,  `m-pos` ,  `m-sub` 
		FROM  `lexemes` 
		ORDER BY  `lexemes`.`m-hw` ASC
SQL;
$results = $db->fetch($query);
foreach ($results as $result) {
	$url = "viewMhw.php?mhw={$result["m-hw"]}&mpos={$result["m-pos"]}&msub={$result["m-sub"]}";
	$html .= <<<HTML
		<li><a href="{$url}">{$result["m-hw"]}</a></li>
HTML;
}
$html .= "</ul>";
echo $html;

require_once 'includes/htmlFooter.php';