<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
    xmlns:dasg="https://dasg.ac.uk/corpus/">
    <xsl:output method="xml" encoding="UTF-8"/>

    <xsl:template match="@* | node()">
        <xsl:copy>
            <xsl:apply-templates select="@* | node()"/>
        </xsl:copy>
    </xsl:template>

    <!-- strip out t-, h- and n- mutations -->

    <xsl:template
        match="dasg:w[(text() = 't' or text() = 'h' or text() = 'n') and following-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '-']] and following-sibling::*[2][self::dasg:w]]"/>

    <xsl:template
        match="dasg:pc[@join = 'both' and text() = '-' and preceding-sibling::*[1][self::dasg:w[text() = 't' or text() = 'h' or text() = 'n']] and following-sibling::*[1][self::dasg:w]]"/>

    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text() = 't']] and preceding-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('t-', .)"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text() = 'h']] and preceding-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('h-', .)"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template
        match="dasg:w[preceding-sibling::*[2][self::dasg:w[text() = 'n']] and preceding-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '-']]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('n-', .)"/>
        </xsl:copy>
    </xsl:template>

    <!-- strip out a’ -->

    <xsl:template
        match="dasg:pc[@join = 'left' and text() = '’' and preceding-sibling::*[1][self::dasg:w[text() = 'a']] and following-sibling::*[1][self::dasg:w]]"/>

    <xsl:template
        match="dasg:w[text() = 'a' and following-sibling::*[1][self::dasg:pc[@join = 'left' and text() = '’']] and following-sibling::*[2][self::dasg:w]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:text>a’</xsl:text>
        </xsl:copy>
    </xsl:template>

    <!-- strip out ’nam etc. -->


    <xsl:template
        match="dasg:pc[@join = 'right' and text() = '’' and preceding-sibling::*[1][self::dasg:w] and following-sibling::*[1][self::dasg:w]]"/>

    <xsl:template
        match="dasg:w[preceding-sibling::*[1][self::dasg:pc[@join = 'right' and text() = '’']] and preceding-sibling::*[2][self::dasg:w]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat('’', .)"/>
        </xsl:copy>
    </xsl:template>

    <!-- string out de’n etc. -->

    <xsl:template
        match="dasg:pc[@join = 'both' and text() = '’' and preceding-sibling::*[1][self::dasg:w] and following-sibling::*[1][self::dasg:w]]"/>

    <xsl:template
        match="dasg:w[preceding-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '’']] and preceding-sibling::*[2][self::dasg:w]]"/>

    <xsl:template
        match="dasg:w[following-sibling::*[1][self::dasg:pc[@join = 'both' and text() = '’']] and following-sibling::*[2][self::dasg:w]]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:value-of select="concat(., '’', following-sibling::*[2])"/>
        </xsl:copy>
    </xsl:template>

    <!-- strip out sam bith -->

    <xsl:template
        match="dasg:w[text() = 'sam' and following-sibling::*[1][self::dasg:w and text() = 'bith']]">
        <xsl:copy>
            <xsl:copy-of select="@*"/>
            <xsl:text>sam bith</xsl:text>
        </xsl:copy>
    </xsl:template>

    <xsl:template
        match="dasg:w[text() = 'bith' and preceding-sibling::*[1][self::dasg:w and text() = 'sam']]"/>


    <!-- <w wid="_200_d2e979" pos="n" lemma="sam">sam</w>
    <w wid="_200_d2e983" pos="n" lemma="bith">bith</w>
-->



</xsl:stylesheet>
