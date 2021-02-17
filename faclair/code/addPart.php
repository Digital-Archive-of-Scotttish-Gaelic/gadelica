<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

/**
 * Save the form data on submit
 */
if (isset($_GET["a"]) && $_GET["a"] == "save") {

	//save the lexeme data
	$sql = <<<SQL
		INSERT INTO parts (`m-hw`, `m-pos`, `m-sub`, `m-p-hw`, `m-p-pos`, `m-p-sub`)
			VALUES(:mhw, :mpos, :msub, :mphw, :mppos, :mpsub);
SQL;
	$db->exec($sql, array(":mhw"=>$_GET["mhw"], ":mpos"=>$_GET["mpos"], ":msub"=>$_GET["msub"],
		":mphw"=>$_GET["mphw"], ":mppos"=>$_GET["mppos"], ":mpsub"=>$_GET["mpsub"]));

	$html = <<<HTML
		<h2>Saved</h2>
		<script>
			window.opener.document.location.reload(true); //reload parent page
			setTimeout(function() {
        window.close();
      }, 2000);
		</script>
HTML;
	echo $html;
	die();
}

/**
 * Write the form
 */
$html = <<<HTML
	<form method="get">		
		<div class="form-group">
			<label for="id">mhw</label>
			<input type="text" id="mhw" disabled value="{$_GET["mhw"]}">
			<input type="hidden" name="mhw" value="{$_GET["mhw"]}">
		</div>
		<div class="form-group">
			<label for="id">mpos</label>
			<input type="text" id="mpos" disabled value="{$_GET["mpos"]}">
			<input type="hidden" name="mpos" value="{$_GET["mpos"]}">
		</div>
		<div class="form-group">
			<label for="id">msub</label>
			<input type="text" id="msub" disabled value="{$_GET["msub"]}">
			<input type="hidden" name="msub" value="{$_GET["msub"]}">
		</div>
	
		<div class="form-group">
			<label for="mphw">mphw</label>
			<input type="text" name="mphw" id="mphw">
		</div>
		<div class="form-group">
			<label for="mppos">mppos</label>
			<input type="text" name="mppos" id="mppos">
		</div>
		<div class="form-group">
			<label for="mpsub">mpsub</label>
			<input type="text" name="mpsub" id="mpsub">
		</div>
HTML;

$html .= <<<HTML
		<div>
			<input type="hidden" name="a" value="save">
			<input type="submit" class="btn btn-primary" value="save"></input>
			<button type="button" class="btn btn-secondary windowClose">cancel</button>
		</div>
	</form>

<script>
	$(function () {
	  $('.windowClose').on('click', function () {
      window.close();
    });
	});
</script>
HTML;

echo $html;

require_once 'includes/htmlFooter2.php';