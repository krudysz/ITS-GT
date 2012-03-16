<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>webct_c</h2>
<table border="1">
	<tr class="header">
		<th>id</th>
		<th>formula</th>
		<th>val1</th>
		<th>min_val1</th>
		<th>max_val1</th>
		<th>val2</th>
		<th>min_val2</th>
		<th>max_val2</th>
		<th>val3</th>
		<th>min_val3</th>
		<th>max_val3</th>
		<th>val4</th>
		<th>min_val4</th>
		<th>max_val4</th>
		<th>val5</th>
		<th>min_val5</th>
		<th>max_val5</th>
		<th>val6</th>
		<th>min_val6</th>
		<th>max_val6</th>
		<th>val7</th>
		<th>min_val7</th>
		<th>max_val7</th>
		<th>val8</th>
		<th>min_val8</th>
		<th>max_val8</th>
		<th>val9</th>
		<th>min_val9</th>
		<th>max_val9</th>
		<th>val10</th>
		<th>min_val10</th>
		<th>max_val10</th>
		<th>vals</th>
</tr>
<xsl:for-each select="webct_c/entry">
<tr>
<td><xsl:value-of select="id"/></td>
<td><xsl:value-of select="formula"/></td>
<td><xsl:value-of select="val1"/></td>
<td><xsl:value-of select="min_val1"/></td>
<td><xsl:value-of select="max_val1"/></td>
<td><xsl:value-of select="val2"/></td>
<td><xsl:value-of select="min_val2"/></td>
<td><xsl:value-of select="max_val2"/></td>
<td><xsl:value-of select="val3"/></td>
<td><xsl:value-of select="min_val3"/></td>
<td><xsl:value-of select="max_val3"/></td>
<td><xsl:value-of select="val4"/></td>
<td><xsl:value-of select="min_val4"/></td>
<td><xsl:value-of select="max_val4"/></td>
<td><xsl:value-of select="val5"/></td>
<td><xsl:value-of select="min_val5"/></td>
<td><xsl:value-of select="max_val5"/></td>
<td><xsl:value-of select="val6"/></td>
<td><xsl:value-of select="min_val6"/></td>
<td><xsl:value-of select="max_val6"/></td>
<td><xsl:value-of select="val7"/></td>
<td><xsl:value-of select="min_val7"/></td>
<td><xsl:value-of select="max_val7"/></td>
<td><xsl:value-of select="val8"/></td>
<td><xsl:value-of select="min_val8"/></td>
<td><xsl:value-of select="max_val8"/></td>
<td><xsl:value-of select="val9"/></td>
<td><xsl:value-of select="min_val9"/></td>
<td><xsl:value-of select="max_val9"/></td>
<td><xsl:value-of select="val10"/></td>
<td><xsl:value-of select="min_val10"/></td>
<td><xsl:value-of select="max_val10"/></td>
<td><xsl:value-of select="vals"/></td>
</tr></xsl:for-each></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>