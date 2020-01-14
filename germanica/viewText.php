<!doctype html>
<html lang="en" style="height: 100%">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="text.css">
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"/>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bPopup/0.11.0/jquery.bpopup.min.js"/>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="text.js"></script>
    <title>Korpus</title>
  </head>
  <body style="height: 100%;">
    <div class="container-fluid" style="height: 100%;">
      <div class="row" style="height: 100%;">
        <div id="midl" class="col-6" style="overflow: auto; height: 100%;">
<?php
$text = new SimpleXMLElement("./" . $_GET["t"] . ".xml", LIBXML_XINCLUDE, true);
//$text->registerXPathNamespace('dasg', 'https://dasg.ac.uk/corpus/');
//$subtext = $text->xpath('//dasg:text[@ref="' . $_GET["ref"] . '"]')[0];
$xsl = new DOMDocument;
$xsl->load('viewText.xsl');
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($text);
?>
        </div>
        <div id="rhs" class="col-6" style="overflow: auto; height: 100%;"> <!-- the chunk info panel, on the right -->
        </div>
      </div>
    </div>
  </body>
</html>
