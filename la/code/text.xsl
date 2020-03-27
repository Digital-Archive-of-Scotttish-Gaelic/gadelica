<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:dasg="https://dasg.ac.uk/corpus/"
  exclude-result-prefixes="xs"
  version="1.0">

  <xsl:output encoding="UTF-8" method="html"/>
  
  <xsl:template match="/">
    <html lang="en">
      <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"/>
        <title>Latin</title>
      </head>
      <body style="padding-top: 20px;">
        <div class="container-fluid">
          <xsl:apply-templates/>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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
    <xsl:apply-templates/>
  </xsl:template>
 
  <xsl:template match="dasg:w[@ref]">
    <xsl:variable name="pos">
      <xsl:choose>
        <xsl:when test="contains(@ref,'/nouns/')">Noun</xsl:when>
        <xsl:when test="contains(@ref,'/verbs/')">Verb</xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="subj">
      <xsl:choose>
        <xsl:when test="@subj!=''">
          <xsl:value-of select="concat('SUBJ ', @subj)"/>
        </xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="pred">
      <xsl:choose>
        <xsl:when test="@pred!=''">
          <xsl:value-of select="concat('PRED ', @pred)"/>
        </xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <a data-toggle="tooltip" data-html="true">
      <xsl:attribute name="href">
        <xsl:value-of select="concat('../code/view',$pos,'Form.php?id=',@ref)"/>
      </xsl:attribute>
      <xsl:attribute name="title">
        <xsl:value-of select="concat($subj,' ',$pred)"/>
      </xsl:attribute>
      <xsl:apply-templates/>
    </a>
  </xsl:template>
  
  <xsl:template match="dasg:pc">
    <xsl:apply-templates/>
  </xsl:template>
  
</xsl:stylesheet>