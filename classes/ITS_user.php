<?php
//=====================================================================//
/*
ITS_user - display, add, and delete user account.

		Constructor: ITS_user(name)
		
								 ex. $ITS_user = new ITS_users();
								
	 Author(s): Greg Krudysz |  Feb-04-2011
	 Last Revision: Apr-07-2011
//-----------------------------------------*/
/*
CALLS: 

			 require_once("classes/ITS_user.php");
			 $x = new ITS_book($id);
			 $o = $x->main();
*/
//=====================================================================//

class ITS_user {

		public $name;
		public $chapter;
		public $meta;	
		
	  //public function get_lab_active()        { return $this->lab_active;  }
	
	//=====================================================================//
  	   function __construct($name) {
	//=====================================================================//
	  global $db_dsn,$db_name,$tb_name,$db_table_user_state;
		
		$this->name    = $name;
		$this->db_dsn  = $db_dsn;
   } 
	//=====================================================================//
	   function main(){
	//=====================================================================//
	  // 
		$this->name = 1;
		$main_str = $this->fcn($this->name);	
				
		return $main_str;
	}
	//=====================================================================//
	function add_user($first_name,$last_name,$username,$status) {
  //=====================================================================//	
	  global $db_dsn, $db_table_user_state;
	  $mdb2 =& MDB2::connect($db_dsn);
	  if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
		
		$query = 'SELECT max(id)+1 FROM users';
		$res   =& $mdb2->query($query);
    $id    = $res->fetchCol();
		//print_r($id);
	
		$query1 = 'INSERT INTO users (id,first_name,last_name,username,password,status) VALUES ('
					       .$id[0].', '
						. '"'.$first_name.'", ' 
  					. '"'.$last_name.'", ' 
  					. '"'.$username.'", ' 
  					. '"'.$username.'", ' 
						. '"'.$status.'")';
						
		$res =& $mdb2->exec($query1);
		
		$query2 = 'CREATE TABLE IF NOT EXISTS stats_'.$id[0].' (id int NOT NULL AUTO_INCREMENT, question_id int, concept_id int, current_chapter int, answered varchar(1024), score float, rating int, comment varchar(128),tags varchar(256), epochtime int(10) unsigned, duration int(11), PRIMARY KEY (id), FOREIGN KEY (concept_id) REFERENCES concept (id) ON DELETE SET NULL )';
		$res    =& $mdb2->exec($query2);
		$label  = '<b>ADDED USER:  <a href="Profile.php?class='.$status.'&sid='.$id[0].'&ch=1">'.$id[0].'</a></b>';
		$str    = $label.'<p><div class="ITS_SQL">'.$query1.'</div>'.
				                '<div class="ITS_SQL">'.$query2.'</div></p>';
		$mdb2->disconnect();				
	  return $str;
	}
	//=====================================================================//
	function render_courses() {
  //=====================================================================//
	  global $db_dsn, $db_table_user_state;
	  $mdb2 =& MDB2::connect($db_dsn);
	  if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
    $msg = "Adding class and creating resources ... please wait";
	  $m   = '<p><span class="TextMessage" style="text-decoration:blink" id="Students_Message"></span><p>';
	  $add = '<FORM id="add_class_Form" action="Course.php" method="POST">'
    			."<INPUT TYPE=file name=new_class>&nbsp;&nbsp;"
    			."<INPUT TYPE=BUTTON class=ITS_button onclick=\"ITS_form_submit(this.name,'".$msg."')\" VALUE='Add' NAME='add_class_Form'>"
    			."</form>";
			
	  // Obtain top class name
	  $course =& $mdb2->queryAll("SELECT * FROM course"); 
	 	 
	  $tb_course = '<table class="ITS_course">' .
	               '<tr><th>NAME</th><th>TERM</th><th>YEAR</th><th></th></tr>';
	  for ($i=0; $i<count($course); $i++){		
			 $name = preg_replace('/_/',' ',$course[$i][1]);
			 $del = '<form id="delete_class_Form" action="Course.php" method="POST">' .
      			  '<INPUT TYPE=HIDDEN name="delete class" VALUE="'.$course[$i][1].'">'.
      	 	    '<INPUT class="ITS_button" TYPE=SUBMIT ONCLICK=ITS_form_submit(this.name,"'.$msg.'") NAME=delete_class_Form VALUE="delete">'.
      	 	    '</form>';
			 $tb_course .= '<tr><td>'.$name.'</td><td>'.$course[$i][2].'</td><td>'.$course[$i][3].'</td><td>'.$del.'</td></tr>';
	  }	
	  $tb_course .= '<tr><td colspan="4">'.$add.'</td></tr>' .
	 						   '</table>';
	  $str = $tb_course;
	 
	  $mdb2->disconnect();
		return $str;
	}
	//=====================================================================//
	function delete() {
  //=====================================================================//
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
	//=====================================================================//
	/*function new_class() {
  //=====================================================================//
    // var_dump($_POST); echo "<p>"; die();
    $current_term = 'Spring_2011';
    if ( isset($_POST['new_class']) ){
	 // Connect to DB and ...
	 global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}	 
		
		$path = $_POST['new_class'];
		$path_parts = pathinfo($path);
    $filename = $path_parts['basename'];

		 //echo "|".$filename."|<p>";  die();
		
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
/*		$query = 'INSERT INTO course (name,term,year,filename) VALUES ('
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
		$filepath = "sql/" . $filename;
		$handle   = fopen($filepath,"r");
		
		$returning_users = array();
		$status = $current_term;
		$tb = '<table>';
		while (($data = fgetcsv($handle,300, ",")) !== FALSE) {
					list($lastname,$firstname) = split('[,]', $data[0]);
					$section = intval(preg_replace('/L/','',$data[2]));
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
	/*				$users[] = $data[1];
		
    			$query = 'SELECT id,status,first_name,last_name,section FROM users WHERE username="'.$data[1].'"';
					$res_u =& $mdb2->queryRow($query);
					
					// IF user already exists => update semester, ELSE insert new
					if (!empty($res_u[0])){ 				
						// IF user = CURRENT semester => message, ELSE UPDATE to CURRENT
						if ($res_u[1]==$current_term) { 
						   
							 
							 if (empty($res_u[4])) {  
							    $color = 'green';
								  $query = 'UPDATE users SET section='.$section.' WHERE id='.$res_u[0];
							    $res =& $mdb2->exec($query);
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
						$res =& $mdb2->exec($query);
						$color = 'red';
						$tb .= '<tr><td>'.$student_id.'</td><td style="color:'.$color.'">'.$query.'</td></tr>';
						
            $tb_name = "stats_" . $student_id;
            $pstats = new STATS($student_id,$db_name,$tb_name);
            $pstats->load();
		      }
					//$tb .= '<tr><td>'.$res_u[0].'</td><td style="color:'.$color.'">'.$res_u[1].'</td></tr>';
   }
		$num_users++;
		$tb .= '</table>';
		fclose($handle);
		
		echo $tb.'<h3>USERS: '.count($users).'</h3>';
		*/
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
		$mdb2->disconnect();
}*/
  //=====================================================================//
}
/*
if (0) { //($datagrid->getRecordCount()>0){			
	 // Connect to DB
	 global $db_dsn, $db_table_user_state;
	 $mdb2 =& MDB2::connect($db_dsn);
	 if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
	
	 // Obtain top class name
	 $course =& $mdb2->queryAll("SELECT * FROM course"); 
	 //$ITS_table = new ITS_table('tableA',2,2,array(1,2,3,4),array(20,30));
	 
	 $tb_course = '<table class="ITS_USERS">' .
	              '<tr><th>NAME</td><th>TERM</td><th>YEAR</td><th></th></tr>';
	 for ($i=0; $i<count($course); $i++){
	    //$query = "SELECT * FROM users WHERE status='".$class_names[$i]."'";
	 		//$test = $datagrid->bind($query,$options);
	    //if (PEAR::isError($test)) { echo $test->getMessage(); }
			
			$name = preg_replace('/_/',' ',$course[$i][1]);
			$del =  '<form id="delete_class_Form" action=USERS.php method="POST">' .
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
/*
	 }	
	 $tb_course .= '</table>';
	 echo $tb_course;
	 //$query = "SELECT 1 FROM stats_1 LIMIT 1";
	 $mdb2->disconnect();
}
*/
?>
