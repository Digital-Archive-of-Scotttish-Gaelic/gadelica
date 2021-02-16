<?php

require_once 'includes/htmlHeader2.php';

$html = <<<HTML
  <p><a href="index-ss.php">&lt;index</a></p>
	<h1>{$_GET["id"]}</h1>
HTML;

$db = new \models\database();
$sql = <<<SQL
	SELECT `id`, `hw`, `pos`, `sub`
		FROM `lexemes`
		WHERE `source` = :source
SQL;
$results = $db->fetch($sql, array(":source" => $_GET["id"]));
foreach ($results as $result) {
  $html .= "<p>" . $result["hw"] . "</p>";
}

echo $html;

require_once 'includes/htmlFooter2.php';
