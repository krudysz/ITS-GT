<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>Questions (QTI)</h2>
<xsl:for-each select="questestinterop/assessment/section/item">
<h3><xsl:value-of select="response_lid"/></h3>
<table border="1">
 <xsl:for-each select="presentation/material">
 <tr><th colspan="2" class="header">Question</th></tr>
 <tr><td colspan="2"><xsl:value-of select="mattext"/></td></tr>
 <xsl:if test="matimage/@uri">
   <tr><td class="image" colspan="2"><xsl:value-of select="matimage/@uri"/></td></tr>
 </xsl:if>
 </xsl:for-each>
 <tr><th class="header">Answers</th><th class="header">Weight</th></tr>
  <tr>
    <td class="answers">
  		<xsl:for-each select="presentation/response_lid/render_choice/response_label/material">
      <tr><td><xsl:value-of select="mattext"/></td></tr>
      </xsl:for-each>
		</td>
		<td class="weight">
  		<xsl:for-each select="resprocessing/respcondition">
  		<tr><td><xsl:value-of select="setvar"/></td></tr>
  		</xsl:for-each>
		</td>
 </tr>
</table>
</xsl:for-each>
</body>
</html>
</xsl:template>
</xsl:stylesheet>