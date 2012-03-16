<?php
/* ============================================================= */
$ITS_version = '166b';
$LAST_UPDATE = 'Jul-25-2011';
/* ============================================================= */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>ITS</title>

        <link rel="stylesheet" href="css/ITS_index4.css" type="text/css" media="screen">		
        <!--[if IE 6]>
      	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
      	<![endif]-->

        <link type="text/css" href="js/jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet">	
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
        <script type="text/javascript">
            function UR_Start() {
                UR_Nu = new Date;
                UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
                document.getElementById("ur").innerHTML = UR_Indhold;
                setTimeout("UR_Start()",1000);
            }
            /*---- GOOGLE ANALYTICS ------------------*/
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-16889198-1']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            /*---- GOOGLE ANALYTICS ------------------*/            
        </script>	 
				<link rel="stylesheet" type="text/css" href="css/ITS_intro.css" media="screen">				
        <link rel="stylesheet" type="text/css" href="plugins/rating/ITS_rating.css" media="screen">
        <link rel="stylesheet" type="text/css" href="plugins/rating/jquery.ui.stars.css?v=3.0.0b38" media="screen">
        <script type="text/javascript" src="plugins/rating/jquery.ui.stars.js?v=3.0.0b38"></script>
    </head>
<body onload="UR_Start()">
<div id="container">
	<div id="header"></div>
	<!-- div#navigation -->
	<div id="navigation">
		<ul>
			<li><a href="login.php">GT Login</a></li>
			<li style="float:left"><div class="loginContainer">
			<form>
			<table>
			<tr><td>Username:</td><td>Password:</td></tr>
			<tr><td><input type="text"></td><td><input type="password"></td></tr>
			</table></form></div></li>
			</ul>
	</div>
	<!-- end div#FOOTER -->
	<div id="content">
		<h2><p><h1>I<font color="gray">ntelligent</font> T<font color="gray">utoring</font> S<font color="gray">ystem</font><br /></h1></h2>
<p>
<ul style="font-size:18pt;list-style-type:square;">
<li>
ITS is a web-based tutoring system designed to enhance a
student's conceptual understanding by providing many
problem-centered exercises.</li>
<br>
<li>The system can be keyed to a course textbook
for knowledge representation and can track each student's problem
solving proficiency in terms of concepts.</li>
</ul>
</p>
<?php
/* ============================================================= /*
<img src="/cgi-bin/mimetex.exe?\fbox{8 \club}" alt="">
x\rightarrow\limits^gy
x\longrightarrow^gy
<img src="/cgi-bin/mimetex.exe?\raisebox{-2}{\rotatebox{20}I}ntelligent \raisebox{-2}{\rotatebox{20}T}utoring">
/* ============================================================= */
?>
	</div>
	<!-- div#FOOTER -->
	<div id="footer">
	<center>
	<b>ITS @ GT</b> is supported in part by the National Science Foundation Grant # 
	<p>
School of Electrical and Computer Engineering <br>
Georgia Institute of Technology, Atlanta, GA 30332-0250
  </p>
	</center>
Copyright <img src="/cgi-bin/mimetex.exe?\compose{\LARGE O}{\normalsize c}" alt=""> ITS @ GT, 2009-2011
	</div>
					<!-- end div#FOOTER -->
</div>
<!-- end div#container -->
</body></html>
