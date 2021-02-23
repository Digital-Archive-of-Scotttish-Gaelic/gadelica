<?php

require_once 'includes/htmlHeader2.php';

$db = new models\database();

if (!isset($_GET["id"])) {
	die('<h2>Error: unrecognised parameter</h2>');
} else {
	//delete the part data
	$sql = <<<SQL
		DELETE FROM parts WHERE id = :id
SQL;
	$db->exec($sql, array(":id" => $_GET["id"]));

	$html = <<<HTML
		<h2>Part deleted</h2>
		<script>
			window.opener.document.location.reload(true); //reload parent page
			setTimeout(function() {
        window.close();
      }, 2000);
		</script>
HTML;
	echo $html;
}

require_once 'includes/htmlFooter2.php';