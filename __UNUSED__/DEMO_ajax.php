<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Concurent AJAX Demo</title>
<script src="js/AJAX.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function onLd(){
   var helloWorld = new ajaxObject('helloContainer', 'showtext.php');
   helloWorld.update('doc=helloWorld.txt');
	 
   var goodbyeWorld = new ajaxObject('goodbyeContainer', 'ITS_screen_AJAX2.php'); //showtext
	 var php_arguments = 'updateScores';
	 var user_data = '';
   goodbyeWorld.update('doc=goodbyeWorld.txt&ajax_args='+php_arguments+'&ajax_data='+user_data);
	 
	 // ITS_AJAX('ITS_screen_AJAX2.php','updateScores','~',ITS_screen,'scoreContainerContent');
	 // ITS_AJAX(php_file,php_arguments,user_data,js_file,target_obj)
	 
	 // showtext.php => .callback => processData()
	 var liveData = new ajaxObject('abstract', 'showtext.php');
   liveData.callback = function() { processData(); }  
	 liveData.update('doc=livedata.txt');
	 }
   function processData() {
      layerID = document.getElementById('abstract');
      data = layerID.innerHTML;
			alert(data);
      // rest of code processes data which is just a giant string containing all the data recieved from the server.
   }	 
//-->
</script>
</head>
<body >
In the body : <p>
<span id='abstract' style='display:none'></span>
<span id='helloContainer' onclick="onLd()" style="border:1px solid red">This is it</span>
<p>
<span id='goodbyeContainer'></span>
<p>
</body>
</html>
