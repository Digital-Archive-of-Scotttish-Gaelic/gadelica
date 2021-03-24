<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  exclude-result-prefixes="xs"
  version="1.0">
  
  <xsl:output encoding="UTF-8" method="html"/>
  
  <xsl:template match="/">
    <xsl:apply-templates select="module/*"/>
  </xsl:template>
  
  <xsl:template match="module/title">
    <h1>
      <xsl:apply-templates/>
    </h1>
  </xsl:template>
  
  <xsl:template match="meta"/>
  
  <xsl:template match="p|strong|hr|ol|ul|li|a|h1|h2|h3|h4|h5|table|thead|tbody|tr|td|th|u">
    <xsl:copy>
      <xsl:if test="@type">
        <xsl:attribute name="type"><xsl:value-of select="@type"/></xsl:attribute>
      </xsl:if>
      <xsl:if test="@href">
        <xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
      </xsl:if>
      <xsl:if test="@class">
        <xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute>
      </xsl:if>
      <xsl:if test="@colspan">
        <xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute>
      </xsl:if>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>
  
  <xsl:template match="m">
    <mark>
      <xsl:apply-templates/>
    </mark>
  </xsl:template>
  
  <xsl:template match="i">
    <em>
      <xsl:apply-templates/>
    </em>
  </xsl:template>
  
  <xsl:template match="en">
    <span class="text-muted">
      <xsl:text>‘</xsl:text><xsl:apply-templates/><xsl:text>’</xsl:text>
    </span>
  </xsl:template>
  
  <xsl:template match="boxlink">
    <xsl:text> </xsl:text>
    <small><a data-toggle="modal" data-target="{@ref}" href="#">[more]</a></small>
  </xsl:template>
  
  <xsl:template match="boxlink2">
    <a data-toggle="modal" data-target="{@ref}" href="#">
      <xsl:apply-templates/>
    </a>
  </xsl:template>
  
  <xsl:template match="box">
    <div class="modal fade" id="{@id}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">
              <xsl:apply-templates select="title"/>
            </h2>
          </div>
          <div class="modal-body">
            <xsl:apply-templates select="*[name()!='title']"/>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </xsl:template>

  <xsl:template match="xl">
    <ul class="list-group list-group-flush mb-3">
      <xsl:apply-templates/>
    </ul>
  </xsl:template>
  
  <xsl:template match="xl/li">
    <li class="ex list-group-item list-group-item-secondary" data-slip="{.}"/>
  </xsl:template>
  
  <xsl:template match="xlx">
    <ul class="list-group list-group-flush mb-3">
      <xsl:for-each select="li">
        <li class="list-group-item list-group-item-secondary">
          <xsl:apply-templates/>
        </li>
      </xsl:for-each>
    </ul>
  </xsl:template>
  
  <xsl:template match="gd">
    <strong>
      <xsl:apply-templates/>
    </strong>
  </xsl:template>
  
  <xsl:template match="en2">
    <br/>
    <small class="text-muted">
      <xsl:apply-templates/>
    </small>
  </xsl:template>
  
  <xsl:template match="ref">
    <br/>
    <small class="text-muted">
      <xsl:text>[</xsl:text>
      <xsl:apply-templates/>
      <xsl:text>]</xsl:text>
    </small>
  </xsl:template>
  
</xsl:stylesheet>