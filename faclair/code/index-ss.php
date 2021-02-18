<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

$html = "<div class=\"list-group list-group-flush\">";
$query = <<<SQL
	SELECT DISTINCT  `source`
		FROM  `lexemes`
		ORDER BY  `lexemes`.`source` ASC
SQL;
$results = $db->fetch($query);
foreach ($results as $result) {
	$url = "viewS.php?id={$result["source"]}";
	$html .= <<<HTML
		<a href="{$url}" class="list-group-item list-group-item-action">{$result["source"]}</a>
HTML;
}
$html .= "<small><a href=\"#\">[add]</a></small>";
$html .= "</div>";
echo $html;

require_once 'includes/htmlFooter2.php';
