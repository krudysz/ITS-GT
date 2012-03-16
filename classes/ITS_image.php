<?php
//=====================================================================//
/*
  ITS_image class - render ITS image browser.

  Constructor: ITS_image(*)

  Methods: 
  * get_related_images
  * get_all_images($page)
  * save_image()

  ex. $ITS_images = new ITS_image(id,col_name);

  Author(s): Khyati Shrivastava  | Dec-18-2011
  Last Revision: Feb-20-2012, Greg Krudysz
*/
//=====================================================================//
class ITS_image {
	var $id;
	var $col_name;
	var $con;
	var $rows;
	var $columns;
	
	// Constructor //======================================================//
	function __construct($id=0,$col_name=0){
    //======================================================//
		global $db_dsn, $db_name, $tb_name, $db_table_user_state, $mimetex_path, $files_path,$host;
		//var_dump($files_path);

		$this->id = $id;
		$this->col_name = $col_name;
		$this->rows    = 2;   	    // no of rows that must be displayed
		$this->columns = 9;			// no of columns that must be displayed
		$this->files_path = $files_path;
		$this->db_name = $db_name;
		$this->tb_name = $tb_name;
		$this->host = 'localhost'; //$host;
		//mysql://root:root@tcp(localhost:3306)/its
		$dsn = preg_split("/[\/:@()]+/",$db_dsn);
		//foreach ($dsn as $value) {echo $value.'<br>';}//die();
		
		$this->db_user = $dsn[1];
		$this->db_pass = $dsn[2];
		
		//echo $this->host.' - '.$this->db_user.' - '.$this->db_pass.' - '.$this->db_name.'<br>';
		
		$this->con = mysql_connect($this->host,$this->db_user,$this->db_pass) or die('Could not Connect to DB');
		mysql_select_db($this->db_name, $this->con) or die('Could not select DB');
	}
//======================================================//
	function get_related_images(){
//======================================================//	
//	Output: Returns an array with images associated with the current question
		$query  = "SELECT qtype FROM webct WHERE id=".$this->id;
		$result = mysql_query($query, $this->con) or die(mysql_error());
		$row    = mysql_fetch_assoc($result);
		
		if($row){ $qtype = strtolower($row['qtype']); }
		else 	{ die("No results for this question ID: ".$this->id); }

		$image_query ='SELECT image_id FROM '.$this->tb_name.' WHERE id='.$this->id;
		//echo $image_query.'<br>';
		$result = mysql_query($image_query, $this->con) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$x = 0;
		if ($row && $row['image_id']!='')
			$image_array[$x++] = $row['image_id'];
		
		if($qtype=="mc"){
			$cols = '';
			for($i=1;$i<=21;$i++){
				$cols .= 'image'.$i.', ';
			}
			$cols .= 'image'.$i;
			//echo $image_array[0];
			$image_query = 'SELECT '.$cols.' FROM '.$this->tb_name.'_'.$qtype.' WHERE id='.$this->id;
			$result = mysql_query($image_query, $this->con) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			
			for($i=1;$i<=22;$i++){
				$column = 'image'.$i;
				if($row[$column]!=''){ $image_array[$x++] = $row[$column]; }
			}
		}
		else if ($qtype=="m"){
			$cols ='';
			for($i=1;$i<=26;$i++){
				$cols .= 'Limage'.$i.', Rimage'.$i.', ';
			}
			$cols .= 'Limage'.$i.', Rimage'.$i;
			$image_query = 'SELECT '.$cols.' FROM '.$this->tb_name.'_'.$qtype.' WHERE id='.$this->id;
			$result = mysql_query($image_query, $this->con) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			for($i=1;$i<=27;$i++){
				$column = 'Limage'.$i;
				if($row[$column]!='') { $image_array[$x++] = $row[$column]; }
			}
			for($i=1;$i<=27;$i++){
				$column = 'Rimage'.$i;
				if($row[$column]!=''){ $image_array[$x++] = $row[$column]; }
			}
		}
//echo '<pre>';var_dump($image_array);echo '</pre>';
		return $image_array;	
	}
//======================================================//
	function get_all_images($page){
//======================================================//		
// Output: Returns an array with images catalogued in the database		
		$con = mysql_connect($this->host,$this->db_user,$this->db_pass) or die('Could not Connect');
		mysql_select_db($this->db_name, $con) or die('Could not select DB');
		
		// Define max and min as per the page number 
		$min = $this->rows*$this->columns*$page;
		$len = $this->rows*$this->columns;
	    //echo $page.' '.$min.' '.$max;
		$query = 'SELECT id,name,dir FROM images WHERE dir NOT RLIKE "images/question" AND name NOT LIKE "sm_%"';
		//die($query);
		$result = mysql_query($query, $con) or die(mysql_error());
		
		for($x = 0; $x < mysql_num_rows($result); $x++){
				$row = mysql_fetch_assoc($result);
//echo '<pre>';var_dump($row);echo '</pre>';	
				$image_files[$x] = $row;
		}
		//echo '<pre>';var_dump($image_files);echo '</pre>';		

		return array_slice($image_files,$min,$len); //;
	}	
//======================================================//
	function image_viewer($page,$image_option){
//======================================================//
// Input:  Array with all the images
// Output: Displays all the images in the required format

	switch ($image_option) {
		case 0:
			$image_files = $this->get_related_images();
			break;
		case 1:
			$image_files = $this->get_all_images($page);
			break;
		}

		$index = 0;
		$table = '<table class="img_browser">';
		for($i=0; $index<count($image_files) && $i<$this->rows; $i++){
			$table .= '<tr>';
			//echo $srv.$files_path.$image_path.'<br>';
			for($j=0; $j<$this->columns && $index<count($image_files); $j++){ //
				//echo '<p>i='.$i.' j='.$j.' index='.$index.'</p>';
				$image_path = $this->files_path.'/'.$image_files[$index]['dir'].'/'.$image_files[$index]['name'];
				/*
				$table .= '<td id="'.$image_path.'" class="bo"><a class="small" href="#nogo">'.
						  '<img src="'.$srv.$this->files_path.$image_path.'" width="100" height="100"/>'.
						  '<img class="large" src="'.$srv.$this->files_path.'/'.$image_path.'" /></a></td>';
				*/
				//echo '<br>'.$image_path.'<br>';
				$table .= '<td id="'.$image_path.'">'.
						  '<img class="img_sm" val="'.$j.'" src="'.$image_path.'" iid="'.$image_files[$index]['id'].'"/></a></td>';				
				$index++;
			}
			$table .= '</tr>';
		}
		$table .= '</table>';
		return $table;
	}
//======================================================//
	function save_image($qid,$iid,$field_name){	
//======================================================//
	//echo "find the question type from webct Run the query and/OR let the user know that the changes have been made and the window can be closed!";
    /* When the class is created
    $obj = new ITS_images($path,$id,$col_name); */
    
	//echo $this->host.'-'.$this->db_user.'-'.$this->db_pass;//die();

	$con = mysql_connect($this->host,$this->db_user,$this->db_pass) or die('Could not Connect to DB');
	mysql_select_db($this->db_name,$con) or die('save_image: Could not select DB');

	$query  = 'SELECT qtype FROM '.$this->tb_name.' WHERE id='.$qid;
	//echo $query;die();
	$result = mysql_query($query, $con) or die(mysql_error());
	$row    = mysql_fetch_assoc($result);
	if($row) { $qtype = $row['qtype']; }
	else     { die("No results for this question ID: ".$qid); }
	
	switch ($field_name) {
    case 'image_id':
        $tb_name = $this->tb_name;
        break;
    default:
        $tb_name = $this->tb_name.'_'.strtolower($qtype);
        break;
	}

	$query = 'UPDATE '.$tb_name.' SET '.$field_name.'='.$iid.' WHERE id='.$qid;
	//die($query);
	$result = mysql_query($query, $con) or die(mysql_error());
	
	// -- redirect to Question page -- //
	/*		
	header("Location: http://" . $_SERVER['HTTP_HOST']
				. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
				. "/../Question.php?qNum=".$qid);	
	*/
	return $query;
}
//======================================================//
	function delete_image($qid,$iid,$field_name){
//======================================================//	
	echo $qid.' -- '.$iid.' -- '.$field_name;

	$con = mysql_connect($this->host,$this->db_user,$this->db_pass) or die('Could not Connect to DB');
	mysql_select_db($this->db_name,$con) or die('delete_image: Could not select DB');

	$query  = 'SELECT qtype FROM '.$this->tb_name.' WHERE id='.$qid;
	//echo $query;
	$result = mysql_query($query, $con) or die(mysql_error());
	$row    = mysql_fetch_assoc($result);
	if($row) { $qtype = $row['qtype']; }
	else     { die("No results for this question ID: ".$qid); }
		
	switch ($field_name) {
    case 'image_id':
        $tb_name = $this->tb_name;
        break;
    default:
        $tb_name = $this->tb_name.'_'.strtolower($qtype);
        break;
	}
			
	$query  = 'UPDATE '.$tb_name .' SET '.$field_name.'=0 WHERE id='.$qid;
	//die($query);
	$result = mysql_query($query, $con) or die(mysql_error());
}
//======================================================//
	function upload_image($qid,$name,$dir,$field_name){	
//======================================================//
	//
	echo $qid.'-'.$name.'-'.$dir.'-'.$field_name.'<br>';

	$con = mysql_connect($this->host,$this->db_user,$this->db_pass) or die('Could not Connect to DB');
	mysql_select_db($this->db_name,$con) or die('Could not select DB');
	
	$query  = 'SELECT qtype FROM '.$this->tb_name.' WHERE id='.$qid;
	$result = mysql_query($query, $con) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	if($row) { $qtype = $row['qtype']; }
	else     { die("No results for this question ID: ".$id); }
	
	switch ($field_name) {
    case 'image_id':
        $tb_name = $this->tb_name;
        break;
    default:
        $tb_name = $this->tb_name.'_'.strtolower($qtype);
        break;
	}
	 
	$query  = 'INSERT IGNORE INTO images (name,dir) VALUES("'.$name.'","'.$dir.'")';
	//die($query);
	$result = mysql_query($query,$con) or die(mysql_error());
	
	$iid = mysql_insert_id();
	
	if ($iid==0) {
		// uploaded image already exists in /images/question/qid
		$query  = 'SELECT id FROM images WHERE name="'.$name.'" AND dir="'.$dir.'"';
		$result = mysql_query($query, $con) or die(mysql_error());
		$row    = mysql_fetch_assoc($result);
		if($row) { $iid = $row['id']; }
		else     { die("No results for this question ID: ".$id); }
	}		
	
	  //echo '<br>'.$iid.'<br>';die($query);
	  $query = 'UPDATE '.$tb_name.' SET '.$field_name.'='.$iid.' WHERE id='.$qid;
	  //echo '<p>'.$query.'</p>'; die($query);
	  $result = mysql_query($query,$con) or die(mysql_error());
	//echo "Location: http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/Question.php?qNum=".$qid;	
	//$query = 'UPDATE webct SET image_id="'.$ques_dir.$filename.'" WHERE id='.$qid;  //die($query);

	//echo $_SERVER['HTTP_HOST'].' ---- '.rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	//die();
	// -- redirect to Question page -- //	
	header("Location: http://" . $_SERVER['HTTP_HOST']
				. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
				. "/../Question.php?qNum=".$qid);
}
//======================================================//
}
?>
