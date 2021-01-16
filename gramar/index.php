<?php
namespace controllers;
session_start();
$_SESSION["groupId"] = 2; //need to set this for the database queries
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

echo <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <title>Gràmar na Gàidhlig</title>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script>
	  $(function() {
		  $('.ex').each(function () {
		    var li = $(this);
	      var url = '../corpas/code/ajax.php?action=loadSlipData&id='+$(this).attr('data-slip');
	      $.getJSON(url, function (data) {
	        var text = '<strong>' + data.context.pre["output"] + ' <mark>' + data.context.word + '</mark> ' + data.context.post["output"] + '</strong><br/>';
          trans = data.translation;
          trans = trans.replace('<p>','');
          trans = trans.replace('</p>','');
	        text += '<small class="text-muted">' + trans + ' [#' + data.tid + ']</small>';
	        li.html(text);
	      });
		  });
		});
	</script>
</head>
<body style="padding-top: 80px;">
  <div class="container-fluid">
    <nav class="navbar navbar-dark bg-primary fixed-top navbar-expand-lg">
      <a class="navbar-brand" href="index.php">Gràmar na Gàidhlig</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <!--
          <a class="nav-item nav-link" href="browseCorpus.php">browse</a>
        -->
        </div>
      </div>
    </nav>
HTML;

new gaelic();

echo <<<HTML
      <hr/><hr/>
    </div>
</body>
</html>
HTML;
