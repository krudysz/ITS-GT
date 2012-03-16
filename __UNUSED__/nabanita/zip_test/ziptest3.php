<?php
$filename = "test.zip"; 								// escapeshellarg($_GET['filename']);
$destination_folder = '/root/user/';    // escapeshellarg($_GET['destination_folder']);

echo shell_exec ("unzip -ou $filename -d $destination_folder");
//echo shell_exec ("unzip -ou $filename -d $destination_folder");
?>
