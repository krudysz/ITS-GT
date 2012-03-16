<?php
include("class_rating_main.php");
echo "<html> <body>";
$p=new rating_main(1,"test","localhost","root","","test","index2.php");

?>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=450,height=380,left = 280,top = 100');");
}
// End -->
</script>

<A HREF="javascript:popUp('disp_graph.php')">
<img id="icon1" src="icons/bargrap1.jpg" style="width:50px; height:50px; float:left;"> 
</A>

<!--
<body onload="loadStars()">
<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="1" style="width:30px; height:30px; float:left;" />
<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="2" style="width:30px; height:30px; float:left;" />
<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="3" style="width:30px; height:30px; float:left;" />
<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="4" style="width:30px; height:30px; float:left;" />
<img src="icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="5" style="width:30px; height:30px; float:left;" /><br /><br />
<div id="vote" style="font-family:tahoma; color:red;"></div>

<div id="cu<html>
rrent rating">
Current Rating:

<script language="Javascript" src="forms/star_rating.js">
</script>

</div>
</body>
-->
