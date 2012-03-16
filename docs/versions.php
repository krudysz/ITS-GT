<?php
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS Versions</title>
	<link rel="stylesheet" href="css/ITS_versions.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/docs.css">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
</head>

<body>
<div id="pageContainer">
  <p>
  <h1>I<font color="gray">ntelligent</font> T<font color="gray">utoring</font> S<font color="gray">ystem</font>: Version Log</h1>
  </p>
	  <table class="ITS_version" summary="ITS versions">
	    <!--------------------------------------------------------------------->
			<tr><th>Issue</th></tr>
	    <!--------------------------------------------------------------------->
	    <tr>
			<td class="list">
			  <ul>
				<li>Stats: "Calculated" levels of approximity</li>
				<li>Stats: "Matching"</li>
				<li>manage session time-outs (save & logout)</li>
				<li>Update Profile according to "Mode"</li>
        <li>Q.1195 does not work with input 1.5e-3, error <div class="error">Parse error: syntax error, unexpected ';' in /var/www/html/classes/ITS_statistics.php(621) : eval()'d code on line 1<br>Warning: Division by zero in /var/www/html/classes/ITS_statistics.php on line 637</div></li>			
				<li>Add "feedback" field to the Editor Mode</li>
				<li>Add "feedback" to questions</li>				
				<li>Question.php "NEW","CLONE" for "C"</li>
				<li>Randomnize Question/Review initial view: Left to last question</li>
        </ul>
			</td>
		</tr>
		  <!--------------------------------------------------------------------->
  </table>
	<p>
  <table class="ITS_version" summary="ITS versions">
  	    <!--------------------------------------------------------------------->
			<tr><th>Version</th><th>Updates</th></tr>
			<!--------------------------------------------------------------------->
	    <tr>
		  <td>ITS 187<br>Mar-5-2012</td>
			<td class="list">
			  <ol>
			  	<li>Question Mode: Added Distribution after submit</li>
				<li>"Skip" button activity recording</li>
				<li>Tag support</li>
        </ol>
			</td>
		  </tr>	  	    
			<tr><th>Version</th><th>Updates</th></tr>
			<!--------------------------------------------------------------------->
	    <tr>
		  <td>ITS 175<br>Sep-24-2011</td>
			<td class="list">
			  <ol>
			  	<li>Review Mode: Added slider</li>
				<li>Review Mode: Q.C shows actual answer with 3% tolerance</li>
				<li>"NEXT" button 10 click limit fixed, also repositioned now</li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>ITS 157<br>May-02-2011</td>
			<td class="list">
			  <ol>
				<li>3-TAB design: Practice | Questions | Review</li>
				<li>Correct/Incorrect Partial Credit designation</li>
        <li>Moved "rating" to post question submission</li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->			
	    <tr>
		  <td>ITS 141<br>Feb-28-2011</td>
			<td class="list">
			  <ol>
				<li>Added question rating to question</li>
        <li>Added timer to questions: timestamp, duration</li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>ITS 135<br>Jan-31-2011</td>
			<td class="list">
			  <ol>
				<li>Initial Ch 1 recording error - set to current chapter</li>
				<li>Profile.php: add question links</li>
				<li>Profile.php: add "class" pulldown menu for semesters</li>
        <li>Added /Query.php,/DB.php</li>
        </ol>
			</td>
		  </tr>
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>ITS 134<br>Jan-23-2011</td>
			<td class="list">
			  <ol>
        <li>"C" SUBMIT error msg in FF</li>
        <li>Fixed in "C" mode - wrong user parameters after SUBMIT/REVIEW</li>
        <li>Fixed ITS_computeScores() AJAX error: 
				<div class="error">Fatal error: ITS_computeScores::computeChapterScores() [<a href='its-computescores.computechapterscores'>its-computescores.computechapterscores</a>]: The script tried to execute a method or access a property of an incomplete object. Please ensure that the class definition &quot;MDB2_Driver_mysql&quot; of the object you are trying to operate on was loaded _before_ unserialize() gets called or provide a __autoload() function to load the class definition in C:\wamp\www\ITS133\classes\ITS_computeScores.php on line 122</div>
				DB mdb2 connection fails under AJAX - fix connects/disconnects for each method
				</li>
        </ol>
			</td>
		</tr>
	    <!--------------------------------------------------------------------->					
	    <tr>
		  <td>ITS 93<br>Sep-13-2010</td>
			<td class="list">
			  <ol>
        <li>Fixed question select/submit session problems: remove set sessions</li>
        <li>Error msg fix: css</li>
        <li>Added 'Edit' option for answers in Question.php</li>
        </ol>
			</td>
		</tr>
		  <!--------------------------------------------------------------------->
    <tr>
		  <td>ITS 66<br>Feb-18-2010</td><td>&nbsp;</td>
		</tr>
		  <!--------------------------------------------------------------------->
		<tr>
		  <td>ITS 65<br>Feb-13-2010</td><td>&nbsp;</td>
		</tr>
		  <!--------------------------------------------------------------------->
  </table>
</div>
</body>
</html>
