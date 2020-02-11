<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="ajax.js"></script>
    <title>Stòras Brì</title>
  </head>
  <!--
<?php
$searchTerm = $_GET['searchTerm'];
$lang = $_GET['lang'];
$snh = $_GET['snh'];
$frp = $_GET['frp'];
$seotal = $_GET['seotal'];
if ($searchTerm!='') {
  echo '<body style="padding-top: 20px;" data-searchterm="' . $searchTerm . '" data-lang="' . $lang . '"';
  if ($snh=='yes') { echo ' data-snh="yes"'; }
  else { echo ' data-snh="no"'; }
  if ($frp=='yes') { echo ' data-frp="yes"'; }
  else { echo ' data-frp="no"'; }
  if ($seotal=='yes') { echo ' data-seotal="yes"'; }
  else { echo ' data-seotal="no"'; }
  echo '>';
}
else {
  echo '<body style="padding-top: 20px;">';
}
?>
-->
  <body style="padding-top: 20px;">
    <div class="container-fluid">
      <form action="" method="get" autocomplete="off" id="searchForm"> <!-- Search box -->
        <div class="form-group">
          <div class="input-group">
            <input id="searchBox" type="text" class="form-control active" name="searchTerm"  data-toggle="tooltip" title="Enter search term here" autofocus="autofocus"/>
            <div class="input-group-append">
              <button class="btn btn-primary" type="submit" data-toggle="tooltip" title="Click to find entries">Lorg</button>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="lang" id="enRadio" value="en"<?php if ($lang!='gd') echo ' checked'; ?>>
            <label class="form-check-label" for="enRadio">Beurla</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="lang" id="gdRadio" value="gd"<?php if ($lang=='gd') echo ' checked'; ?>>
            <label class="form-check-label" for="gdRadio">Gàidhlig</label>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="snh" id="snhCheck" value="yes"<?php if ($searchTerm=='' || $snh=='yes') echo ' checked'; ?>>
            <label class="form-check-label" for="snhCheck">Faclan Nàdair (SNH)</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="frp" id="frpCheck" value="yes"<?php if ($searchTerm=='' || $frp=='yes') echo ' checked'; ?>>
            <label class="form-check-label" for="frpCheck">Faclair Rianachd Phoblaich</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="seotal" id="seotalCheck" value="yes"<?php if ($searchTerm=='' || $seotal=='yes') echo ' checked'; ?>>
            <label class="form-check-label" for="seotalCheck">Seotal</label>
          </div>
          <!--
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lexicon" id="dwellyCheck" value="dwelly" checked>
            <label class="form-check-label" for="dwellyCheck">Dwelly</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="lexicon" id="otherCheck" value="other" checked>
            <label class="form-check-label" for="otherCheck">Eile</label>
          </div>
        -->
        </div>
      </form>
      <table class="table table-hover" id="resultsTable">
        <tbody>
        </tbody>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
        <a class="navbar-brand" href="index.php">🏛 Stòras Brì</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
             <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
             <a class="nav-item nav-link" href="random.php" data-toggle="tooltip" title="View random entry">sonas</a>
          </div>
        </div>
      </nav>
    </div>
  </body>
</html>
