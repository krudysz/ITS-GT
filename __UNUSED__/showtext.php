<?php
   header("Cache-Control: no-cache, must-revalidate");     // Must do cache-control headers
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");       // or IE will pull from cache 100% of time (which is really bad)

   //readfile($doc);                                       // send file to browser
                                                           // $doc is whatever was passed as doc= in the url.
                                     											 // showtext.php?doc=hello.txt will open hello.txt.
echo 'danuta';

var_dump($_GET);
//echo $kk;
//$args = $_GET['k']);
//echo $args;
?>
