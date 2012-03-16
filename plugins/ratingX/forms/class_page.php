<?php
class Page{
	function Page(){
	}
	
	function startTemplate($heading1="Rating System",$heading2="ITS difficulty Rating system:")
	{
		echo "<html>";
		echo "<head> <title> $heading1 </title> </head>";
		echo "<body> <h2> $heading2 </h2>";
	}
	
	function closeTemplate()
	{
		echo "</body> </html>";
	}	
	
	function graph_menu()
	{
		echo "<table><tr>";
		echo	"<a href='disp_graph.php' onclick='window.open('disp_graph.php','popup','width=450,height=280,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false'>";
		echo	"<img id='icon1' src='icons/bargrap1.jpg' style='width:60px; height:60px; float:left;'>	</a></tr><tr>";
		echo	"<a href='disp_pie_graph.php' onclick='window.open('disp_pie_graph.php','popup','width=450,height=280,scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false'>";
		echo    "<img id='icon1' src='icons/graph_images2.jpg' style='width:60px; height:60px; float:left;'> </a> </tr> </br>";
	
	}
}
?>