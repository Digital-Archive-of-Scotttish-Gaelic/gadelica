<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="https://dasg.ac.uk/corpus/" version="1.0">
  
  <xsl:strip-space elements="w"/>
  <xsl:output encoding="UTF-8" method="xml"/>
  
  <xsl:template match="/">
    <xsl:apply-templates select="div"/>
  </xsl:template>
  
  <xsl:template match="div">
    <xsl:text>&#10;</xsl:text>
    <text ref="" status="raw">
      <xsl:apply-templates/>
    </text>
  </xsl:template>
  
  <xsl:template match="lg">
    <lg>
      <xsl:apply-templates/>
    </lg>
  </xsl:template>
  
  <xsl:template match="l[last()]">
    <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="l">
    <xsl:apply-templates/>
    <lb/>
  </xsl:template>
  
  <xsl:template match="pb">
    <pb n="{@n}"/>
  </xsl:template>
  
  <xsl:template match="lb"/>
  
  <xsl:template match="space"/>
  
  <xsl:template match="pc">
    <pc>
      <xsl:apply-templates/>
    </pc>
  </xsl:template>
  
  <xsl:template match="name">
      <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="w/w">
      <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="w">
    <w id="{@id}" pos="{@pos}" lemma="{@lemma}">
      <xsl:apply-templates/>
    </w>
  </xsl:template>
  
  <xsl:template match="abbr">
    <xsl:apply-templates/>
  </xsl:template>
  
  <xsl:template match="g">
    <xsl:apply-templates/>
  </xsl:template>
  
</xsl:stylesheet>