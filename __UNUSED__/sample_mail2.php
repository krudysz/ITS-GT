<?php

$Name = "users's name"; 									// senders name
$email = "email@adress.com"; 							// senders e-mail adress
$recipient = "krudysz@gmail.com"; 				// recipient
$mail_body = "The text for the mail...";  // mail body
$subject = "Subject for reviever"; 				// subject
$header = "From: ". $Name . " <" . $email . ">\r\n"; // optional headerfields

ini_set('sendmail_from', 'gte269x@itsdev1.vip.gatech.edu'); // Suggested by "Some Guy"
mail($recipient, $subject, $mail_body, $header); 						// mail command :)
?>
