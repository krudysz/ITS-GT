<?php	
//=====================================================================//
/*					
	 Author(s): Gregory Krudysz
	 Last Revision: Mar-17-2010	 
*/
//=====================================================================//
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate");            // Must do cache-control headers 
header("Pragma: no-cache");

include("config.php");
require_once(INCLUDE_DIR . "common.php");
require_once(INCLUDE_DIR . "User.php");

session_start();
// return to login page if not logged in
abort_if_unauthenticated();
//--------------------------------------// 
$status = $_SESSION['user']->status();
// connect to database
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)){throw new Question_Control_Exception($mdb2->getMessage());}
// DEBUG:	echo '<p>GET: '.$_GET['qNum'].'  POST: '.$_POST['qNum'];

	  //--- determine question number ---//
	  if     ( isset($_GET['qNum'])  )           {  $qNum = $_GET['qNum'];  $from = 'if';   }
	  elseif ( isset($_POST['qNum']) )           {  $qNum = $_POST['qNum']; $from = 'if';   }
	  elseif ( isset($_SESSION['qNum_current']) ){  $qNum = $_SESSION['qNum_current'];      }
	  else                                       {  $qNum = 2;              $from = 'else'; }
	  //------- CHAPTER -------------//
    $ch_max = 13;
		
    if (isset($_GET['ch']))   { $ch = $_GET['ch']; }
    else                      { $ch = 0;           } 
    $chapter = 'Chapter #<select class="ITS_select" name="ch" id="select_chapter" onchange="javascript:this.submit()">';
    for ($c=0; $c<=($ch_max+1); $c++) { 		  
          if ($ch == $c) { $sel = 'selected="selected"'; }
          else           { $sel = '';                    }
          if ($c==0) 			         { $hc = 'ANY'; $class_option='highlight'; }
          elseif ($c==($ch_max+1)) { $hc = 'ALL'; $class_option='highlight'; }			
					else                     { $hc = $c;    $class_option = '';        }		
          $chapter .= '<option class="'.$class_option.'" value="'.$c.'" '.$sel.'>'.$hc.'</option>';
    }
    $chapter .= '</select>';  
    //------- TYPE ---------------//
		$qtype = array('ALL','Multiple Choice','Matching','Calculated','Short Answer','Paragraph');
		$qtype_db  = array('','MC','M','C','S','P'); 
    if (isset($_GET['type']))   { $qt = $_GET['type']; }
    else                        { $qt = 0;             } 
    $type = 'Type <select class="ITS_select" name="type" id="select_type" onchange="javascript:this.submit()">';
    for ($t=0; $t<count($qtype); $t++) {
          if ($qt == $t) { $tsel = 'selected="selected"'; }
          else           { $tsel = '';                    }
          $type .= '<option value="'.$t.'" '.$tsel.'>'.$qtype[$t].'</option>';
    }
    $type .= '</select>';
		
	 // update SESSION
	 $_SESSION['qNum_current'] = $qNum;
	 $form = $chapter.'&nbsp;&nbsp;'.$type;
//--------------------------------------//
// QUERY
$ALL = $ch_max+1;
switch ($ch) {
    case 0: 
				$query_chapter = 'AND category NOT IN (';
		    for ($n=1;$n<=$ch_max;$n++) {
						if ($n < 10) { $nn = '0'.$n; }
						else         { $nn = $n;     }
						$query_chapter .= '"PreLab'.$nn.'","Lab'.$nn.'","Chapter'.$nn.'"';
						if ($n<$ch_max) { $sep = ','; }
						else 						{ $sep = ')'; }
						$query_chapter .= $sep;
				}		    
		break;
		case $ALL: $query_chapter = ''; break;
		default:
		    if ($ch < 10) { $chs = '0'.$ch; }
				else          { $chs = $ch;  }
				$query_chapter = 'AND category IN ("PreLab'.$chs.'","Lab'.$ch.'","Chapter'.$ch.'")';				
}
switch ($qt) {
    case 0:  $query_type = 'qtype IN ("MC","M","C","S","P") '; break;
		default: $query_type = 'qtype = "'.$qtype_db[$qt].'" ';				
}
$qindex = 0;
// look for LIST of question
//$query = 'SELECT id,title,image,category,tag_id FROM webct WHERE '.$query_type.$query_chapter;
$query = 'SELECT id,title,image,category FROM webct WHERE '.$query_type.$query_chapter;
//echo $query;

$res =& $mdb2->query($query);
if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
$qs  = $res->fetchAll();

if (empty($qs[$qindex][1])) { $title = '';              }
else 												{ $title = $qs[$qindex][1]; }
if (empty($qs[$qindex][2])) { $image = '';              }
else 												{ $image = $qs[$qindex][2]; }
if (empty($qs[$qindex][3])) { $category = '';              }
else 												{ $category = $qs[$qindex][3]; }
if (empty($qs[$qindex][4])) { $tags = ''; }
else {
  $query = 'SELECT name FROM tags WHERE id IN ('.$qs[$qindex][4].')';  //echo $query;
  $res =& $mdb2->query($query);
  if (PEAR::isError($res)) {throw new Question_Control_Exception($res->getMessage());}
  $tagNames  = $res->fetchCol();
	$tags = implode(',',$tagNames);
}

$Nqs = count($qs); 
  
	if ($Nqs){ 
	  $qid = $qs[$qindex][0];
		  $tagList = '';
			if (!empty($tags)) {
  			for ($i=0; $i < count($tagNames); $i++) {
  			  //$tagList .= '<input type="button" class="logout" value="'.$tagNames[$i].'">';
					$tagList .= '<span class="ITS_tag">'.$tagNames[$i].'</span>';
  			}		
  		}	

    //$tb = new ITS_table('qs',round(sqrt($Nqs)),round(sqrt($Nqs)),$qs,array(),'ITS_ANSWER');
    //echo $tb->str;
    //echo implode(', ',$qs).'<p>';
    //--------------------------------------//
    	 $Q = new ITS_question(1,$db_name,$tb_name);
			 $qid = $qNum;
    	 $Q->load_DATA_from_DB($qid); 
    	 //echo $Q->render_QUESTION_check()."<p>";
    	 $Q->get_ANSWERS_data_from_DB();
    	 //$Q->get_ANSWERS_solution_from_DB();
    	 //echo $Q->render_ANSWERS('a',0);
			 $sts = $Q->render_data();
    	 //$mdb2->disconnect();
    //--------------------------------------//
	}
	else { $qid = ''; $sts = '<p><b>- nothing found -</b>'; }

//'<div class="ITS_navigate">'
$nav = '<input id="previousQuestion" class="ITS_navigate_button" type="button" onclick="ITS_QCONTROL(\'PREV\',\'ITS_question_container\')"  name="prev_question" value="<<" qid="'.$qid.'">'.
			 '<input type="text" class="ITS_navigate" onkeypress=ITS_QCONTROL(\'TEXT\',\'ITS_question_container\') name="qNum" value="'.$qid.'" id="ITS_QCONTROL_TEXT" Q_num="'.$qid.'" Q_type="'.$qtype.'">'.
			 '<input id="nextQuestion" class="ITS_navigate_button" type="button" onclick="ITS_QCONTROL(\'NEXT\',\'ITS_question_container\')" name="next_question" value="&gt;&gt;">';		 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd" >
<html lang="en">
<head> 
<script src="js/ITS_AJAX.js"></script>
<script src="js/ITS_QControl.js"></script>
<title>Questions Database</title>
  <link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_navigation.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_QTI.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">	
	
	<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="MathJax/MathJax.js"></script>

	<script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
	<!-- QTI IMPORTER start ------------------------------------>
	<link href="css/ITS_QTI.css" rel="stylesheet" type="text/css" />
	<link href="css/swfupload.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="swfupload/swfupload.js"></script>
	<script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.4.min.js"></script>
	<link href="uploadify/uploadify.css" type="text/css" rel="stylesheet" />
  <script type="text/javascript" src="uploadify/swfobject.js"></script>
	<!-- QTI IMPORTER end1 ------------------------------------>
<style>
    #dialog-form { background: #fff; }
		.ui-dialog-form { background: #e1e; }
		.ui-widget-header { background: red; border: 2px solid #666; }
    .ui-dialog-title { background: #aaa;}
		.ui-dialog-titlebar { background: #aaa; border: 2px solid #666; color: #fff; font-size:12pt}
		.ui-dialog-content  { text-align: left; color: #000; padding: 0.5em; }
		.ui-button-text { color: #00a; }
		#myDialog { background: #fff; border-bottom: 2px solid #666; zindex: 5;}
</style>	
  <style>
  p { background:yellow; font-weight:bold; cursor:pointer; 
      padding:5px; }
  p.over { background: #ccc; }
  span { color:red; }
  </style>
  <script src="http://code.jquery.com/jquery-1.5.js"></script>
<script>
$(document).ready(function() { 
	/*-------------------------------------------------------------------------*/
	  /*================= UPLOADIFY ===========================================*/
		/* NOT WORKING IN FF ?? *//*
     $("#file_upload").uploadify({
        'uploader'  : 'uploadify/uploadify.swf',
        'script'    : 'uploadify/uploadify.php',
        'cancelImg' : 'uploadify/cancel.png',
        'folder'    : 'ITS_FILES/QTI/images',
    	  'multi'     : true,
        'auto'      : true,
    	  'fileExt'   : '*.jpg;*.gif;*.png',
        'fileDesc'  : 'Image Files (.JPG, .GIF, .PNG)'
     });*/
		/*================= UPLOADIFY ===========================================*/
	   $(".ITS_select").change(function() { document.profile.submit(); });
		 //$("#select_class").buttonset();
	  /*-------------------------------------------------------------------------*/
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/	
	   $('#previousQuestion').live('click', function(event) {
	      var qid = $('#ITS_QCONTROL_TEXT').attr("value");
	      //var del = $(this).attr("del"); //alert(delta);
        $.get('ITS_admin_AJAX.php', { ajax_args: "getQuestionMeta", ajax_data: qid}, function(data) {
           $('#metaContainer').html(data);
        })
	   })		
		/*-------------------------------------------------------------------------*/	
	   $('#nextQuestion').live('click', function(event) {
	      var qid = $('#ITS_QCONTROL_TEXT').attr("value");
	      //var del = $(this).attr("del"); //alert(delta);
        $.get('ITS_admin_AJAX.php', { ajax_args: "getQuestionMeta", ajax_data: qid}, function(data) {
           $('#metaContainer').html(data);  
        })
	   })		
		/*-------------------------------------------------------------------------*/
	   $('#testme').live('click', function(event) {
		    //if ($.browser.ff && event.which == 1) { alert('ff'); }
				//if (event.which != 0) return true;
		    //if (event.which != 1) return true;														
	      //var del = $(this).attr("del"); //
				//console.log('find was called');

				//alert('a');
	   })	 	 		
		/*-------------------------------------------------------------------------*/		
		 $("#deleteButton").live('click', function(event) {
		    var uid = $(this).attr("uid");
		    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		    $( "#addQuestionDialog:ui-dialog" ).dialog( "destroy" );
	      $( "#addQuestionDialog" ).dialog({
    			resizable: false,
    			height: 300,
    			modal: true,
    			buttons: { "Add Question": function() { $( this ).dialog( "close" );
    									$.get('ITS_admin_AJAX.php', { ajax_args: "AddQuestionDialog", ajax_data: uid}, function(data) {
                			  //alert(data); //$('#contentContainer').html(data); 
            					});
					         },
        				           Cancel: function() { $( this ).dialog( "close" ); }
        			     }
        }); 
		 });
	 /*-------------------------------------------------------------------------*/
	  $('#title').live('mouseover', function(event) {
	      //$(this).css('cursor', 'hand');
				//$(this).css('background', 'yellow');
	      //var del = $(this).attr("del"); //alert(delta);
        //$.get('ITS_admin_AJAX.php', { ajax_args: "getQuestionMeta", ajax_data: qid}, function(data) {
        //   $('#metaContainer').html(data);  
        //})
	  })			
		/*-------------------------------------------------------------------------*/	
		$( "#createQuestion" ).live('click', function(event) {
			 $.get('ITS_admin_AJAX.php', { ajax_args: "createQuestion", ajax_data: 'new'}, function(data) {
					var dialog = $(data).appendTo('body');
					dialog.dialog({show:'blind',hide:'slide',resizable:false,width:'600px',height:'auto',modal:false});
       })
		}) 		
		/*-------------------------------------------------------------------------*/		
		$( "#cloneQuestion" ).live('click', function(event) {
		   //alert('ad');
		   var qid = $(this).attr("qid");
			 //$("#ITS_question_container").append(load("showtext.php"));
			 $.get('ITS_admin_AJAX.php', { ajax_args: "createQuestion", ajax_data: 'clone~'+qid}, function(data) {   
					$('#ITS_question_container').append(data);
					$('#dialog-form').css("display","block");
			    //var dialog = data;
          //alert(dialog);
					//var dialog = $(data).appendTo('body');
					//var dialog = $('<div style="display:hidden"></div>').appendTo('body');
					//dialog.dialog();
					//$('#myDialog').css("position","absolute");
					//$('#ITS_question_container').append(data);  
					//$( "#dialog-form" ).dialog("open");
       })
		   //$( "#dialog-form" ).dialog("open");
		}) 	
		/*-------------------------------------------------------------------------*/		
		$( "#deleteQuestion" ).live('click', function(event) {
		   var qid = $(this).attr("qid");
			 var qtype = 'mc'; //$(this).attr("qtype");
       var dialog = $('<h2 style="color:#009">Delete Question <b><font color="red">'+qid+'</font></b> ?</h2>').appendTo('body');
			 dialog.dialog({
    			resizable: false,
    			height: 160,
    			modal: true,
    			buttons: { "Delete Question": function() { $( this ).dialog( "close" );
    									$.get('ITS_admin_AJAX.php', { ajax_args: "deleteQuestion", ajax_data: qid+'~'+qtype}, function(data) {
            					$('#ITS_question_container').html(data);
											});
					         },
        				     Cancel: function() { $( this ).dialog( "close" ); }
        			     }
        });
		})		
		/*-------------------------------------------------------------------------*/		
		 $("#submitDialog").live('click', function(event) {	
		   var str = $("#Qform").serialize();  //alert(str);
			 $.get('ITS_admin_AJAX.php', { ajax_args: "addQuestion", ajax_data: str}, function(data) {   
					$('#ITS_question_container').append(data);
			 })
			 /*
			 $( "#users tbody" ).append( "<tr>" +
			        "<td></td>" +
							"<td>MC</td>" +
							"<td>" + $("#title").val() + "</td>" + 
							"<td>" + $("#image").val() + "</td>" + 
							"<td>" + $("#question").val() + "</td>" +
						"</tr>" );
			 */
			 $("#dialog-form").remove();			
       /*
		   Qtitle    = $("#Qtitle").val();
			 Qimage    = $("#Qimage").val();
			 Qquestion = $("#Qquestion").val();
			 Qanswers  = $("#Qanswers").val();
			 var Qanswer = $( [] );
			 
			 for(var a = 1; a <= Qanswers; a++) {
			   Qanswer.add( "#Qanswer"+a ).val();
			 }
			 */
			 //alert(str);
			 //$(this).dialog("close");
			 //$("#dialog-form").remove();   //detach();
		 });
		/*-------------------------------------------------------------------------*/		
		 //$("#answers").click(function() { doChange(); }).attr("onchange", function() { doChange(); });
		 //$('#answers').change(function() { alert('me');}).attr("onchange",function() {$(this).change()});
		 /*
		 $("#answers").live('click', function(event) {
		 $.get('ITS_admin_AJAX.php', { ajax_args: "editAnswers", ajax_data: ''}, function(data) {   
					$('#ITS_Qans').html(data);
			 })
		 }).attr("onchange", function() { alert('changed'); });
		 */
		 $("#ansUpdate").live('click', function(event) {	
		   var action = $(this).attr("action");
		   var qid = $("#answers").attr("qid");// alert(qid);
			 var N = $("#answers").val();  
			 
			 $.get('ITS_admin_AJAX.php', { ajax_args: "editAnswers", ajax_data: qid+'~'+action+'~'+N}, function(data) {   
					$('#ITS_Qans').html(data);
			 })		
		 });		 
		/*-------------------------------------------------------------------------*/		
		 $("#cancelDialog").live('click', function(event) {	
		   $("#dialog-form").remove();   //detach();
		 });	
		/*-------------------------------------------------------------------------*/		
		 $("#tags").live('click', function(event) {
		    var uid = $(this).attr("uid");
		    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		    $( "#tagDialog:ui-dialog" ).dialog( "destroy" );
	      $( "#tagDialog" ).dialog({
    			resizable: false,
    			height: 200,
    			modal: true,
    			buttons: { "Delete Now": function() { $( this ).dialog( "close" );
    									$.get('ITS_admin_AJAX.php', { ajax_args: "deleteDialog", ajax_data: uid}, function(data) {
                			  //alert(data); //$('#contentContainer').html(data); 
            					});
					         },
        				           Cancel: function() { $( this ).dialog( "close" ); }
        			     }
        }); 
		 });
		/*-------------------------------------------------------------------------*/		 
		 $("#QTIsubmit").live('click', function(event) {	
		   //var str = $("#QTI2form").serialize();
			 //$('#ITS_question_container').load('upload_QTIfile.php', function() {
			 //$('#ITS_question_container').load("upload_QTIfile.php", {limit: 25}, function(){
         //alert('Load was performed.');
       //});
			 /*
			 $.post("upload_QTIfile.php.php", { name: "John", time: "2pm" },
        function(data){
          alert("Data Loaded: " + data);
       });*/
			 /*
			 var action = $(this).attr("action");
		   var qid = $("#answers").attr("qid");// alert(qid);
			 var N = $("#answers").val();  
			 ajax_args: "editAnswers", ajax_data: qid+'~'+action+'~'+N
			 */
			 //$.get('upload_QTIfile.php', { ajax_args: "addQuestion", ajax_data: str }, function(data) {  
			 //alert(data); 
			 //	$('#ITS_question_container').html(data);
			 //})		
		 });		 
		/*-------------------------------------------------------------------------*/						 
		 $("#importQuestion").live('click', function(event) {
		    //$("#importQTI").css("display","inline");
				$("#importQuestionContainer").css("display","inline");
		    //var uid = $(this).attr("uid");
				//action="upload_QTIfile.php" method="post"
				/*
				var content = '<form id="QTIform" action="upload_QTIfile.php" method="post" enctype="multipart/form-data">' 
	                +'<input type="file" name="file" id="file" />'
	                +'<input type="submit" name="submit" value="Submit" id="QTIsubmit" />'
	                +'</form>'
	                +'<form><input id="file_upload" name="file_upload" type="file" /></form>';
				$("#importQuestionContainer").html(content);
				*/
		 });		 
	 /*-------------------------------------------------------------------------*/
		$("a[name='ITS_EDIT_QCONTROL']").live('click', function(event) {
		  //alert('jquery ITS_EDIT_QCONTROL');
			var textarea_id = $('textarea.ITS_EDIT').attr("id");
			var textarea_value = $('textarea#'+textarea_id).val();
			//alert(textarea_value);
			var ret = renderQuestionAnswer(textarea_value);
			//alert(ret);
			$('#'+textarea_id).attr('value',ret);
		});	 
	/*-------------------------------------------------------------------------*/
    $("p").live("click", function(){
      $(this).after("<p>Another paragraph!</p>");
    });
	/*-------------------------------------------------------------------------*/
})			
</script>	
</head>
<body>
  <p>Click me!</p>

  <span></span>
</body>
</html>
