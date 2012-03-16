<?php
echo 'testing zip <p> zip_open():';
$zip = zip_open("images.zip");
zip_read($zip);

// some code

zip_close($zip);
?> 