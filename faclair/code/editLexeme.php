<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

/**
 * Save the form data on submit
 */
if (isset($_GET["a"]) && $_GET["a"] == "save") {

	//save the lexeme data
	$sql = <<<SQL
		UPDATE lexemes 
			SET `hw` = :hw, `pos` = :pos, `sub` = :sub, `m-hw` = :mhw, `m-pos` = :mpos, `m-sub` = :msub 
			WHERE id = :id
SQL;
	$db->exec($sql, array(":hw"=>$_GET["hw"], ":pos"=>$_GET["pos"], ":sub"=>$_GET["sub"], ":mhw"=>$_GET["mhw"],
		":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"], ":id"=>$_GET["id"]));

	//save the forms
	if (!empty($_GET["form"])) {
		//delete forms
		if (!empty($_GET["delete-form"])) {
			foreach ($_GET["delete-form"] as $formId => $value) {
				$sql = "DELETE FROM `forms` WHERE id = :formId";
				$db->exec($sql, array(":formId" => $formId));
				unset($_GET["form"][$formId]);
				unset($_GET["morph"][$formId]);
			}
		}
		//insert a new form
		if (isset($_GET["form"][0]) && $_GET["form"][0] != "") {
			$sql = <<<SQL
				INSERT INTO `forms` (`form`, `morph`, `lexeme_id`) VALUES (:form, :morph, :lexemeId)
SQL;
			$db->exec($sql, array(":form" => $_GET["form"][0], ":morph" => $_GET["morph"][0], ":lexemeId" => $_GET["id"]));
			unset($_GET["form"][0]);
			unset($_GET["morph"][0]);
		}
		//update existing forms
		foreach ($_GET["form"] as $id => $value) {
			$sql = <<<SQL
				UPDATE forms SET `form` = :form, `morph` = :morph
					WHERE id = :id
SQL;
			$db->exec($sql, array(":form" => $value, ":morph" => $_GET["morph"][$id], ":id" => $id));
		}
	}

	//save the translation data
	if (!empty($_GET["en"])) {
		//delete translations
		if (!empty($_GET["delete-en"])) {
			foreach ($_GET["delete-en"] as $enId => $value) {
				$sql = "DELETE FROM `english` WHERE id = :enId";
				$db->exec($sql, array(":enId" => $enId));
				unset($_GET["en"][$enId]);
			}
		}
		//insert a new translation
		if (isset($_GET["en"][0]) && $_GET["en"][0] != "") {
			$sql = <<<SQL
				INSERT INTO `english` (`en`, `lexeme_id`) VALUES (:en, :lexemeId)
SQL;
			$db->exec($sql, array(":en" => $_GET["en"][0], ":lexemeId" => $_GET["id"]));
			unset($_GET["en"][0]);
		}
		//update existing translations
		foreach ($_GET["en"] as $id => $value) {
			$sql = <<<SQL
				UPDATE english SET `en` = :en
					WHERE id = :id
SQL;
			$db->exec($sql, array(":en" => $value, ":id" => $id));
		}
	}

	//save the note data
	if (!empty($_GET["note"])) {
		//delete notes
		if (!empty($_GET["delete-note"])) {
			foreach ($_GET["delete-note"] as $noteId => $value) {
				$sql = "DELETE FROM `notes` WHERE id = :noteId";
				$db->exec($sql, array(":noteId" => $noteId));
				unset($_GET["note"][$noteId]);
			}
		}
		//insert a new note
		if (isset($_GET["note"][0]) && $_GET["note"][0] != "") {
			$sql = <<<SQL
				INSERT INTO `notes` (`note`, `lexeme_id`) VALUES (:note, :lexemeId)
SQL;
			$db->exec($sql, array(":note" => $_GET["note"][0], ":lexemeId" => $_GET["id"]));
			unset($_GET["note"][0]);
		}
		//update existing notes
		foreach ($_GET["note"] as $id => $value) {
			$sql = <<<SQL
				UPDATE notes SET `note` = :note
					WHERE id = :id
SQL;
			$db->exec($sql, array(":note" => $value, ":id" => $id));
		}
	}

	die( "<h2>Saved</h2>" );
}

/**
 * Write the form and form data
 */
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

// -- Forms
$html .= "<h2>Forms –</h2>";
$sql = <<<SQL
	SELECT * FROM forms WHERE lexeme_id = :lexemeId
SQL;

$formResults = $db->fetch($sql, array(":lexemeId" => $_GET["id"]));
foreach ($formResults as $form) {
	$id = $form["id"];
	$html .= <<<HTML
		<div class="form-group">
			<label for="form{$id}">form</label>
			<input type="text" name="form[{$id}]" id="form{$id}" value="{$form["form"]}">
			<label for="delete-form{$id}">delete</label>
			<input type="checkbox" id="delete-form[{$id}]" name="delete-form[{$id}]">
		</div>
		<div class="form-group">
			<label for="morph{$id}">morph</label>
			<input type="text" name="morph[{$id}]" id="morph{$id}" value="{$form["morph"]}">
		</div>
HTML;
}
$html .= <<<HTML
		<button type="button" class="btn btn-success add-form">add</button>
		<div class="new-form">
			<div class="form-group">
		    <label for="form0">form</label>
				<input type="text" name="form[0]" id="form0">
			</div>
			<div class="form-group">
				<label for="morph0">morph</label>
				<input type="text" name="morph[0]" id="morph0">
			</div>
		</div>  
HTML;


// -- Translations
$html .= "<h2>Translations –</h2>";
$sql = <<<SQL
	SELECT * FROM english WHERE lexeme_id = :lexemeId 
SQL;
$englishResults = $db->fetch($sql, array(":lexemeId" => $_GET["id"]));
foreach($englishResults as $english) {
	$id = $english["id"];
	$html .= <<<HTML
		<div class="form-group">
			<label for="en{$id}">en</label>
			<input type="text" name="en[{$id}]" id="en{$id}" value="{$english["en"]}">
			<label for="delete-en{$id}">delete</label>
			<input type="checkbox" id="delete-en[{$id}]" name="delete-en[{$id}]">
		</div>
HTML;
}
$html .= <<<HTML
		<button type="button" class="btn btn-success add-en">add</button>
		<div class="new-en">
			<div class="form-group">
		    <label for="en0">en</label>
				<input type="text" name="en[0]" id="en0">
			</div>
		</div>  
HTML;

// -- Notes
$html .= "<h2>Notes –</h2>";
$sql = <<<SQL
	SELECT * FROM notes WHERE lexeme_id = :lexemeId  
SQL;
$notesResults = $db->fetch($sql, array(":lexemeId" => $_GET["id"]));
foreach ($notesResults as $note) {
	$id = $note["id"];
	$html .= <<<HTML
		<div class="form-group">
			<label for="note{$id}">note</label>
			<textarea name="note[{$id}]" id="note{$id}">{$note["note"]}</textarea>
			<label for="delete-note{$id}">delete</label>
			<input type="checkbox" id="delete-note[{$id}]" name="delete-note[{$id}]">
		</div>
HTML;
}
$html .= <<<HTML
		<button type="button" class="btn btn-success add-note">add</button>
		<div class="new-note">
			<div class="form-group">
		    <label for="note0">note</label>
				<textarea name="note[0]" id="note0"></textarea>
			</div>
		</div>  
HTML;


$html .= <<<HTML
		<div>
			<input type="hidden" name="a" value="save">
			<input type="submit" class="btn btn-primary" value="save"></input>
			<button class="btn btn-secondary">cancel</button>
		</div>
	</form>

<script>
	$(function () {
	  $('.new-form').hide();
	  $('.new-en').hide();
	  $('.new-note').hide();
	  $('.add-form').on('click', function () {
	    $('.add-form').hide();
	    $('.new-form').show();
	  });
	  $('.add-en').on('click', function () {
	    $('.add-en').hide();
	    $('.new-en').show();
	  });
	  $('.add-note').on('click', function () {
	    $('.add-note').hide();
	    $('.new-note').show();
	  });
	});
</script>
HTML;

echo $html;

require_once 'includes/htmlFooter2.php';