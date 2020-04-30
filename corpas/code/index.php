<?php

require_once "includes/htmlHeader.php";

echo <<<HTML
<div class="list-group list-group-flush">
  <a class="list-group-item list-group-item-action" href="search.php?action=newSearch">search corpus</a>
  <a class="list-group-item list-group-item-action" href="browseCorpus.php">browse corpus</a>
  <a class="list-group-item list-group-item-action" href="generateForms.php">generate dictionary of forms</a>
  <a class="list-group-item list-group-item-action" href="generateHeadwords.php">generate dictionary of headwords</a>
</div>
HTML;

require_once "includes/htmlFooter.php";

?>
