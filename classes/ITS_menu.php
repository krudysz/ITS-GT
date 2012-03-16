<?php
//=====================================================================//
/*
ITS_menu class - lab configuration file.

		Constructor: ITS_menu($user_info,$lab_info,$db_info)
		
		where:
		        $user_info = array($db_dsn,$db_name,$tb_name)
						$lab_info  = array($lab_title,$lab_intro,$lab_num,$lab_sec,$lab_sec_headers, ...
						                   $lab_sec_parts_headers,$lab_sec_parts,$_lab_sec_ques, ...
															 $_lab_sec_ans_rows,$lab_coda)
						$db_info   = array($db_question_id)
	
		ex. $ITS_menu = new ITS_menu(90001,"its","user_cpt");
								
	 Author(s): Greg Krudysz |  Sep-3-2009
	 Last Update: 
*/
//=====================================================================//
class ITS_menu
{
  private $db_dsn;
	private $db_name;
	private $tb_name;
	
  //=====================================================================//
  function __construct(){
  //=====================================================================//

	}
	//=====================================================================//
	   function main(){
	//=====================================================================//
	 $common = '<a href = "logout.php">Logout</a>';	
	 $menu = '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'.$common.'</form>';
	 
	 return $common;
	}
//=====================================================================//
} //eo:class
//=====================================================================//
/*
<?php
// GET STATUS
// If menu selected ELSE session
if(isset($_POST['ITS_current_status'])){
    $status = $_POST['ITS_current_status'];
} else {
		$status = $_SESSION['user']->status();
}
// DISPLAY MENU FOR STATUS
//echo $status;

$spacer = '&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;';
/*
$common = '<a href = "Profile.php">Profile</a>'.$spacer
         .'<a href = "logout.php">Logout</a>';			 

$common = '<a href = "logout.php">Logout</a>';
switch ($status) {
  //-------------------------
  case 'admin':
  //-------------------------
				 $menu1_str = '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'
		     .'<a href="Concept.php">Concepts</a>'.$spacer
		     .'<a href="Question.php">Questions</a>&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;'
		 		 .'<select width="200px" name="ITS_current_status" onchange="ITS_change_status()"><option> admin </option><option> student </option></select>'
				 .$spacer.$common
				 .'</form>';
   break;
  //-------------------------
  case 'admin_all':
  //-------------------------
		 echo "<form action=admin/webct.php name=menu_form method=post target=main_frame>"
		 		 ."<input type=hidden name=db>"
  			 ."</form>";
	
	   echo "<p><fieldset><legend><span class=ITS_QCONTROL>Administrator</span></legend>";
		 // echo "<div style=`background:#87CEEB`>";  //D8D8D8
		 echo "<a href = admin/admins.php   target = main_frame>Admins</a> |";
		 echo "<a href = admin/students.php target = main_frame>Students</a> | ";
		 echo "<a href = admin/concepts.php target = main_frame>Concepts</a> | ";
		 echo "<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct >WebCT</a>: "
		 		 ."<b>[ </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_s >s</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_m >m</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_p >p</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_c >c</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_mc >mc</a>"
				 ."<b> ]</b> | "; 
		 echo "<a href = admin/Question_index.php target = main_frame>Questions</a>&nbsp;&nbsp;"
				 ."<b>[ </b>" 
		     ."<a href = admin/Question_New.php target = main_frame> new</a>"
				 ."<b> ]</b> | ";
		 echo "<a href = LabView/ITS1.html target = main_frame>GUI</a> | ";
		 echo "<a href = dumb-test.html target = main_frame>test</a> | ";
		 echo "<a href = game/question.php target = main_frame>game</a> | ";	
		 echo "<a href = game/test.php target = main_frame>test</a> | ";
		 echo "<a href = game/test1.php target = main_frame>test1</a> | ";	
		 echo "<a href = game/test2.php target = main_frame>test2</a> | ";
		 echo "<a href = game/lab1.php target = main_frame>Lab 1</a> | ";
		 echo "<a href = game/lab2.php target = main_frame>Lab 2</a> | ";
		 echo "<a href = game/lab3.php target = main_frame>Lab 3</a> |";
		 echo "<a href = game/lab4.php target = main_frame>Lab 4</a> | ";
		 echo "<a href = game/lab5.php target = main_frame>Lab 5</a> | ";
		 echo "<a href = game/lab6.php target = main_frame>Lab 6</a> | ";
		 echo "<a href = game/lab7.php target = main_frame>Lab 7</a> | ";
		 echo "<a href = game/lab8.php target = main_frame>Lab 8</a> | ";
		 echo "<a href = game/lab10.php target = main_frame>Lab 10</a> | ";
		 echo "<a href = game/lab11.php target = main_frame>Lab 11</a> | ";
		 echo "<a href = game/lab12.php target = main_frame>Lab12A</a> | ";
		 echo "<a href = game/lab13.php target = main_frame>Lab12B</a> | ";
		 echo "<a href = ITS_lab07.php target = main_frame>Lab7</a> | ";
		 echo "<a href = ITS_lab10.php target = main_frame>Lab10</a> | ";
		 echo "<a href = ITS_lab.php target = main_frame>Warm-up</a> | ";
		 echo "<a href = LabView/remote/ITS_remote.php target = main_frame>LV</a> | ";
		 echo "<p><a href = survey_results.php target = main_frame>Survey Results</a> ~ ";
		 echo "<a href = ITS_pdf.php target = main_frame>pdf</a><p>";
		 echo "<a href = admin/cmd.php target = main_frame>cmd</a>";
		 echo "<p><a href = learning_analysis3.php target = main_frame>Analysis3</a> ~ <a href = learning_analysis.php target = main_frame>Analysis</a>";
		 // echo "</div>";
		 echo "</fieldset>";		
    break;
  //-------------------------
  default: // student
  //-------------------------
	  // check if really admin
		if (strcmp($_SESSION['user']->status(),'admin')==0) {
		   $menu1_str = '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'
		 		   .'<select width="200px" name="ITS_current_status" onchange="ITS_change_status()"><option> student </option><option> admin </option></select>'
				   .$spacer.$common
				   .'</form>';
		}else {
		   $menu1_str =  $common;
		}
}
//onsubmit="javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit()"
//=====***************======

// GET STATUS
// If menu selected ELSE session
/*
if(isset($_POST['ITS_current_status'])){
    $status = $_POST['ITS_current_status'];
} else {
    $status = $_SESSION['user']->status();
}
// DISPLAY MENU FOR STATUS
echo $status;
echo '<div class="ITS_header">';

$spacer = '&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;';
$common = '<a href="Home.php">Home</a>'.$spacer.'<a href = "logout.php">Logout</a>';

switch ($status) {
  //-------------------------
  case 'admin':
  //-------------------------
		 echo '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'
		     .'<a href="Concept.php">Concepts</a>'.$spacer
				 .'<a href="Question.php"> Questions </a>&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;'
		 		 .'<select width="200px" name="ITS_current_status" onchange="ITS_change_status()"><option> admin </option><option> student </option></select>'
				 .$spacer.$common
				 .'</form>';
   break;
  //-------------------------
  case 'admin_all':
  //-------------------------
		 echo "<form action=admin/webct.php name=menu_form method=post target=main_frame>"
		 		 ."<input type=hidden name=db>"
  			 ."</form>";
	
	   echo "<p><fieldset><legend><span class=ITS_QCONTROL>Administrator</span></legend>";
		 // echo "<div style=`background:#87CEEB`>";  //D8D8D8
		 echo "<a href = admin/admins.php   target = main_frame>Admins</a> |";
		 echo "<a href = admin/students.php target = main_frame>Students</a> | ";
		 echo "<a href = admin/concepts.php target = main_frame>Concepts</a> | ";
		 echo "<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct >WebCT</a>: "
		 		 ."<b>[ </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_s >s</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_m >m</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_p >p</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_c >c</a>"
				 ."<b> | </b>"
				 ."<a href=javascript:; onclick=javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit() target=main_frame name=webct_mc >mc</a>"
				 ."<b> ]</b> | "; 
		 echo "<a href = admin/Question_index.php target = main_frame>Questions</a>&nbsp;&nbsp;"
				 ."<b>[ </b>" 
		     ."<a href = admin/Question_New.php target = main_frame> new</a>"
				 ."<b> ]</b> | ";
		 echo "<a href = LabView/ITS1.html target = main_frame>GUI</a> | ";
		 echo "<a href = dumb-test.html target = main_frame>test</a> | ";
		 echo "<a href = game/question.php target = main_frame>game</a> | ";	
		 echo "<a href = game/test.php target = main_frame>test</a> | ";
		 echo "<a href = game/test1.php target = main_frame>test1</a> | ";	
		 echo "<a href = game/test2.php target = main_frame>test2</a> | ";
		 echo "<a href = game/lab1.php target = main_frame>Lab 1</a> | ";
		 echo "<a href = game/lab2.php target = main_frame>Lab 2</a> | ";
		 echo "<a href = game/lab3.php target = main_frame>Lab 3</a> |";
		 echo "<a href = game/lab4.php target = main_frame>Lab 4</a> | ";
		 echo "<a href = game/lab5.php target = main_frame>Lab 5</a> | ";
		 echo "<a href = game/lab6.php target = main_frame>Lab 6</a> | ";
		 echo "<a href = game/lab7.php target = main_frame>Lab 7</a> | ";
		 echo "<a href = game/lab8.php target = main_frame>Lab 8</a> | ";
		 echo "<a href = game/lab10.php target = main_frame>Lab 10</a> | ";
		 echo "<a href = game/lab11.php target = main_frame>Lab 11</a> | ";
		 echo "<a href = game/lab12.php target = main_frame>Lab12A</a> | ";
		 echo "<a href = game/lab13.php target = main_frame>Lab12B</a> | ";
		 echo "<a href = ITS_lab07.php target = main_frame>Lab7</a> | ";
		 echo "<a href = ITS_lab10.php target = main_frame>Lab10</a> | ";
		 echo "<a href = ITS_lab.php target = main_frame>Warm-up</a> | ";
		 echo "<a href = LabView/remote/ITS_remote.php target = main_frame>LV</a> | ";
		 echo "<p><a href = survey_results.php target = main_frame>Survey Results</a> ~ ";
		 echo "<a href = ITS_pdf.php target = main_frame>pdf</a><p>";
		 echo "<a href = admin/cmd.php target = main_frame>cmd</a>";
		 echo "<p><a href = learning_analysis3.php target = main_frame>Analysis3</a> ~ <a href = learning_analysis.php target = main_frame>Analysis</a>";
		 // echo "</div>";
		 echo "</fieldset>";		
    break;
  //-------------------------
  default: // student
  //-------------------------
	  // check if really admin
		if (strcmp($_SESSION['user']->status(),'admin')==0) {
		   echo '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'
		 		   .'<select width="200px" name="ITS_current_status" onchange="ITS_change_status()"><option> student </option><option> admin </option></select>'
				   .$spacer.$common
				   .'</form>';
		}else {
		   echo $common;
		}
}
echo '</div>';
//onsubmit="javascript:document.menu_form.children[0].value=this.name;document.menu_form.submit()"
*/
/*
$spacer = '&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;';
$common = '<a href = "Home.php">Home</a>'.$spacer
         .'<a href = "logout.php">Logout</a>';
$menu2_str = '<form action="'.$_SERVER['PHP_SELF'].'" name="menu_form" method="post">'.$common.'</form>';
$menu2 = '<DIV class="ITS_menu1">'
				.$menu2_str
				.'</DIV>';
echo $menu2;
*/
/*
$status = strcmp($_SESSION['user']->status(),"admin");
	
echo '<div class="ITS_header">';
if ($status == 0){
	 echo '<a href="Question.php">Questions</a>&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;<select width=200px><option> '.$_SESSION['user']->status().' </option><option> student </option></select>&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp;';
}	
echo '<a href = "Home.php">Home</a>&nbsp;<b><font color="royalblue">&diams;</font></b>&nbsp';
echo '<a href = "logout.php">Logout</a></div>';
*/
?>


