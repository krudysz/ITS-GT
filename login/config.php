<?php
error_reporting(E_ALL);
define('INCLUDE_DIR','_include/');

/*========= LOCAL  =========*/
//$db_name = 'its';
//$db_dsn  = 'mysql://root:csip@tcp(localhost:3306)/'.$db_name;

//$MDB2_path = 'C:/wamp/bin/php/php5.3.0/pear/';
//$mimetex_path = '/cgi-bin/mimetex.exe?';
/*========= ITS.ECE.GATECH.EDU =========*/
//$db_name = 'ece2025';
//$db_dsn  = 'mysql://ece2025:5202ece@babeldev.ece.gatech.edu/'.$db_name;
//$mimetex_path = '/cgi-bin/mimetex.exe?';
/*========= ITS.VIP.GATECH.EDU  =========*/
$db_name = 'its';
$db_dsn  = 'mysql://root:csip@tcp(localhost:3306)/'.$db_name;
$MDB2_path = '';
$mimetex_path = '/cgi-bin/mimetex.cgi?';
/*==========================*/

$db_table_users 		 = 'users';
$db_table_user_state = 'stats_';
$db_table_user_cpt   = 'cpt_';
$db_table_question 	 = 'question';
$tb_name 						 = 'webct';

$question_dir 		 = "question";
$question_file_ext = 'html';
$answer_dir 			 = "answer";
$answer_file_ext 	 = 'html';
$BNT_dir 					 = "Debug";

global $db_dsn,$db_table_user_state,$db_table_user_cpt,$db_name,$tb_name;
?>
