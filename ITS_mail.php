<?php

ini_set("SMTP","smtp.its.vip.gatech.edu" );
ini_set('sendmail_from', 'user@its.vip.gatech.edu'); //Suggested by "Some Guy"

$Name = "Da Duder"; //senders name
$email = "email@its.vip.gatech.edu"; //senders e-mail adress
$recipient = "krudysz@gmail.com"; //recipient
$mail_body = "The text for the mail..."; //mail body
$subject = "Subject for reviever"; //subject
$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields



mail($recipient, $subject, $mail_body, $header); //mail command :)
?>
