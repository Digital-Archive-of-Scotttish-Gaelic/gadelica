<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:c="https://dasg.ac.uk/corpus/"
  exclude-result-prefixes="xs"
  version="1.0">
  
  <xsl:output encoding="UTF-8" method="text"/>
  
  <xsl:template match="/">
    <xsl:apply-templates select="//c:w"/>
  </xsl:template>
  
  <xsl:template match="c:w">
    <xsl:value-of select="."/>
    <xsl:text> </xsl:text>
    <xsl:value-of select="@lemma"/>
    <xsl:text> </xsl:text>
    <xsl:value-of select="@pos"/>
    <xsl:text>&#10;</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>