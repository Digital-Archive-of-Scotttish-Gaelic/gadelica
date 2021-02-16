<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

if (isset($_GET["a"]) && $_GET["a"] == "save") {

	$sql = <<<SQL
		UPDATE lexemes 
			SET `hw` = :hw, `pos` = :pos, `sub` = :sub, `m-hw` = :mhw, `m-pos` = :mpos, `m-sub` = :msub 
			WHERE id = :id
SQL;

	$db->exec($sql, array(":hw"=>$_GET["hw"], ":pos"=>$_GET["pos"], ":sub"=>$_GET["sub"], ":mhw"=>$_GET["mhw"],
		":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"], ":id"=>$_GET["id"]));

	die( "<h2>Saved</h2>" );
}

$sql = <<<SQL
	SELECT * FROM lexemes
		WHERE id = :id
SQL;
$results = $db->fetch($sql, array(":id"=>$_GET["id"]));
$result = $results[0];

$html = <<<HTML
	<form method="get">
		<div class="form-group">
			<label for="id">ID</label>
			<input type="text" id="id" disabled value="{$result["id"]}">
			<input type="hidden" name="id" value="{$result["id"]}">
		</div>
		<div class="form-group">
			<label for="source">source</label>
			<input type="text" name="source" id="source" disabled value="{$result["source"]}">
		</div>
		<div class="form-group">
			<label for="headword">hw</label>
			<input type="text" name="hw" id="hw" value="{$result["hw"]}">
		</div>
		<div class="form-group">
			<label for="pos">pos</label>
			<input type="text" name="pos" id="pos" value="{$result["pos"]}">
		</div>
		<div class="form-group">
			<label for="sub">sub</label>
			<input type="text" name="sub" id="sub" value="{$result["sub"]}">
		</div>
		<div class="form-group">
			<label for="mhw">m-hw</label>
			<input type="text" name="mhw" id="mhw" value="{$result["m-hw"]}">
		</div>
		<div class="form-group">
			<label for="mpos">m-pos</label>
			<input type="text" name="mpos" id="mpos" value="{$result["m-pos"]}">
		</div>
		<div class="form-group">
			<label for="msub">m-sub</label>
			<input type="text" name="msub" id="msub" value="{$result["m-sub"]}">
		</div>
HTML;

/*
$html .= "<h2>Forms –</h2>";
$sql = <<<SQL
	SELECT * FROM forms 
		WHERE source = :source AND hw = :hw AND pos = :pos AND sub = :sub 
SQL;
$formResults = $db->fetch($sql, array(":source"=>$result["source"], ":hw"=>$result["hw"],
	":pos"=>$result["pos"], ":sub"=>$result["sub"]));
foreach ($formResults as $form) {
	$html .= <<<HTML
		<div class="form-group">
			<label for="form">form</label>
			<input type="text" name="form" id="form" value="{$form["form"]}">
		</div>
		<div class="form-group">
			<label for="morph">morph</label>
			<input type="text" name="morph" id="morph" value="{$form["morph"]}">
		</div>
HTML;
}

$html .= "<h2>Translations –</h2>";
$sql = <<<SQL
	SELECT * FROM english 
		WHERE source = :source AND hw = :hw AND pos = :pos AND sub = :sub 
SQL;
$englishResults = $db->fetch($sql, array(":source"=>$result["source"], ":hw"=>$result["hw"],
	":pos"=>$result["pos"], ":sub"=>$result["sub"]));
foreach($englishResults as $english) {
	$html .= <<<HTML
		<div class="form-group">
			<label for="en">en</label>
			<input type="text" name="en" id="" value="{$english["en"]}">
		</div>
HTML;
}


$html .= "<h2>Notes –</h2>";
$sql = <<<SQL
	SELECT * FROM notes 
		WHERE source = :source AND hw = :hw AND pos = :pos AND sub = :sub 
SQL;
$notesResults = $db->fetch($sql, array(":source"=>$result["source"], ":hw"=>$result["hw"],
	":pos"=>$result["pos"], ":sub"=>$result["sub"]));
foreach ($notesResults as $note) {
	$html .= <<<HTML
		<div class="form-group">
			<label for="note">note</label>
			<input type="text" name="note" id="note" value="{$note["note"]}">
		</div>
HTML;
}
*/

$html .= <<<HTML
		<div>
			<input type="hidden" name="a" value="save">
			<input type="submit" class="btn btn-primary" value="save"></input>
			<button class="btn btn-secondary">cancel</button>
		</div>
	</form>
HTML;

echo $html;

require_once 'includes/htmlFooter2.php';