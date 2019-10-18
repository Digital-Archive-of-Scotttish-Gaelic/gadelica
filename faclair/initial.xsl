<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  exclude-result-prefixes="xs"
  version="1.0">
  
  <xsl:strip-space elements="*"/>
  <xsl:output encoding="UTF-8" method="xml" indent="yes"/>
  
  <xsl:template match="/">
    <entries>
      <xsl:apply-templates select="//p"/>
    </entries>
  </xsl:template>
  
  <xsl:template match="p">
    <entry source="{span/@style}">
      <xsl:apply-templates select="span"/>
    </entry>
  </xsl:template>
  
  <xsl:template match="span">
        <xsl:value-of select="."/>
        <xsl:text> </xsl:text>
  </xsl:template>
  
</xsl:stylesheet>