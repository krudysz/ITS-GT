<html>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
$( "input[name=changeAnswer]" ).live('click', function(event) {
var v = $(this).attr("v");  
switch(v){
    case '+': $("#MyText").append('<p>Add +</p>');break;
    case '-': // use .remove();
        }
    });	
})
</script>
</head>
<body>
<input type="button" name="changeAnswer" id="addAnswer" v="+" value="+">
<p id="MyText">My Text</p>
</body>
</html>
