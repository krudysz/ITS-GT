<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>webct</h2>
<table border="1">
	<tr class="header">
		<th>id</th>
		<th>qtype</th>
		<th>title</th>
		<th>image</th>
		<th>question</th>
		<th>answers</th>
		<th>answers_config</th>
		<th>question_config</th>
		<th>category</th>
		<th>tag_id</th>
</tr>
<xsl:for-each select="webct/entry">
<tr>
<td><xsl:value-of select="id"/></td>
<td><xsl:value-of select="qtype"/></td>
<td><xsl:value-of select="title"/></td>
<td><xsl:value-of select="image"/></td>
<td><xsl:value-of select="question"/></td>
<td><xsl:value-of select="answers"/></td>
<td><xsl:value-of select="answers_config"/></td>
<td><xsl:value-of select="question_config"/></td>
<td><xsl:value-of select="category"/></td>
<td><xsl:value-of select="tag_id"/></td>
</tr></xsl:for-each></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>