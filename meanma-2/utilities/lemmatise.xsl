<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
    xmlns:dasg="https://dasg.ac.uk/corpus/">
    <xsl:output method="text" encoding="UTF-8"/>
    
    <!-- this script is deprecated -->
    
    <xsl:template match="/">
        <xsl:apply-templates select="child::dasg:text"/>
    </xsl:template>
    
    <xsl:template match="dasg:text">
        <xsl:choose>
            <xsl:when test="@status='tagged'">
                <xsl:apply-templates select="descendant::dasg:w"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="child::dasg:text"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    
    <!--
    <xsl:apply-templates select="//dasg:w[ancestor::dasg:text[@status='tagged']]"/>
    -->
    
    <xsl:template match="dasg:w">
        <xsl:value-of select="."/>
        <xsl:text>&#09;</xsl:text>
        <xsl:value-of select="@lemma"/>
        <xsl:text>&#09;</xsl:text>
        <xsl:value-of select="@pos"/>
        <!--<xsl:text>&#09;</xsl:text>
        <xsl:value-of select="ancestor::dasg:text[1]/@ref"/>-->
        <xsl:text>&#10;</xsl:text>
    </xsl:template>
    
</xsl:stylesheet>