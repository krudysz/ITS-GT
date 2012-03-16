<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>webct_s</h2>
<table border="1">
	<tr class="header">
		<th>id</th>
		<th>ans1</th>
		<th>ans2</th>
		<th>ans3</th>
		<th>ans4</th>
		<th>ans5</th>
		<th>ans6</th>
		<th>ans7</th>
		<th>ans8</th>
		<th>ans9</th>
		<th>ans10</th>
		<th>ans11</th>
		<th>ans12</th>
</tr>
<xsl:for-each select="webct_s/entry">
<tr>
<td><xsl:value-of select="id"/></td>
<td><xsl:value-of select="ans1"/></td>
<td><xsl:value-of select="ans2"/></td>
<td><xsl:value-of select="ans3"/></td>
<td><xsl:value-of select="ans4"/></td>
<td><xsl:value-of select="ans5"/></td>
<td><xsl:value-of select="ans6"/></td>
<td><xsl:value-of select="ans7"/></td>
<td><xsl:value-of select="ans8"/></td>
<td><xsl:value-of select="ans9"/></td>
<td><xsl:value-of select="ans10"/></td>
<td><xsl:value-of select="ans11"/></td>
<td><xsl:value-of select="ans12"/></td>
</tr></xsl:for-each></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>