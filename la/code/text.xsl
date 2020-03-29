<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:dasg="https://dasg.ac.uk/corpus/"
  exclude-result-prefixes="xs"
  version="1.0">

  <xsl:output encoding="UTF-8" method="html"/>
  
  <xsl:template match="/">
    <html lang="en" style="height: 100%;">
      <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"/>
        <title>Latin</title>
      </head>
      <body style="padding-top: 20px; height: 100%;">
        <div class="container-fluid" style="height: 100%;">
          <div class="row" style="height: 100%;">
            <div id="lhs" class="col-6" style="overflow: auto; height: 100%;">
              <xsl:apply-templates/>
            </div>
            <div id="rhs" class="col-6" style="overflow: auto; height: 100%;">
            </div>
          </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script>
$(function() {

  $('.word').click(function(){
    $('.word').css('background-color', 'inherit');
    $(this).css('background-color', 'yellow');
    var ref = $(this).attr('data-ref');
    var pos = '';
    if (ref.includes('/nouns/')) {
      pos = 'Noun';
    }
    else if (ref.includes('/verbs/')) {
      pos = 'Verb';
    }
    var url = '../code/view' + pos + 'Form.php?id=' + ref;
    var subj = $(this).attr('data-subj');
    var pred = $(this).attr('data-pred');
    var mod = $(this).attr('data-mod');
    var comp = $(this).attr('data-comp');
    var obj = $(this).attr('data-obj');
    var structure = '<dl>';
    if (subj!=undefined) {
      structure += '<dt>subject</dt>';
      structure += '<dd>' + $(this).siblings('#'+subj).text() + '</dd>';
    }
    if (pred!=undefined) {
      structure += '<dt>predicate</dt>';
      structure += '<dd>' + $(this).siblings('#'+pred).text() + '</dd>';
    }
    if (mod!=undefined) {
      structure += '<dt>modifier</dt>';
      structure += '<dd>' + $(this).siblings('#'+mod).text() + '</dd>';
    }
    if (comp!=undefined) {
      structure += '<dt>complement</dt>';
      structure += '<dd>' + $(this).siblings('#'+comp).text() + '</dd>';
    }
    if (obj!=undefined) {
      structure += '<dt>object</dt>';
      structure += '<dd>' + $(this).siblings('#'+obj).text() + '</dd>';
    }
    structure += '</dl>';  
    $('#rhs').load(url,function() {
      $('#rhs').append(structure);  
    });
    
  });
          
});       

function loadForm(ref) {
  var pos = '';
  if (ref.includes('/nouns/')) {
    pos = 'Noun';
  }
  else if (ref.includes('/verbs/')) {
    pos = 'Verb';
  }
  var url = '../code/view' + pos + 'Form.php?id=' + ref;
  $("#rhs").load(url);
}

function loadLexeme(ref) {
  var pos = '';
  if (ref.includes('/nouns/')) {
    pos = 'Noun';
  }
  else if (ref.includes('/verbs/')) {
    pos = 'Verb';
  }
  var url = '../code/view' + pos + 'Lexeme.php?id=' + ref;
  $("#rhs").load(url);
}
        </script>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="dasg:text">
    <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="dasg:h">
    <p>
      <strong>
        <xsl:apply-templates/>
      </strong>
    </p>
  </xsl:template>
  
  <xsl:template match="dasg:p">
    <p>
      <xsl:apply-templates/>  
    </p>
  </xsl:template>
  
  <xsl:template match="dasg:s">
    <span class="sentence">
      <xsl:apply-templates/>
    </span>
  </xsl:template>
 
  <xsl:template match="dasg:w[@ref]"> 
    <a class="word" href="#" id="{@id}" data-ref="{@ref}">
      <xsl:if test="@subj">
        <xsl:attribute name="data-subj">
          <xsl:value-of select="@subj"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="@pred">
        <xsl:attribute name="data-pred">
          <xsl:value-of select="@pred"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="@comp">
        <xsl:attribute name="data-comp">
          <xsl:value-of select="@comp"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="@mod">
        <xsl:attribute name="data-mod">
          <xsl:value-of select="@mod"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="@obj">
        <xsl:attribute name="data-obj">
          <xsl:value-of select="@obj"/>
        </xsl:attribute>
      </xsl:if>
      <xsl:apply-templates/>
    </a>
  </xsl:template>
  
  <xsl:template match="dasg:pc">
    <xsl:apply-templates/>
  </xsl:template>
  
</xsl:stylesheet>