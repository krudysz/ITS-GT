<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/pagestyles.css" type="text/css">
<style type="text/css">
body{
	margin:0; padding:0; /*Because Browsers Add Different Margin and Padding around their body*/
	font: 12px Verdana, Geneva, sans-serif;
}
#pageContainer{
	width: 80%;
	margin:0 auto; /*Centers the Container*/
	background:#e8ffd8;
	border:1px solid #bbbbbb;
	overflow:auto; /*Makes the Container Wrap around the floats, remove it to see what I mean*/
	padding:1em;
}
#banner{
	padding:1em; margin:0 auto 1em auto;
	background:#fff;
	border:1px solid #bbbbbb;
}
#leftContainer{
	width: 160px;
	background:#fff;
	border: 1px solid #bbbbbb;
	float:left;
	margin-right:-100%; /*Margin Trick to allow room for right container*/
	padding:10px;
	/*Wrapping Words For all Browsers below*/
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
    white-space: -pre-wrap; /* Opera 4-6 */
    white-space: -o-pre-wrap; /* Opera 7 */
    word-wrap: break-word; /* Internet Explorer 5.5+ */
}
#contentsContainer{
	background:#fff;
	border: 1px solid #bbbbbb;
	float:right;
	margin-left:200px; /*Make Room for the Sidebar*/
}
p {
	margin:0; padding:10px;
}
</style>
</head>

<body>
<div id="pageContainer">
	<div id="banner">HE</div>
	<div id="contentsContainer">
		<p>Phasellus porttitor aliquam odio id accumsan. Nulla facilisi. Donec euismod mattis metus, sit amet tristique neque fringilla sit amet. Proin risus lacus, lacinia vel consectetur quis, commodo ac ante. Duis fringilla sodales orci, vitae posuere massa fringilla at. Nullam vel eros turpis, eu bibendum urna. Integer ornare vulputate erat, nec ultrices lorem euismod non. Aenean vehicula nunc ut nibh tincidunt eget sodales diam euismod. Curabitur vulputate turpis non justo ultrices varius. Nullam feugiat lacinia libero, nec elementum erat hendrerit eu. Sed quis urna non eros eleifend fermentum. Praesent mauris quam, pellentesque ut suscipit vitae, elementum nec massa. Quisque turpis velit, rutrum eget bibendum ac, consequat ut velit. Ut rutrum nisl leo. Nullam et nulla dolor, nec aliquet felis. Fusce eu libero massa, mollis pretium quam. Sed gravida ipsum vitae lectus feugiat quis blandit risus mollis. Duis nisi mi, cursus vel malesuada vitae, condimentum nec magna.</p>
	<input type="hidden" value="add">
	</div>
	<div id="leftContainer"><img src="images/chessboard.jpeg" alt=""><q>It has been estimated that world-class chess masters require from 50,000 to 100,000 hours of practice to reach that level of expertise.</q><font size="3" color="gray">[ 100,000 hours = 11.4 years]</font>
	</div>
</div>
</body>
</html>