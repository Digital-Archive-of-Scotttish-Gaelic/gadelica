<?php

require_once 'includes/htmlHeader2.php';

$html = <<<HTML
  <p><a href="index-hw.php">&lt;index</a></p>
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
			<small><a href="editLexeme.php?id={$result["id"]}" target="_new">[edit]</a></small>
			<ul>
HTML;
  $sql = <<<SQL
    SELECT  `form` ,  `morph` ,  `id`
      FROM  `forms`
      WHERE  `lexeme_id` = :lexemeId
SQL;
  $results2 = $db->fetch($sql, array(":lexemeId" => $result["id"]));
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
	$sql = <<<SQL
		SELECT  `en` ,  `id`
			FROM  `english`
			WHERE  `lexeme_id` = :lexemeId
SQL;
	$results2 = $db->fetch($sql, array(":lexemeId" => $result["id"]));
	if ($results2) {
		$html .= <<<HTML
				<li>Translations:
					<ul>
HTML;
		foreach ($results2 as $nextResult2) {
			$html .= "<li>" . $nextResult2["en"] . "</li>";
		}
		$html .= <<<HTML
					</ul>
				</li>
HTML;
	}
	$sql = <<<SQL
		SELECT  `note` ,  `id`
			FROM  `notes`
			WHERE  `lexeme_id` = :lexemeId
SQL;
	$results2 = $db->fetch($sql, array(":lexemeId" => $result["id"]));
	if ($results2) {
		$html .= <<<HTML
				<li>Notes:
					<ul>
HTML;
		foreach ($results2 as $nextResult2) {
			$html .= "<li>" . $nextResult2["note"] . "</li>";
		}
		$html .= <<<HTML
					</ul>
				</li>
HTML;
	}
	$html .= <<<HTML
			</ul>
		</li>
HTML;
}
$html .= <<<HTML
      <li><small><a target="_new" href="addLexeme.php?mhw={$_GET["mhw"]}&mpos={$_GET["mpos"]}&msub={$_GET["msub"]}">[add]</a></small></li>
    </ul>
HTML;


//Parts
$html .= <<<HTML
  <h5>Parts</h5>
  <ul>
HTML;
$sql = <<<SQL
	SELECT `m-p-hw`, `m-p-pos`, `m-p-sub`, `id`
		FROM `parts`
		WHERE `m-hw` = :mhw
		AND `m-pos` =  :mpos
		AND `m-sub` =  :msub
SQL;
$results = $db->fetch($sql, array(":mhw" => $_GET["mhw"], ":mpos" => $_GET["mpos"], ":msub" => $_GET["msub"]));
foreach ($results as $result) {
	$url = "viewMhw.php?mhw={$result["m-p-hw"]}&mpos={$result["m-p-pos"]}&msub={$result["m-p-sub"]}";
	$html .= <<<HTML
		<li><a href="{$url}">{$result["m-p-hw"]}</a> <em>{$result["m-p-pos"]}</em> 
			<small>
				<a target="_new" onclick="return confirm('Are you sure?');" href="deletePart.php?id={$result["id"]}">[delete]</a>
			</small>
		</li>
HTML;
}
$html .= <<<HTML
    <li>
      <small>
        <a target="_new" href="addPart.php?mhw={$_GET["mhw"]}&mpos={$_GET["mpos"]}&msub={$_GET["msub"]}">[add]</a>
      </small>
     </li>
	</ul>
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
if ($results) {
  $html .= <<<HTML
	<h5>Compounds</h5>
	<ul>
HTML;
  foreach ($results as $result) {
	  $url = "viewMhw.php?mhw={$result["m-hw"]}&mpos={$result["m-pos"]}&msub={$result["m-sub"]}";
	  $html .= <<<HTML
		  <li><a href="{$url}">{$result["m-hw"]}</a> <em>{$result["m-pos"]}</em></li>
HTML;
  }
  $html .= "</ul>";
}

echo $html;

require_once 'includes/htmlFooter2.php';
