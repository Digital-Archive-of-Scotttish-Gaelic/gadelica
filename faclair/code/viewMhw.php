<?php

require_once 'includes/htmlHeader2.php';

$html = <<<HTML
	<h1>{$_GET["mhw"]} <em>{$_GET["mpos"]}</em></h1>
	<h5>Sources</h5>
	<ul>
HTML;
$db = new \models\database();
$sql = <<<SQL
	SELECT `id`, `source`, `hw`, `pos`, `sub`
		FROM `lexemes`
		WHERE `m-hw` = :mhw
		AND `m-pos` = :mpos
		AND `m-sub` = :msub
SQL;
$results = $db->fetch($sql, array(":mhw" => $_GET["mhw"], ":mpos" => $_GET["mpos"], ":msub" => $_GET["msub"]));
foreach ($results as $result) {
	$html .= <<<HTML
		<li>[{$result["source"]}] <strong>{$result["hw"]}</strong> <em>{$result["pos"]}</em>
			<small><a href="editHw.php?id={$result["id"]}">[edit]</a></small>
			<ul>
HTML;
  $sql = <<<SQL
    SELECT  `form` ,  `morph` ,  `id`
      FROM  `forms`
      WHERE  `source` =  {$result["source"]}
      AND  `hw` =  :hw
      AND  `pos` =  :pos
      AND  `sub` =  :sub
SQL;
  $results2 = $db->fetch($sql, array(":hw" => $result["hw"], ":pos" => $result["pos"], ":sub" => $result["sub"]));
	if ($results2) {
    $html .= <<<HTML
				<li>Forms:
					<ul>
HTML;
    foreach ($results2 as $nextResult2) {
  	  $html .= "<li>" . $nextResult2["form"] . " <em>" . $nextResult2["morph"] . "</em></li>";
    }
	  $html .= <<<HTML
					</ul>
				</li>
HTML;
  }
	$html .= <<<HTML
        <li>Translations:
					<ul>
						<li>[eng 1]</li>
						<li>[eng 2]</li>
						<li>[...]</li>
					</ul>
				</li>
				<li>Notes:
					<ul>
						<li>[note 1]</li>
						<li>[note 2]</li>
						<li>[...]</li>
					</ul>
				</li>
			</ul>
		</li>
HTML;
}
$html .= <<<HTML
    </ul>
    <h5>Parts</h5>
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
	<h5>Compounds</h5>
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

require_once 'includes/htmlFooter2.php';
