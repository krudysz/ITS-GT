<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" media="screen" />


<script type="text/javascript">
$(document).ready(function() {
	/* This is basic - uses default settings */
	$("a#single_image").fancybox();	
	/* Using custom settings */	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});
	/* Apply fancybox to multiple items */	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	900, 
		'speedOut'		:	900, 
		'overlayShow'	:	true
	});
});
</script>
</head>
<body>
<a id="single_image" href="pdf_icon.gif"><img src="pdf_icon.gif" alt=""/></a>

<a id="inline" href="#data">This shows content of element who has id="data"</a>
<div style="display:none"><div id="data">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div></div>

<a id="single_image" href="http://localhost/ITS-GT/TEST/test_plus.php">This takes content using ajax</a>
</body>
</html>
