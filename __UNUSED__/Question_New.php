<?php
include("config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script src="js/ITS.js"/></script>
<script src="js/ITS_add_file.js"/></script>
<script src="js/ITS_http.js"/></script>
<title>WebCT Questions</title>
</head>
<link rel="stylesheet" href="pagestyles.css">
<body>
<!-- JAVA SCRIPT ------------------->
<script type="text/javascript">
function ITS_submit(sel){
      sel.selectedIndex = sel.options[sel.selectedIndex].value;
			sel.form.submit(); 
}
</script>
<!---------------------------------->
<?php
echo time()."<p>";
// Pulldown menu selection
$pulldown_name = 'sel_type';
// var_dump($_POST);
// echo "<p>".$tb_name."<p>";

if (isset($_POST[$pulldown_name])){ // user selected
	 																	$selected = $_POST[$pulldown_name]+1;
} else {  													// initialize
	 		 			 					 							$selected = 5;
}
		
	// pulldown menu
	$choices = array('short answer','matching','paragraph','calculated','multiple choice','other');
	//$ITS_pm = new ITS_pulldown_menu($pulldown_name,$choices,$selected);
	
$choice_table = "<table>"
		 					 ."<tr>"										  			 
		 					 ."<td nowrap width = 85%>"
							 ."<form action=Question_New.php method=post name=Question_New_choice_form>"
							 ."TYPE: "  
		 					 ."<select name=".$pulldown_name." onchange=ITS_submit(this)>"
		 					 .'$ITS_pm->str'
		 					 ."</select>" 
							 ."</form>"
		 					 ."</td>"
		 					 ."</table>";
echo $choice_table;				 		

$Q = new ITS_question_template(1,$db_name,$tb_name);

// FORM
echo "<form action=Question_Preview.php method=post name=Question_New_form id=Question_New_form target=Question_frame>";	
// TITLE
echo $Q->TITLE();
	 
// QUESTION
echo $Q->QUESTION();		 
		 
// ANSWER ( based on 'type' from pulldown menu )
$type_arr = array('s','m','p','c','mc','o');
if (isset($_POST[$pulldown_name])) {
 $type = $type_arr[$_POST[$pulldown_name]];
 //echo "<p>".$type;
} 
else {
 $type = $type_arr[4];
}

echo $Q->ANSWERS($type);
echo  "<input type=hidden name=HDN_TYPE value=".$type.">"
		 ."</form>";
		 
// <a href=`temp4.php` onclick=`ITS_QCONTROL`> View </a><b> | </b> <a href=`#` onclick=`ITS_QCONTROL`> Edit </a><b> | </b> <a href=`#` onclick=`ITS_QCONTROL`> New </a><b>
?>
</body>
</html>

