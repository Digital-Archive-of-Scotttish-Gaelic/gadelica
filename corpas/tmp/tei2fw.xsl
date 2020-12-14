<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:dasg="https://dasg.ac.uk/corpus/" exclude-result-prefixes="xs dasg" version="1.0">

  <xsl:output method="xml" indent="yes"/>

  <xsl:strip-space elements="*"/>

  <xsl:template match="/">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="dasg:h">
    <h1>
      <xsl:apply-templates/>
    </h1>
  </xsl:template>

  <xsl:template match="dasg:o">
    <xsl:text> </xsl:text>
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="dasg:w">
    <xsl:text> </xsl:text>
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="dasg:pc">
    <xsl:apply-templates/>
  </xsl:template>

  <xsl:template match="dasg:list">
    <xsl:apply-templates select="dasg:label"/>
  </xsl:template>

  <xsl:template match="dasg:label">
    <item>
      <headword>
        <xsl:apply-templates/>
      </headword>
      <xsl:for-each select="following-sibling::dasg:item[1]">
        <description>
          <xsl:apply-templates/>
        </description>
      </xsl:for-each>
    </item>
  </xsl:template>





</xsl:stylesheet>
