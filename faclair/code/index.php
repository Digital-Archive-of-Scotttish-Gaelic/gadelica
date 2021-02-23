<!doctype html>
<?php
$searchTerm = $_GET['searchTerm'];
$gd = $_GET['gd'];
$query = $_SERVER['QUERY_STRING'];
$snh = false;
$frp = false;
$seotal = false;
if (strpos($query,'lex=snh')>-1) { $snh = true; }
if (strpos($query,'lex=frp')>-1) { $frp = true; }
if (strpos($query,'lex=seotal')>-1) { $seotal = true; }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Briathradan</title>
    <style>
      td { width: 50%; }
    </style>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
      <form action="index.php" method="get" autocomplete="off" id="searchForm"> <!-- Search box -->
        <div class="form-group">
          <div class="input-group">
<?php
echo '<input id="searchBox" type="text" class="form-control active" name="searchTerm" data-toggle="tooltip" title="Enter search term here" ';
if ($searchTerm!='') { echo 'value="' . $searchTerm . '"'; }
else { echo 'autofocus="autofocus"'; }
echo '/>';
?>
            <div class="input-group-append">
              <button id="searchButton" class="btn btn-primary" type="submit" data-toggle="tooltip" title="Click to find entries">Siuthad</button>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Enter English term">
<?php
echo '<input class="form-check-input" type="radio" name="gd" id="enRadio" value="no"';
if ($gd!='yes') { echo ' checked'; }
echo '>';
?>
            <label class="form-check-label" for="enRadio">Beurla</label>
          </div>
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Enter Gaelic term">
<?php
echo '<input class="form-check-input" type="radio" name="gd" id="gdRadio" value="yes"';
if ($gd=='yes') { echo ' checked'; }
echo '>';
?>
            <label class="form-check-label" for="gdRadio">Gàidhlig</label>
          </div>
        </div>
        <div class="form-group">
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Search Scottish Natural Heritage nature terms">
<?php
echo '<input class="form-check-input" type="checkbox" name="lex" id="snhCheck" value="snh"';
if ($snh || $searchTerm=='') { echo ' checked'; }
echo '>';
?>
            <label class="form-check-label" for="snhCheck">Faclan Nàdair</label>
          </div>
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Search the Scottish Parliament dictionary and related resources">
<?php
echo '<input class="form-check-input" type="checkbox" name="lex" id="frpCheck" value="frp"';
if ($frp || $searchTerm=='') { echo ' checked'; }
echo '>';
?>
            <label class="form-check-label" for="frpCheck">Faclair na Pàrlamaid</label>
          </div>
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Search Stòrlann’s terms for use in GME">
<?php
echo '<input class="form-check-input" type="checkbox" name="lex" id="seotalCheck" value="seotal"';
if ($seotal || $searchTerm=='') { echo ' checked'; }
echo '>';
?>
            <label class="form-check-label" for="seotalCheck">Seotal</label>
          </div>
          <!--
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Search Dwelly">
            <input class="form-check-input" type="checkbox" name="dwelly" id="dwellyCheck" value="yes" checked>
            <label class="form-check-label" for="dwellyCheck">Dwelly</label>
          </div>
          <div class="form-check form-check-inline" data-toggle="tooltip" title="Search other resources">
            <input class="form-check-input" type="checkbox" name="others" id="othersCheck" value="yes" checked>
            <label class="form-check-label" for="othersCheck">Eile</label>
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
        <a class="navbar-brand" href="index.php">🍒 Briathradan</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
             <!-- <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a> -->
             <a class="nav-item nav-link" href="viewRandomEntry.php" data-toggle="tooltip" title="View random entry">sonas</a>
          </div>
        </div>
      </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
$(function() {
  var searchTerm = removeAccents($('#searchBox').val());
  if (searchTerm!='') {
    $('#resultsTable tbody').empty();
    var lang = 'en';
    if ($('#gdRadio').is(':checked')) { lang = 'gd'; }
    var snh = false;
    var frp = false;
    var seotal = false;
    var dwelly = false;
    var others = false;
    if($('#snhCheck').is(':checked')) { snh = true; }
    if($('#frpCheck').is(':checked')) { frp = true; }
    if($('#seotalCheck').is(':checked')) { seotal = true; }
    var parameters = '&searchTerm='+searchTerm;
    if (lang=='en') { parameters += '&gd=no'; }
    else { parameters += '&gd=yes'; }
    if (snh) { parameters += '&lex=snh'; }
    if (frp) { parameters += '&lex=frp'; }
    if (seotal) { parameters += '&lex=seotal'; }
    if (lang == 'en') {
      var url = 'getResults.php?action=getEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data,parameters);
      }).done(function() {
        var url = 'getResults.php?action=getMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url, function(data) {
          addData(data,parameters);
        }).done(function() {
          var url = 'getResults.php?action=getEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
          $.getJSON(url, function(data) {
            addData(data,parameters);
          }).done(function() {
            var url = 'getResults.php?action=getEvenEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
            $.getJSON(url, function(data) {
              addData(data,parameters);
            }).done(noResults());
          });
        });
      });
    }
    else {
      var url = 'getResults.php?action=getGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data,parameters);
      }).done(function() {
        var url = 'getResults.php?action=getMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url, function(data) {
          addData(data,parameters);
        }).done(function() {
          var url = 'getResults.php?action=getEvenMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
          $.getJSON(url, function(data) {
            addData(data,parameters);
          }).done(function() {
            var url = 'getResults.php?action=getEvenEvenMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
            $.getJSON(url, function(data) {
              addData(data,parameters);
            }).done(noResults());
          });
        });
      });
    }
  }
});

function removeAccents(str) {
  str = str.replace('ù','u');
  str = str.replace('è','e');
  str = str.replace('é','e');
  str = str.replace('à','a');
  str = str.replace('ì','i');
  str = str.replace('ò','o');
  str = str.replace('ó','o');
  return str;
}

function addData(data,parameters) { // add rows (search results) to the table
      var ids = [];
      $.each(data, function(k,v) {
        id = v.id.value;
        if (ids.indexOf(id)<0) { // unique values only
          ids.push(id);
        }
      });
      $.each(ids, function(k,id) { // display each entry in a row
        var hws = [];
        $.each(data, function(k,v) {
          var gd = v.gd.value;
          if (v.id.value == id && hws.indexOf(gd)<0) { // unique
            hws.push(gd);
          }
        });
        var ens = [];
        $.each(data, function(k,v) {
          var en = v.en.value;
          if (v.id.value == id && ens.indexOf(en)<0) {
            ens.push(en);
          }
        });
        var enStr = ens.join(', ');
        var hwStr;
        if (hws.length>0) {
          hwStr = hws.join(', ');
        }
        else { hwStr = id; }
        $('#resultsTable tbody').append('<tr><td><a href="viewEntry.php?id=' + encodeURI(id) + parameters +'">' + hwStr + '</a></td><td>' + enStr + '</td></tr>');
      });
}

function noResults() {
  x = $('#resultsTable tbody tr').length;
  if (x==0) {
    alert('No results!'); // NEED GAELIC
  }
}
    </script>
  </body>
</html>
