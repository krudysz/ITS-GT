<!DOCTYPE html>
<html>
<head>
  <style>
  #TButton { position:relative; border: 2px solid red;background:#3d9a44; margin:3px; width:80px; 
    height:40px; }
	#TC { position:relative; margin-top: -50px; padding: 10px; height:40px; display:none; border: 2px solid red;background: #eee;}
  </style>
  <script src="http://code.jquery.com/jquery-1.5.js"></script>
</head>
<body>
Some text <p>
Some text <p>
Some text <p>
Some text <p>
<div id="TC"><a href="" >convolution</a> <a href="" >convolution</a></div>
<div id="TButton">TAGS</div>
Some text <p>
<script>
/*
$('#TButton').hover(function () {
  if   ($("#TC:first").is(":hidden")) {$("#TC").show("fast");} 
	else {$("#TC").slideUp();}
});
*/
$("#TButton").click(function(){$("#TC").slideToggle("normal");});
  </script>

</body>
</html>