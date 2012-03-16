  <?php
  $error = '';
   if (isset($_POST["upload"])) {  

        //Get the file information  
        $userfile_name = $_FILES["image"]["name"];  
        
              echo  $userfile_name.'<p>';
 	    //die('in up ...');
        $userfile_tmp = $_FILES["image"]["tmp_name"];  
        $userfile_size = $_FILES["image"]["size"];  
        $filename = basename($_FILES["image"]["name"]);  
        $file_ext = substr($filename, strrpos($filename, ".") + 1);  

        //Only process if the file is a JPG and below the allowed limit  
        /*
       if((!emptyempty($_FILES["image"])) && ($_FILES["image"]["error"] == 0)) {  
           if (($file_ext!="jpg") && ($userfile_size > $max_file)) {  
               $error= "ONLY jpeg images under 1MB are accepted for upload";  
        } 
        *
     }else{  
         $error= "Select a jpeg image for upload";  
    }   */ 
      //Everything is ok, so we can upload the image.  
    if (strlen($error)==0){  
  
        if (isset($_FILES["image"]["name"])){  
    $large_image_location = '/var/www/html/TEMP/';
    $name = $filename;
    $uploads_dir = '/TEMP';
    
   // 
   echo $userfile_tmp.' +  '.$large_image_location.'<p>|</p>';

    $tmp_name = $_FILES["image"]["tmp_name"];
    $name = $_FILES["image"]["name"];
    echo $tmp_name.' +  '."$uploads_dir/$name".'<p>|</p>';
    move_uploaded_file($tmp_name, "$uploads_dir/$name");
             //$flag = move_uploaded_file($userfile_tmp, $large_image_location);  
              //echo 'flag: '.bool($flag);
              //chmod ($large_image_location, 0777);  
             
   die('die here');
             $width = getWidth($large_image_location);  
            $height = getHeight($large_image_location);  
             //Scale the image if it is greater than the width set above  
            if ($width > $max_width){  
                  $scale = $max_width/$width;  
                $uploaded = resizeImage($large_image_location,$width,$height,$scale);  
             }else{  
                 $scale = 1;  
                  $uploaded = resizeImage($large_image_location,$width,$height,$scale);  
            }  
             //Delete the thumbnail file so the user can create a new one  
              if (file_exists($thumb_image_location)) {  
                  unlink($thumb_image_location);  
              }  
         }  
        //Refresh the page to show the new uploaded image  
          header("location:".$_SERVER["PHP_SELF"]);  
         exit();  
     }  
 }  
 ?>
