<?php
//include paths and files
	include_once("/database/class_query.php");
	include_once("/forms/class_page.php");
	include_once("/database/class_rating_query.php");
	include_once("/forms/class_form_data.php");				
//connect to database
	$id=1;	
	$dbname="timer";
	$server="localhost";
	$username="root";
	$password="";
	$tbname="timer";	
	
	$db=new Query($server,$username,$password,$dbname);
	$ret=$db->execute("SELECT * FROM ".$tbname." where id=$id");
	$q=$db->get();
	//echo "time out=".$q['test'];
	//set page template
	$p=new Page();
	$p->startTemplate("Rating System","");
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="submit" value="timein" name="timein">
</form>

<?php
	$timein_status='unchecked';
	if(isset($_REQUEST['timein']))
	{
		echo "timein=";
		$val=$_SERVER['REQUEST_TIME'];
		echo $val;
		$db->execute(("UPDATE timer SET test=$val,timein=0,timeout=0,WHERE id=$id"));
	}

 //close the page template
	$p->closeTemplate();
?>