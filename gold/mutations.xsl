<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
    xmlns:dasg="https://dasg.ac.uk/corpus/">
    <xsl:output method="xml" encoding="UTF-8"/>

    <xsl:template match="@* | node()">
        <xsl:copy>
            <xsl:apply-templates select="@* | node()"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template
        match="dasg:w[(text()='t' or text()='h' or text()='n') and following-sibling::*[1][self::dasg:pc[text()='-']]]"/>

    <xsl:template
        match="dasg:pc[text()='-' and preceding-sibling::*[1][self::dasg:w[text()='t' or text()='h' or text()='n']]]"/>

    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text()='t']] and preceding-sibling::*[1][self::dasg:pc[text()='-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('t-',.)"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text()='h']] and preceding-sibling::*[1][self::dasg:pc[text()='-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('h-',.)"/>
        </xsl:copy>
    </xsl:template>
    
    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text()='n']] and preceding-sibling::*[1][self::dasg:pc[text()='-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('n-',.)"/>
        </xsl:copy>
    </xsl:template>
    
    

</xsl:stylesheet>
