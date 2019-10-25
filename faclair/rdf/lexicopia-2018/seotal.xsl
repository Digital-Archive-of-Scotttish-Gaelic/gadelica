<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  exclude-result-prefixes="xs"
  version="1.0">
  
  <xsl:output method="text" encoding="UTF-8"/>
  
  <xsl:template match="/">
    <xsl:apply-templates select="//dd[@class='def']"/>
  </xsl:template>
  
  <xsl:template match="dd[@class='def']">
    <xsl:variable name="pos" select="following-sibling::dd[@class='grammar'][1]/span[@class='pos']/abbr/@title"/>
    <xsl:choose>
      <xsl:when test="$pos='adjective'">
        <xsl:text>a:</xsl:text>
      </xsl:when>
      <xsl:when test="$pos='noun'">
        <xsl:text>n:</xsl:text>
      </xsl:when>
      <xsl:when test="$pos='verb'">
        <xsl:text>v:</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:text>o:</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:value-of select="translate(.,'- àèìòù','__*^%$£')"/>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>  a :</xsl:text>
    <xsl:apply-templates select="following-sibling::dd[@class='grammar'][1]"/>
    <xsl:text> ; &#10;</xsl:text>
    <xsl:text>  rdfs:label "</xsl:text>
    <xsl:value-of select="."/>
    <xsl:text>" <!--; &#10;--></xsl:text>
    <!--
    <xsl:text>  :sense "</xsl:text>
    <xsl:value-of select="preceding-sibling::dt[1]"/>
    <xsl:text>" </xsl:text>
    -->
    <xsl:apply-templates select="following-sibling::dd[@class='gramUsage'][1]"/>
    <xsl:text>. &#10;</xsl:text>
    <xsl:text>&#10;</xsl:text>
  </xsl:template>
  
  <xsl:template match="dd[@class='grammar']">
    <xsl:variable name="pos" select="span[@class='pos']/abbr/@title"/>
    <xsl:variable name="gender" select="abbr[@class='gne']/@title"/>
    <xsl:choose>
      <xsl:when test="$pos='adjective'">
        <xsl:text>Adjective</xsl:text>
      </xsl:when>
      <xsl:when test="$pos='noun' and $gender='masculine'">
        <xsl:text>MasculineNoun</xsl:text>
      </xsl:when>
      <xsl:when test="$pos='noun' and $gender='feminine'">
        <xsl:text>FeminineNoun</xsl:text>
      </xsl:when>
      <xsl:when test="$pos='verb'">
        <xsl:text>Verb</xsl:text>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$pos"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="dd[@class='gramUsage']">
    <xsl:for-each select="span[@class='sceangailte']">
      <xsl:text>;&#10;  :gen "</xsl:text>
      <xsl:value-of select="substring-before(substring-after(.,'- '),' sc')"/>
      <xsl:text>" </xsl:text>
    </xsl:for-each>
    <xsl:for-each select="span[@class='iom']">
      <xsl:text>;&#10;  :pl "</xsl:text>
      <xsl:value-of select="substring-before(substring-after(.,'- '),' iom')"/>
      <xsl:text>" </xsl:text>
    </xsl:for-each>
  </xsl:template>
  
</xsl:stylesheet>