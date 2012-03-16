<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>VIP</title>
	<link rel="stylesheet" href="../docs/css/docs.css">
</head>

<body>
<h3>VIP ITS Projects</h3>

<h2>Fall 2011</h2>
  <table class="ITS_version" summary="ITS versions">
	    <!--------------------------------------------------------------------->
			<tr><th>PROJECT</th><th>MEMBERS</th><th>DETAILS</th></tr>
			<!--------------------------------------------------------------------->
				    <tr>
		  <td>WebView</td>
		  <td>May Li</td>
			<td class="list">
			  <ol>
				<li><b>Emulator Install:</b> <a href="http://developer.android.com/sdk/installing.html">http://developer.android.com/sdk/installing.html</a></li>
				<li>To import the project, open up eclipse -> file -> import...
open the folder that says general, and select Existing Projects into Workspace. 
select root directory. Browse to the location where you save the files of the project. 
Eclipse will search for the project, and show a list of projects that are in the selected directory. 
select the one you want to import, check the box where it says Copy projects into workspace</li>
<li><b>Files:</b> <a href="Fall2011/WebViewDemo2.zip">WebViewDemo2.zip</a></li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->
	    <tr>
		  <td>Question Tagger</td>
		  <td>David Fleischhauer, Rakesh Kumar, Kha Tran</td>
			<td class="list">
			  <ol>
				<li>(Optional) Manually clear the current tags in the webct table. If this is not done, there will be certain tags listed multiple times. SQL command: update webct set VIP_tags=null;</li>
				<li>(Optional) Run the tag editing script to “turn off” (i.e. not use) tags deemed by the group to be too general or too specific in the current tags table. It also automatically finds tags that regexp deems similar and retains only one of those tags for use.</li>
				<li>Run the tagging script. This will automatically tag questions based on the tags meant to be used (where the column use_check is null in the tags table). The only MATLAB outputs from this script are for statistical purposes and were used in the final presentation. Tags may be viewed in the SQL database using the command: select VIP_tags from webct;</li><li>The number of times each tag is used may be viewed in the tags table column num_uses.</li>
				<li><b>Files:</b> <a href="Fall2011/Tagging.zip">Tagging.zip</a></li>
        </ol>
			</td>
		  </tr>	
	    <!--------------------------------------------------------------------->			
  </table>
</body>
</html>
