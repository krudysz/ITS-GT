<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<style type="text/css">
body /*TH,CAPTION */
{
	margin: 0;
	padding: 0;
	font: 12pt Verdana, Geneva, sans-serif;
	/*font-family: arial,sans-serif; /*font: 100% Georgia, serif; /*font-family: arial; */
	text-align: center;
	color: black;
	background: gainsboro;
	/*background: #000 url(ML.png) repeat 0 0;*/
}
#pageContainer
{
  postion:absolute;
	width: 95%;
	margin-left:-100%;
	margin:0 auto; /*Centers the Container*/
	background: pink;
}
#contentContainer{
  position: relative;
  padding:0; 
	margin:0 auto ;
	background:white;
	border:2px solid red;
	width: 100%;
	overflow:auto;
}
/*
div.other {
  position: absolute;
  padding:0; 
	margin:0;
	border:2px solid purple;
	display: inline;
}*/
input {
 color: red;
 display: none;
}
label {
 border:2px solid purple;
 color: red;
}
label:hover {
 border:2px solid red;
 color: blue;
}
	/*---------------*/
</style>
</head>
<body>
<div id="pageContainer">
    <div id="contentContainer"><p>
<input type="checkbox" class="check" id="check11" name="check" onclick="javascript:ITS_match_box(this)"/><label class="mylabel" for="check11">Label</label>
<input type="checkbox" class="check" id="check12" name="check" onclick="javascript:ITS_match_box(this)"/><label class="mylabel" for="check12">Label</label>
							
    </div>
</div>
<!--
<ul class="DIP"><li>Add a description of the imagecb here</li>
<li><img src="PreLabs/Phase1.png" alt="Klematis"></li>
</ul>
</div>-->
</body>
</html> 