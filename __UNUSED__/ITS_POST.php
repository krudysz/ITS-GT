<?php

$stockLink = 'http://gatech.edu/directories/q';
//http://finance.google.com/finance/historical?q=LON:VOD&startdate=Oct+1,2008&enddate=Oct+9,2008&output=csv

$c = getStockSite($stockLink);
echo $c;

function getStockSite($stockLink){
   
   if ($fp = fopen($stockLink, 'r')) {
      $content = '';
        
      while ($line = fread($fp, 1024)) {
         $content .= $line;
      }
   }
   return $content;  
}

/*
if (isset($_POST['survey'])){
 echo 'HELLO-OO';
 die();
}else{
echo 'NOTHIN';
}
*/
?>

