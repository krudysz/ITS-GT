<?php
$LAST_UPDATE = 'Mar-8-2012';
//=====================================================================//
/* 					
     Author(s): Khyati Shrivastava
     Revision: Gregory Krudysz, Mar-8-2012
*/
//=====================================================================//

require_once ("config.php");
require_once ("classes/ITS_navigation.php");
include "classes/ITS_image.php";
global $db_dsn, $db_name, $db_table_users, $db_table_user_state;

if(isset($_REQUEST['id'])){
	$id  = $_REQUEST['id'];
	$fld = $_REQUEST['f'];
	$img = new ITS_image($id,$fld);
	$images_for_ques = 0;  // for div - ques_pic_table
	$images_in_db = 1;     // for div - main_table
	$page_num = 0;
}
else {
	$id = 1;
	$fld = $_REQUEST['f'];
	$img = new ITS_image($id,$fld);
	$images_for_ques = 0;  // for div - ques_pic_table
	$images_in_db = 1;     // for div - main_table
	$page_num = 0;
}	

session_start();
$_SESSION['image'] = $img;
//---------------------------------------------//
// NAVIGATION 		 
	$current = basename(__FILE__,'.php');
	$ITS_nav = new ITS_navigation($status);
	$nav     = $ITS_nav->render($current);    
//---------------------------------------------//
// QUESTION IMAGES

	$qidstr = '<div id="ITS_Q" qid="'.$id.'"><a href="Question.php?qNum='.$id.'" class="ITS_ADMIN">'.$id.'</a></div>';
	$Qimgs  = $img->image_viewer($page_num,$images_for_ques);
	//var_dump(trim($Qimgs));
	//echo '<br>'.count(trim($Qimgs));die();
			
	if (($Qimgs[0] == "") || (empty($Qimgs))) { $Qimgs_str = 'None'; }
	else 			   					      { $Qimgs_str = $Qimgs; }

// SERVER IMAGES		
	$Simgs_str = $img->image_viewer(0,$images_in_db);	
//---------------------------------------------//			

//echo $Simgs_str;die();
?>
<html>
<head>
<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/ITS_image.css" type="text/css">
<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
<script type="text/javascript" src="js/jquery-1.5.js"></script></head>
<body>
<center>
<!-- div #ITS_navigation_container -->
<?php echo $nav;?>
<!-- end: #ITS_navigation_container -->
<!-- div #Image_container -->
		<!-- <?php echo $qidstr;?> -->
		<div id="Image_container">
		<table class="ITS_Image">
		<tr><td id="ITS_image_container"><div class="logo">IMAGES</div></td></tr>
		<tr><td id="list"><center><?php echo $Simgs_str;?></center></td></tr>		
		</table>
		</div>
<!-- end: #Image_container -->		

<!-- div #Image_navigation_container -->
		<div id="Image_navigation_container">
			<input type="button" name="img_nav" value="<" id="prev">
			<span id="pgno">1</span>
			<input type="button" name="img_nav" value=">" id="next">
			<input type="hidden" name="id" value="<?php echo $id;?>"><br>
			<input type="hidden" name="image" id="image">
			<input type="hidden" name="fld" value="<?php echo $fld;?>">	
			<p>
			<input id="img_submit" type="submit" name="control" value="Select" qid="<?php echo $id;?>" fld="<?php echo $fld;?>">
			<input id="img_cancel" type="button" name="control" value="Cancel" qid="<?php echo $id;?>">
			</p>
		</div>
<!-- end #Image_navigation_container -->			
	</center>
</body>
	<script>
	/*-------------------------------------------------------------------------*/
	var page = 0;
	$("#picture	").delegate("td", "click", function() {  
			$("#image").val($(this).attr("id")); 
			$("td").removeClass("active"); 
			$("td").addClass("bo");
			$(this).removeClass("bo");
			$(this).addClass("active");
		//	alert('clicked');
	});
	/*-------------------------------------------------------------------------*/	
	$(".img_sm").live("hover", function(){ 
		var iid = $(this).attr('iid');
		var src = $(this).attr("src");
		$("#ITS_image_container").html('<img src="'+src+'" iid="'+iid+'">'); 
        $('.img_sm').each(function(index) {
			if (index==val){$(this).attr('id','current');} 
			else 		   {$(this).attr('id','');       }
        });
	}); 
	/*-------------------------------------------------------------------------*/
	$("input[name='img_nav']").live('click', function(event) {
		var nav = $(this).val();
		var pg  = $("#pgno").html()-1;
		
		if ( nav=='>' ) { pg=pg+1; } 
		else 			{ if (pg){ pg=pg-1; }}

		$('#pgno').html(pg+1);		
        $.get('ajax/ITS_image.php', {
            ajax_args: "navigation", 
            ajax_data: (pg)
        }, function(data) {
            $('#list').html(data);			
        });				
	});	
	/*-------------------------------------------------------------------------*/
	$("input[name='control']").live("click", function(event){ 
		var qid = $('#ITS_Q').attr('qid');
		var iid = $("#ITS_image_container img").attr('iid');
		//alert(qid+' - '+iid);

		switch($(this).val()){
		case "Select":
		  var fld = $(this).attr('fld');
		  $.get('ajax/ITS_image.php', {
            ajax_args: "insert", 
            ajax_data: qid+'~'+iid+'~'+fld
          }, function(data) {
            //$('#list').html(data);		
            window.location.replace('Question.php?qNum='+qid);	
          });
		  break;
		case "Cancel":
		  window.location.replace('Question.php?qNum='+qid);
		  break;
		default:
		  alert('action error');
		}
	}); 
	/*-------------------------------------------------------------------------*/		
	</script>
</html>
