<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<h2>webct_mc</h2>
<table border="1">
	<tr class="header">
		<th>id</th>
		<th>feedback</th>
		<th>weight1</th>
		<th>answer1</th>
		<th>reason1</th>
		<th>weight2</th>
		<th>answer2</th>
		<th>reason2</th>
		<th>weight3</th>
		<th>answer3</th>
		<th>reason3</th>
		<th>weight4</th>
		<th>answer4</th>
		<th>reason4</th>
		<th>weight5</th>
		<th>answer5</th>
		<th>reason5</th>
		<th>weight6</th>
		<th>answer6</th>
		<th>reason6</th>
		<th>weight7</th>
		<th>answer7</th>
		<th>reason7</th>
		<th>weight8</th>
		<th>answer8</th>
		<th>reason8</th>
		<th>weight9</th>
		<th>answer9</th>
		<th>reason9</th>
		<th>weight10</th>
		<th>answer10</th>
		<th>reason10</th>
		<th>weight11</th>
		<th>answer11</th>
		<th>reason11</th>
		<th>weight12</th>
		<th>answer12</th>
		<th>reason12</th>
		<th>weight13</th>
		<th>answer13</th>
		<th>reason13</th>
		<th>weight14</th>
		<th>answer14</th>
		<th>reason14</th>
		<th>weight15</th>
		<th>answer15</th>
		<th>reason15</th>
		<th>weight16</th>
		<th>answer16</th>
		<th>reason16</th>
		<th>weight17</th>
		<th>answer17</th>
		<th>reason17</th>
		<th>weight18</th>
		<th>answer18</th>
		<th>reason18</th>
		<th>weight19</th>
		<th>answer19</th>
		<th>reason19</th>
		<th>weight20</th>
		<th>answer20</th>
		<th>reason20</th>
		<th>weight21</th>
		<th>answer21</th>
		<th>reason21</th>
		<th>weight22</th>
		<th>answer22</th>
		<th>reason22</th>
</tr>
<xsl:for-each select="webct_mc/entry">
<tr>
<td><xsl:value-of select="id"/></td>
<td><xsl:value-of select="feedback"/></td>
<td><xsl:value-of select="weight1"/></td>
<td><xsl:value-of select="answer1"/></td>
<td><xsl:value-of select="reason1"/></td>
<td><xsl:value-of select="weight2"/></td>
<td><xsl:value-of select="answer2"/></td>
<td><xsl:value-of select="reason2"/></td>
<td><xsl:value-of select="weight3"/></td>
<td><xsl:value-of select="answer3"/></td>
<td><xsl:value-of select="reason3"/></td>
<td><xsl:value-of select="weight4"/></td>
<td><xsl:value-of select="answer4"/></td>
<td><xsl:value-of select="reason4"/></td>
<td><xsl:value-of select="weight5"/></td>
<td><xsl:value-of select="answer5"/></td>
<td><xsl:value-of select="reason5"/></td>
<td><xsl:value-of select="weight6"/></td>
<td><xsl:value-of select="answer6"/></td>
<td><xsl:value-of select="reason6"/></td>
<td><xsl:value-of select="weight7"/></td>
<td><xsl:value-of select="answer7"/></td>
<td><xsl:value-of select="reason7"/></td>
<td><xsl:value-of select="weight8"/></td>
<td><xsl:value-of select="answer8"/></td>
<td><xsl:value-of select="reason8"/></td>
<td><xsl:value-of select="weight9"/></td>
<td><xsl:value-of select="answer9"/></td>
<td><xsl:value-of select="reason9"/></td>
<td><xsl:value-of select="weight10"/></td>
<td><xsl:value-of select="answer10"/></td>
<td><xsl:value-of select="reason10"/></td>
<td><xsl:value-of select="weight11"/></td>
<td><xsl:value-of select="answer11"/></td>
<td><xsl:value-of select="reason11"/></td>
<td><xsl:value-of select="weight12"/></td>
<td><xsl:value-of select="answer12"/></td>
<td><xsl:value-of select="reason12"/></td>
<td><xsl:value-of select="weight13"/></td>
<td><xsl:value-of select="answer13"/></td>
<td><xsl:value-of select="reason13"/></td>
<td><xsl:value-of select="weight14"/></td>
<td><xsl:value-of select="answer14"/></td>
<td><xsl:value-of select="reason14"/></td>
<td><xsl:value-of select="weight15"/></td>
<td><xsl:value-of select="answer15"/></td>
<td><xsl:value-of select="reason15"/></td>
<td><xsl:value-of select="weight16"/></td>
<td><xsl:value-of select="answer16"/></td>
<td><xsl:value-of select="reason16"/></td>
<td><xsl:value-of select="weight17"/></td>
<td><xsl:value-of select="answer17"/></td>
<td><xsl:value-of select="reason17"/></td>
<td><xsl:value-of select="weight18"/></td>
<td><xsl:value-of select="answer18"/></td>
<td><xsl:value-of select="reason18"/></td>
<td><xsl:value-of select="weight19"/></td>
<td><xsl:value-of select="answer19"/></td>
<td><xsl:value-of select="reason19"/></td>
<td><xsl:value-of select="weight20"/></td>
<td><xsl:value-of select="answer20"/></td>
<td><xsl:value-of select="reason20"/></td>
<td><xsl:value-of select="weight21"/></td>
<td><xsl:value-of select="answer21"/></td>
<td><xsl:value-of select="reason21"/></td>
<td><xsl:value-of select="weight22"/></td>
<td><xsl:value-of select="answer22"/></td>
<td><xsl:value-of select="reason22"/></td>
</tr></xsl:for-each></table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>