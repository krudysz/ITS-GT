<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>webct_p</h2>
<table border="1">
	<tr class="header">
		<th>id</th>
		<th>template</th>
		<th>answer</th>
</tr>
<xsl:for-each select="webct_p/entry">
<tr>
<td><xsl:value-of select="id"/></td>
<td><xsl:value-of select="template"/></td>
<td><xsl:value-of select="answer"/></td>
</tr></xsl:for-each></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>