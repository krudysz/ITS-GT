<!--Force IE6 into quirks mode with this comment tag-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Dynamic Drive: CSS Top Frame Layout</title>
<style type="text/css">

body{
margin: 0;
padding: 0;
border: 0;
overflow: hidden;
height: 100%; 
max-height: 100%; 
}

#framecontent{
position: absolute; 
top: 0; 
left: 0; 
width: 100%; 
height: 130px; /*Height of frame div*/
overflow: hidden; /*Disable scrollbars. Set to "scroll" to enable*/
background-color: navy;
color: white;
}


#maincontent{
position: fixed; 
top: 130px; /*Set top value to HeightOfFrameDiv*/
left: 0;
right: 0;
bottom: 0;
overflow: auto; 
background: #fff;
}

.innertube{
margin: 15px; /*Margins for inner DIV inside each DIV (to provide padding)*/
}

* html body{ /*IE6 hack*/
padding: 130px 0 0 0; /*Set value to (HeightOfFrameDiv 0 0 0)*/
}

* html #maincontent{ /*IE6 hack*/
height: 100%; 
width: 100%; 
}

</style>

<script type="text/javascript">
/*** Temporary text filler function. Remove when deploying template. ***/
var gibberish=["This is just some filler text", "Welcome to Dynamic Drive CSS Library", "Demo content nothing to read here"]
function filltext(words){
for (var i=0; i<words; i++)
document.write(gibberish[Math.floor(Math.random()*3)]+" ")
}
</script>

</head>

<body>

<div id="framecontent">
<div class="innertube">

<h1>CSS Top Frame Layout</h1>
<h3>Sample text here</h3>

</div>
</div>


<div id="maincontent">
<div class="innertube">

<h1>Dynamic Drive CSS Library</h1>
<p><script type="text/javascript">filltext(255)</script></p>

<p style="text-align: center">Credits: <a href="http://www.dynamicdrive.com/style/">Dynamic Drive CSS Library</a></p>

</div>
</div>


</body>
</html>