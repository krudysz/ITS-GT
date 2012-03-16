<?php
//=====================================================================//
/*
Left Hand Menu: "Section Info" >> Student Memberships
 * 
students - creates student accounts, courses, sections.
								
	 Author(s): Greg Krudysz | Aug-25-2010
	 Last Update: Jan-12-2012
//---------------------------------------------------------------------*/
//  ALTER TABLE users MODIFY section INT(11);

//=====================================================================//
include("config.php");
include "file2SQL.php";
include "STATS.php";
require_once($MDB2_path.'MDB2.php');

// return to login page if not logged in
// abort_if_unauthenticated();
//=============================================================
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script src="ITS.js"></script>
</head>
<link rel="stylesheet" href="css/ITS.css">
<link rel="stylesheet" href="css/ITS_navigation.css">
<link rel="stylesheet" href="css/ITS_question.css">
<link rel="stylesheet" href="css/ITS_students.css">
<link rel="stylesheet" href="css/pagestyles.css">
<body>
<DIV class="Heading"> STUDENTS </DIV>
<?php
echo '<pre>';var_dump($_POST);echo '</pre>';
//=============================================================
// CHECK FOR RECORDS
//=============================================================
// Instantiate the DataGrid
//$datagrid =& new Structures_DataGrid();

// Setup your database connection
//$options = array('dsn' => $db_dsn);

//----------------------//
// CLASS
//----------------------//
//$test = $datagrid->bind('SELECT table_name FROM class',$options);

//if ($datagrid->getRecordCount()==0){ $msg_ini = "No class records exist.";} 
//else 															 { $msg_ini = ''; }
$msg_ini = '';
$msg = "Adding class and creating resources ... please wait";
	echo '<p><span class="TextMessage" style="text-decoration:blink" id="Students_Message">'.$msg_ini.'</span><p>'
			."<FORM ID=add_class_Form ACTION=students.php METHOD=post>"
			."<INPUT TYPE=file name=new_class>&nbsp;&nbsp;"
			."<INPUT TYPE=BUTTON onclick=\"ITS_form_submit(this.name,'".$msg."')\" VALUE='Add Class' NAME='add_class_Form'>"
			."</form>";

if (0) { //($datagrid->getRecordCount()>0){			
	 // Connect to DB
	 global $db_dsn, $db_table_user_state;
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
	 // Obtain top class name
	 $course =& $mdb2->queryAll("SELECT * FROM course"); 
	 //$ITS_table = new ITS_table('tableA',2,2,array(1,2,3,4),array(20,30));
	 
	 $tb_course = '<table class="ITS_course">' .
	              '<tr><th>NAME</td><th>TERM</td><th>YEAR</td><th></th></tr>';
	 for ($i=0; $i<count($course); $i++){
	    //$query = "SELECT * FROM users WHERE status='".$class_names[$i]."'";
	 		//$test = $datagrid->bind($query,$options);
	    //if (PEAR::isError($test)) { echo $test->getMessage(); }
			
			$name = preg_replace('/_/',' ',$course[$i][1]);
			$del =  '<form id="delete_class_Form" action=students.php method="POST">' .
      			    '<INPUT TYPE=HIDDEN name="delete class" VALUE="'.$course[$i][1].'">'.
					'<INPUT class=ITS_button TYPE=SUBMIT ONCLICK=ITS_form_submit(this.name,"'.$msg.'") NAME=delete_class_Form VALUE="delete">'.
					'</form>';
			$tb_course .= '<tr><td>'.$name.'</td><td>'.$course[$i][2].'</td><td>'.$course[$i][3].'</td><td>'.$del.'</td></tr>';
			
			/*
	 		$msg = 'Deleting course '.$class_names[$i].' and associated resources ... please wait';
	 		echo '<div class="TextMessage" id="Students_Message"></div>';
	 		echo '<form id="delete_class_Form" action=students.php method="POST">' .
	  	    '<p><DIV class=SubHeading>'.$class_names[$i].'&nbsp;&nbsp;&nbsp;&nbsp;'.
			'<INPUT TYPE=HIDDEN name="delete class" VALUE="'.$class_names[$i].'">'.
	 	    '<INPUT TYPE=SUBMIT ONCLICK=ITS_form_submit(this.name,"'.$msg.'") NAME=delete_class_Form VALUE=delete>'.
	 	    '</DIV></form>';
	 		*/
	 		// Print the DataGrid with the default renderer (HTML Table)
	    //$datagrid->setRenderer('HTMLTable');
	    //$test = $datagrid->render();
	  	//if (PEAR::isError($test)) {  echo $test->getMessage();  }	
	 }	
	 $tb_course .= '</table>';
	 echo $tb_course;
	 //$query = "SELECT 1 FROM stats_1 LIMIT 1";
	 $mdb2->disconnect();
}				
//=============================================================
// NEW CLASS
//=============================================================
// var_dump($_POST); echo "<p>"; die();
$current_term = 'Spring_2012'; //'Fall_2011';   // BMED6787

if ( isset($_POST['new_class']) ){
	
	 //echo 'hello'; die();
	 // Connect to DB and ...
	 global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	 
		
		$path = $_POST['new_class'];
		$path_parts = pathinfo($path);
		$filename = $path_parts['basename'];

		//echo "|".$filename."|<p>";  //die();
		
		list($fname,$ext) = split('[.]',$filename);
		list($term,$year) = split('[_]',$fname);
		
		//echo "|".$fname."|".$ext."|".$term."|".$year."|<p>";  die();
		// $db_name="class_" . $fname;
		
		// Register new class in classes table
		/*
		$query = 'INSERT INTO class (table_name,term,year,filename,no_students) VALUES ('
		. $mdb2->quote($fname,   'text')   .','
		. $mdb2->quote($term, 	 'text')   .','
		. $mdb2->quote($year,    'integer').','
		. $mdb2->quote($filename,'text')   .','
		. $mdb2->quote(150,      'integer').')';
		*/
		$query = 'INSERT INTO course (name,term,year,filename) VALUES ('
        		. '"'.$fname.'",'
            . '"'.$term.'",'
        		. $year.','
        		. '"'.$filename.'")';
		$res =& $mdb2->exec($query);
		
		//echo "<p><DIV class=TextMessage>Loading student data </span><p>";
		// get number of current users
		$res_users =& $mdb2->queryCol('SELECT id FROM users ORDER BY id');
        $num_users = $res_users[count($res_users)-1]+1;
		
		// Write new users to USERS table
		$filepath = "/var/www/html/SQL/users/" . $filename;
		echo '<p>'.$filepath.'<p>';
		$handle   = fopen($filepath,"r");
		
		$returning_users = array();
		$status = $current_term;
		$tb = '<table>';
		//$dataT = fgetcsv($handle,300, ",");var_dump($dataT);die('now');
		while (($data = fgetcsv($handle,300, ",")) !== FALSE) {
					list($lastname,$firstname) = split('[,]', $data[0]);
					if (empty($data[2])) { $section = 1;                                       }
					else 								 { $section = intval(preg_replace('/L/','',$data[2])); }
					/*
					$query = 'INSERT INTO users VALUES ('
					. $mdb2->quote($num_users,  'integer').', '
    			. $mdb2->quote($firstname,  'text')   .', '
					. $mdb2->quote($lastname,   'text')   .', '
					. $mdb2->quote($data[0],    'text')   .', '
					. $mdb2->quote($data[0],    'text')   .', '
    			. $mdb2->quote($fname,      'text')   .') ';
					*/
					/*
    			switch (strtolower($data[3])) {
        	//----------------------//
        		case 'student':
        			 $status = $fname;
        		break;
        		case 'teaching assistant':
						case 'head ta':
        		   $status = 'ta';
						break;
						case 'instructor':
						   $status = 'instructor';
						break;
					//----------------------//	
					}*/
					$users[] = $data[1];
		
    			$query = 'SELECT id,status,first_name,last_name,section FROM users WHERE username="'.$data[1].'"';
				//echo '<p>'.$query.'</p>';	
					$res_u =& $mdb2->queryRow($query);
					
					// IF user already exists => update semester, ELSE insert new
					if (!empty($res_u[0])){ 				
						// IF user = CURRENT semester => message, ELSE UPDATE to CURRENT
						if ($res_u[1]==$current_term) { 
						   	 
							 if (empty($res_u[4])) {  
							    $color = 'green';
									if (!empty($section)) {
  								  $query = 'UPDATE users SET section='.$section.' WHERE id='.$res_u[0];
  							    $res =& $mdb2->exec($query);
									}
							 } else {
							    $color = 'black';
							    $query = 'current';
							 }							 
						   $tb .= '<tr><td>'.$res_u[0].'</td><td style="color:'.$color.'">'.$query.'</td></tr>';
						} else { 
						   $color="blue";  
						   $query = 'UPDATE users SET status="'.$current_term.'",section='.$section.' WHERE id='.$res_u[0];
						   $tb .= '<tr><td>'.$res_u[0].'</td><td style="color:'.$color.'">'.$query.'</td></tr>';
							 $res =& $mdb2->exec($query);
						}
					} 
					else {
					  $student_id = $num_users++;
  					$query = 'INSERT INTO users (id,first_name,last_name,username,password,status,section) VALUES ('
						. $student_id.', '
						. '"'.$firstname.'", '
						. '"'.$lastname.'", '
						. '"'.$data[1].'", '
						. '"'.$data[1].'", '
						. '"'.$status.'", '
						. $section.')';
      			//echo '<p style="color:blue">'.$query.'</p>';
						$res   =& $mdb2->exec($query);
						$color = 'red';
						$tb   .= '<tr><td>'.$student_id.'</td><td style="color:'.$color.'">'.$query.'</td></tr>';
						
            $tb_name = "stats_" . $student_id;
            //echo $student_id.$db_name.$tb_name;
            $pstats = new STATS($student_id,$db_name,$tb_name);
            $pstats->load();
            
		      }
					//$tb .= '<tr><td>'.$res_u[0].'</td><td style="color:'.$color.'">'.$res_u[1].'</td></tr>';
   }
		$num_users++;
		$tb .= '</table>';
		fclose($handle);
		
		echo $tb.'<h3>USERS: '.count($users).'</h3>';
		
	  /*----------------------------------------------------------/
		| 2. for EACH user create CPT & STATS table
		/*---------------------------------------------------------*/
		//die($fname);
		/*
		$query = "SELECT * FROM users WHERE status='".$fname."'";
		echo $query;
		//$res =& $mdb2->queryCol($query);
		$student_id = $res[0];		

		foreach ($res as $student_id){
		//echo $student_id; die();
		//echo $student_id.' -- '.$returning_users.";<p>"; die();
		
		 if (in_array($student_id,$returning_users)) {
		    echo 'did not create table for id='.$student_id.'<p>';
			}
			else {
  			//$tb_name = "cpt_" . $student_id;
  			//$pcpt = new CPT($student_id,"ch7_iCPT.txt",$db_name,$tb_name);
  			//$pcpt->load();
/*
  			$tb_name = "stats_" . $student_id;
  			$pstats = new STATS($student_id,$db_name,$tb_name);
  			$pstats->load();
*/				/*
			}
		}
*/
		$mdb2->disconnect();
}
//=============================================================
// DELETE CLASS
//=============================================================
if ( isset($_POST['delete_class']) ){
	 global $db_dsn, $db_table_user_state;
	 
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
	 $class_name = $_POST['delete_class'];
   //echo $class_name;  die();
	 // delete all user CPT tables
	 $query = "SELECT * FROM users WHERE status='".$class_name."'";
	 //echo $query;
	 $users =& $mdb2->queryCol($query);
	
	 foreach ($users as $student_id){
	 	  // delete CPT, STATS tables
			$query = "DELETE FROM cpt_'"   .$student_id."'";
			$res =& $mdb2->exec($query);
			$query = "DELETE FROM stats_'" .$student_id."'";
			$res =& $mdb2->exec($query);
			$query = "DROP TABLE IF EXISTS cpt_" .$student_id.", stats_" .$student_id;		
			$res =& $mdb2->exec($query);
			//echo "<p>".$query."<p>";
	 }
	
	 // delete class users from 'users' table
	 $query = "DELETE FROM users WHERE status='".$class_name."'";
	 //echo $query.'<p>';
	 $res =& $mdb2->exec($query);
	
	 // delete class from 'class' table
	 $query = "DELETE FROM course WHERE name='".preg_replace('/\s/','_',$class_name)."'";
	 echo $query;
	 $res =& $mdb2->exec($query);
	 $mdb2->disconnect();
}
?>
</body>
</html>
