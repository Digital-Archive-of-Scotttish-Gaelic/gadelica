<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:dasg="https://dasg.ac.uk/corpus/"
  exclude-result-prefixes="xs"
  version="1.0">
  
  <xsl:strip-space elements="*"/>
  <xsl:output encoding="UTF-8" method="html"/>
  
  <xsl:template match="/">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="dasg:text">
    <p>
      <a href="#">
        <xsl:attribute name="onclick">
          <xsl:text>showMeta('</xsl:text>
          <xsl:value-of select="@ref"/>
          <xsl:text>');</xsl:text>
        </xsl:attribute>
        [meta]
      </a>
    </p>
    <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="dasg:h">
    <h1>
      <xsl:apply-templates/>
    </h1>
  </xsl:template>
  
  <xsl:template match="dasg:p">
    <p>
      <xsl:apply-templates/>  
    </p>
  </xsl:template>
  
  <xsl:template match="dasg:lg">
    <p>
      <xsl:apply-templates/>  
    </p>
  </xsl:template>
  
  <xsl:template match="dasg:l">
    <xsl:apply-templates/>
    <br/>
  </xsl:template>
  
  <xsl:template match="dasg:o">
    <span style="color:gray">
      <xsl:apply-templates/>
    </span>
  </xsl:template>

  <xsl:template match="dasg:w[name(following-sibling::*[1])='w']">
    <span class="word">
      <xsl:attribute name="data-ref">
        <xsl:value-of select="@ref"/>
      </xsl:attribute>
      <xsl:apply-templates/>
    </span>
    <xsl:text> </xsl:text>
  </xsl:template>
  
  <xsl:template match="dasg:w">
    <span class="word">
      <xsl:attribute name="data-ref">
        <xsl:value-of select="@ref"/>
      </xsl:attribute>
      <xsl:apply-templates/>
    </span>
  </xsl:template>
  
  <xsl:template match="dasg:pc[@join='right']">
    <xsl:text> </xsl:text>
    <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="dasg:pc[@join='left']">
    <xsl:apply-templates/>
    <xsl:text> </xsl:text>
  </xsl:template>
  
  <xsl:template match="dasg:pc[@join='none']">
    <xsl:text> </xsl:text>
    <xsl:apply-templates/>
    <xsl:text> </xsl:text>
  </xsl:template>
  
  <xsl:template match="dasg:pc">
    <xsl:apply-templates/>
  </xsl:template>
  
  
  
</xsl:stylesheet>