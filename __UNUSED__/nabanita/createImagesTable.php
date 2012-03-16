<?php
	
	/**
	 * http://tycoontalk.freelancer.com/php-forum/41811-list-files-in-directory-sub-directories.html
	 * http://www.php-mysql-tutorial.com/wikis/php-tutorial/determine-a-file-extension-using-php.aspx
	 */
	
	$ITS_FILES_PATH = "C:\wamp\www\ITS\ITS_Spring_2011\ITS_FILES";
	
function ListFiles($dir) {
    if($dh = opendir($dir)) {
        $files = Array();
        $inner_files = Array();
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($dir . "/" . $file)) {
                    $inner_files = ListFiles($dir . "/" . $file);
                    if(is_array($inner_files)) 
						$files = array_merge($files, $inner_files); 
                } else {
                    array_push($files, $dir . "/" . $file);
                }
            }
        }
        closedir($dh);
        return $files;
    }
}

$fileCount = 0;
$fileArray = array();
foreach (ListFiles($ITS_FILES_PATH) as $key=>$file){
	$ext = substr($file, strrpos($file, '.') + 1);
	if($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif") {
		$temparr = explode("\\", $file);
		$fileArray[] = $temparr[count($temparr)-1];
		$fileCount++;
	}
}

//echo '<pre>';
//print_r($fileArray);
//echo '</pre>';


// connect to database
//$mdb2 =& MDB2::connect($db_dsn);
//if (PEAR::isError($mdb2)){throw new Exception($this->mdb2->getMessage());}
$host     = "localhost";
$user     = "root";
$pass     = "";
$database = "its";

$linkID = mysql_connect($host,$user,$pass) or die("Could not connect to host.");
mysql_select_db($database,$linkID) or die("Could not find database.");
				
for($i=0;$i<$fileCount;$i++) {
	$temparr = explode("/",$fileArray[$i]);
	$fileName = $temparr[count($temparr)-1];
	$filePath = substr($fileArray[$i],0,-strlen($fileName));
		
	$ins_query = "INSERT INTO `images` (name, path) VALUES ('".$fileName."', '".$filePath."')";
	mysql_query($ins_query) or die("Could not save the image entry in the database");
}

mysql_close($linkID);

?>