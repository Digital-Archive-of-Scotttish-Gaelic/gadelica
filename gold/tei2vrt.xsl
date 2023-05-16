<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:dasg="https://dasg.ac.uk/corpus/"
    exclude-result-prefixes="xs" version="1.0">

   <xsl:output method="text" encoding="UTF-8"/>

    <xsl:template match="/">
        <xsl:apply-templates select="//dasg:w | //dasg:pc | //dasg:o"/>
    </xsl:template>

    <xsl:template match="dasg:w">
        <xsl:value-of select="translate(.,' ','~')"/>
        <xsl:text>&#9;</xsl:text>
        <xsl:choose>
            <xsl:when test="@pos">
                <xsl:value-of select="@pos"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>NPOS</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
        <xsl:text>&#9;</xsl:text>
        <xsl:value-of select="translate(@lemma,' ','~')"/>
        <xsl:text>&#9;</xsl:text>
        <xsl:value-of select="@wid"/>
        <xsl:text>&#10;</xsl:text>
    </xsl:template>
    
    <xsl:template match="dasg:pc">
        <xsl:value-of select="."/>
        <xsl:text>&#9;</xsl:text>
        <xsl:choose>
            <xsl:when test="@join='left'">
                <xsl:text>LEFT</xsl:text>
            </xsl:when>
            <xsl:when test="@join='right'">
                <xsl:text>RIGHT</xsl:text>
            </xsl:when>
            <xsl:when test="@join='both'">
                <xsl:text>BOTH</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:text>NONE</xsl:text>
            </xsl:otherwise>
        </xsl:choose>
        <xsl:text>&#10;</xsl:text>
    </xsl:template>
    
    <xsl:template match="dasg:o">
        <xsl:value-of select="."/>
        <xsl:text>&#10;</xsl:text>
    </xsl:template>

</xsl:stylesheet>
