<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:dasg="https://dasg.ac.uk/corpus/">

  <xsl:template match="@* | node()">
    <xsl:copy>
      <xsl:apply-templates select="@* | node()"/>
    </xsl:copy>
  </xsl:template>

  <!-- add a unique wid to each headword element -->
  <xsl:template match="dasg:w">
    <xsl:copy>
      <xsl:attribute name="wid">
        <xsl:value-of select="concat(/dasg:text/@ref,'_',generate-id(.))"/>
      </xsl:attribute>
      <xsl:apply-templates select="@* | node()"/>
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
