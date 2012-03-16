<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   					 // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 					 // Must do cache-control headers 
header("Pragma: no-cache");

include ("classes/ITS_timer.php");

require_once ("config.php");
require_once ("classes/ITS_question_table.php");
require_once ("classes/ITS_survey.php");
require_once ("classes/ITS_menu.php");
require_once ("classes/ITS_message.php");
require_once ("classes/ITS_statistics.php");
/*-- SCORING module -----------------------------------*/
require_once ("classes/ITS_computeScores.php");
require_once ("classes/ITS_book.php");
/*-- TAGGING module -----------------------------------*/
require_once ("tagging/ITS_tagInterface.php");
/*-- RATING module ------------------------------------*/
//require_once ("rating/ITS_rating.php");
/*-----------------------------------------------------*/
require_once ("classes/ITS_screen2.php");
require_once (INCLUDE_DIR . "common.php");
require_once (INCLUDE_DIR . "User.php");

//$timer = new ITS_timer();
session_start();

// return to login page if not logged in
abort_if_unauthenticated();

//echo "<center><h3>Welcome {$_SESSION['user']->first_name()}</h3></center>";
//$id = 670; //622;508 //
$id = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
//echo $status;

if (isset ($_POST['role'])) {$role = $_POST['role'];} 
else {
	switch ($status) {
	 case 'admin': $role = 'admin';   break;
	 default:      $role = 'student'; break;
	}
}

$screen = new ITS_screen2($id, $role, $status);
//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);
$_SESSION['screen'] = $screen;
//------------------------------------------//

//<!--<script type="text/javascript" src="js/ITS_jquery.js"></script>-->
//<img src="images/matching_example1.png" style="float:left;height:50px;cursor:pointer;border:1px solid #666;" onclick="ITS_showImage(this)">
//<link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	

	//<link rel="stylesheet" href="css/ITS_DEBUG.css" type="text/css" media="screen">
	//<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
  <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ITS</title>
	<!---->
	<link rel="stylesheet" href="css/ITS_logs.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/print/ITS_print.css" media="print">
	<link rel="stylesheet" href="tagging/ITS_tagging.css" type="text/css" media="screen">
	<link rel="stylesheet" href="rating/ITS_rating.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_jquery.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_computeScores.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_BOOK.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/ITS_DEBUG.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/ITS_question.css" type="text/css" media="screen">
		
  <link type="text/css" href="jquery-ui-1.8.4.custom/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="jquery-ui-1.8.4.custom/js/jquery-ui-1.8.4.custom.min.js"></script>
	<style type="text/css">
  	#feedback { font-size: 1.4em; }
  	#selectable .ui-selecting { background: #FECA40; }
  	#selectable .ui-selected  { background: #F39814; color: white; }
  	#selectable { list-style-type: none; margin: 0; padding: 0;    }
  	#selectable li { margin: 3px; padding: 1px; float: left; width: 200px; height: 80px; font-size: 4em; text-align: center; }
    #tabs { background: #fff; border: none; } 
		#tabs ul {background: #fff; border: 0px solid green; }
		#tabs ul li { border: 1px solid #999; }

  	#tabs { list-style-type: none; margin: 0; padding: 0;   }
	  #tabs-1 { background: #fff; border: 1px solid #999; }
		#tabs-2 { background: #fff; border: 1px solid #999; }
		#contentContainer{ border: none; }
 </style>
	<!--[if IE 6]>
	<link rel="stylesheet" href="css/IE6/ITS.css" type="text/css" media="screen">
	<![endif]-->
  <script src="js/ITS_admin.js"></script>
	<script src="js/AJAX.js"></script>
  <script src="js/ITS_AJAX.js"></script>
  <script src="js/ITS_screen2.js"></script>
	<script src="js/ITS_QControl.js"></script>
	<script src="js/ITS_book.js"></script>
	<script src="tagging/ITS_tagging.js"></script>
	<script src="rating/forms/star_rating.js"></script>
<script type="text/javascript">
function UR_Start() {
	UR_Nu = new Date;
	UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
	document.getElementById("ur").innerHTML = UR_Indhold;
	setTimeout("UR_Start()",1000);
}
function showFilled(Value) { return (Value > 9) ? "" + Value : "0" + Value; }
</script>
<script>
	 var last, diff;
$('#contentContainer.innerHTML').change(function() { 
alert('a');
/*
    var date1 = new Date();
    var ms1 = date1.getTime();
		//alert(ms1);
    sessionStorage.setItem('TIME0',ms1); 
		*/
});
</script>	 
</head>

<body onload="UR_Start()">
<input type="hidden" id="LabData"/>
<div id="pageContainer">
<!-- MENU -------------------------------------------------->
	<div id="menuContainer"> 
	  <div class="logout"><a href="logout.php">Logout</a></div>
	<!--
	  <div class="icon" id="Minst_icon">I</div>
       <p class="ITS_instruction"><img src="images/matching_example1.png" style="position:relative;max-width:100%"></p>
		<div class="icon" id="Tag_icon">Tag</div>
		-->
		<div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
		<div class="icon" id="messageIcon" onClick="ITS_MSG(1)">&para;</div>
	</div>
<!-- myScore ---------->
    <div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div>
    <div id="scoreContainerContent">
<?php
/**/
if ($role == 'admin') {$ch = 13; } 
else                  {$ch = 1;  }
/**/
$chArr = range(1,$ch);

$score = new ITS_computeScores($id,$role,$chArr); //,$ch);
$_SESSION['score'] = $score;

$str = $score->renderChapterScores($ch);
//$str = 'score';
echo $str;

?>
</div>
<!-- NAVIGATION ----------------------------------------------->
<!-- onclick="javascript:ITS_book_select(this)" --------------->
<p>
<div id="bookNavContainer">
<div id="chContainer">
<span id="chText">CHAPTER</span>
<ul id="chList">
<li><a href="#" class="chapter_index" id="chapter_index2" name="chapter" value="1">1</a></li>
<?php
//id="current" id="active" (in li)
//onclick="javascript:ITS_chapter_select(this)"

if ($role == 'admin') {
  for ($i = 2; $i <= $ch; $i++) {
			echo '<li><a href="#" class="chapter_index" id="chapter_index'.$i.'" name="chapter" value="'.$i.'">'.$i.'</a></li>';
	}
}
?>
</ul>
</div>
<?php
//-- TEST -------------------------------------------------->
//$s = new ITS_computeScores($id);
//$str = $s->renderLabScores();
//echo $str;
?>
	</div>
	<div id="page">
<!-- CONTENT ----------------------------------------------->
<?php

//echo $screen->main();
//--- resources ---//
//$arr = array(2,"",NULL);
//echo array_sum($arr);die('done');
$ch = 1;
$screen->chapter_number = $ch;
$meta = 'image';

/*
$x = new ITS_book('dspfirst',$ch,$meta,$mimetex_path);
$o = $x->main();
echo $o.'<p>';
*/
//----------------//
?>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Questions: Chapter 3</a></li>
		<li style="float:right;"><a href="#tabs-2">Review</a></li>
	</ul>
	<div id="tabs-1">
		 <?php 
		 echo $screen->main();
		 //if ($role == 'admin') { echo $screen->main(); }
		 //else                  { echo '<h1>ITS has closed for the semester.<p>Your answers for Chapter 1-8 are available for review.</p></h1>'; }
		 ?>
	</div>
	<div id="tabs-2">
<?php echo $screen->reviewMode(1,0);?>
	</div>
</div>

<!-- HERE -->

</div>
<p>
<!-- FOOTER ----------------------------------------------->
<div id="footerContainer"><p>		
		<ul id="navlist">
		  <li><code>ITS v.135e</code></li>
<?php
if ($status == 'admin') {
	$opt_arr = array (
		'admin',
		'student'
	);
	$option = '';
	for ($o = 0; $o < count($opt_arr); $o++) {
		if ($role == $opt_arr[$o]) { $sel = 'selected="selected"'; } 
		else 				   	   { $sel = '';                    }
		$option .= '<option value="' . $opt_arr[$o] . '" ' . $sel . '>' . $opt_arr[$o] . '</option>';
	}
	$user = '<li><form id="role" name="role" action="index.php" method="post">' .
	'<select class="ITS_select" name="role" id="myselectid" onchange="javascript:this.form.submit();">' .
	$option .
	'</select>' .
	'</form></li>';
	//$user = $status;
	$spacer = '&nbsp;<b><font color="silver">&diams;</font></b>&nbsp;';

	if ($role == 'admin') {
		$admin_list = '<p>' .
		'<li><a href="ITS_versions.php" style="color:#666">Versions</a></li>' .
		'<li> ' . $spacer . ' <a href="dSPFirst.php" style="color:#666">eDSPFirst</a></li>' .
		'<li> ' . $spacer . ' <a href="survey1.php" style="color:#666">Spring 2010 Survey</a></li> ' .
		'<li> ' . $spacer . ' <a href="Question.php" style="color:#666">Questions</a></li> ' .
		'<li> ' . $spacer . ' <a href="Profile.php?ch=1" style="color:#666">Profiles</a></li> ' .
		'<li> ' . $spacer . ' <a href="DATA/DATA.php" style="color:#666">DATA</a></li> '.
		'<li> ' . $spacer . ' <a href="index.php" style="color:#666">SKIN 1</a></li> ';
/*		
$admin_list = '<div id="navcontainer"><ul id="navlist">'
 .'<li><a href="logout.php">Logout</a></li>'
 .'<li><a href="index.php" >ITS</a></li>'
 .'<li><a href="Question.php" >Questions</a></li>'
 .'<li><a href="Profile.php" >Profiles</a></li>'
 .'<li><a href="Logs.php" id="current">Logs</a></li>'
 .'</ul></div>';*/
	} else {
		$admin_list = '';
	}
	$ftr = '&bull; ' . $user . $admin_list;
} else {
	$ftr = '&bull; ' . preg_replace('/_/',' ',$status) . ' <p>';
}
echo $ftr;
?>
    </ul>
	</div>
	<span style="float:right;padding:1.5em 0"><font id="ur" size="2" face="Monospace, sans-serif" color="#666666"></font></span>
</div>
<script type="text/javascript">
   /*-------------------------------------------------------------------------*/
   $(document).ready(function() {
   //$("#chapter_index4").css({background: '#ffffff',border: '2px solid #666666'}); 
	 //$("textarea").resizable();
	 $( "#tabs" ).tabs({event: "mouseover"});
   $(".icon#Minst_icon").change(function(){$(this).css("background-color","red");});
   $(".icon#Minst_icon").click(function(){$(".ITS_instruction").slideToggle("normal");});
	 $(".icon#tagg_icon").click(function(){$(".ITS_instruction").slideToggle("normal");});
	 $(".icon#Tag_icon").mouseover(function(){$(".ITS_tags").slideToggle("normal");});
	 $("div.tagsMore#header").click(function(){$("div.tagsMore#list").slideToggle("fast");});
	 $("#messageContainer").click(function(){$("#messageContainerContent").slideToggle("slow");});
	 $("#adminContainer").click(function(){$("#adminContainerContent").slideToggle("slow");}); 
	 $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
	 //$("#accor").accordion({autoHeight: false});
	 $("#dialog").dialog({ autoOpen: true, resizable: false, width:425 });
	   var v  = $('#headerQuestion').attr('view');
     var ch = $('#accor div h3 a span[name=Question]').attr('ch');
   $('.chapter_index').each(function(index) {
		 if (v) { if ( (index) < 0 ){$(this).css({display: 'none'});} }
		 if ( (index+1) == ch )     {$(this).css({background: '#ffffff',border: '2px solid #666666'});}
		 else                       {$(this).css({background: 'gainsboro',border: '2px solid #aaaaaa'});}
   })
	 /*-------------------------------------------------------------------------*/
	 /*
	  $('span.ITS_plot').live('click', function() {
	   $(this).css({display: 'none'});
	 })*/
	 /*-------------------------------------------------------------------------*/
	 /*
	 $(function() {
      $("#slider").slider({
			value:1,
			min: 0,
			max: 3,
			step: 1,
			//slide: function(event, ui) {
			//	$("#amount").val('$' + ui.value);
			//}
		});
		//$("#amount").val('$' + $("#slider").slider("value"));
	 });
	 */
	 /*-------------------------------------------------------------------------*/
	 $('#headerQuestion').live('click', function() {
	   var v = $(this).attr('view');
	   var ch = $('#accor div h3 a span[name=Question]').attr('ch');
	   $('.chapter_index').each(function(index) {
				// update style: closed chapters invisible
				if (v) { if ( (index) < 0 ){$(this).css({display: 'none'});} }
				if ( (index+1) == ch ){$(this).css({background: '#ffffff',border: '2px solid #666666'});}
				else                  {$(this).css({background: 'gainsboro',border: '2px solid #aaaaaa'});}
	   })
	 })
	 /*-------------------------------------------------------------------------*/
	 $('#headerReview').live('click', function() {
	    var ch = $('#accor div h3 a span[name=Review]').attr('ch');
	    $('.chapter_index').each(function(index) { $(this).css({display: 'inline'}); })
		        $('.chapter_index').each(function(index) {
				  // update style: selected chapter index
					if ( (index+1) == ch ){$(this).css({background: '#ffffff',border: '2px solid #666666'});}
					else                  {$(this).css({background: 'gainsboro',border: '2px solid #aaaaaa'});}
				})
	 })
	 /*-------------------------------------------------------------------------*/
	 	 $('.chapter_index').live('click', function() {
	      var current = $(this).text().replace(/^\s+|\s+$/g,""); // need to trim white spaces
        $('.chapter_index').each(function(index) {
				  // update style
					if ( (index+1) == current ){$(this).css({background: '#ffffff',border: '2px solid #666666'});}
					else                       {$(this).css({background: 'gainsboro',border: '2px solid #aaaaaa'});}
				})
				// Which tab is opened?
				var review_mode = $("#accor").accordion( "option", "active" ); //alert(review_mode);
				if (review_mode) {
				  $.get('ITS_screen_AJAX2.php', { ajax_args: "reviewMode", ajax_data: current+','+0 }, function(data) {
							$('#reviewContainer').html(data);
  				    //alert($('.accordion .head').text());
  						$('#accor div h3 a span[name=Review]').text('Chapter '+current);     
						$('#accor div h3 a span[name=Review]').attr('ch',current);
          })
				}
				else {
  				$.get('ITS_screen_AJAX2.php', { ajax_args: "newChapter", ajax_data: current}, function(data) {
  						$('#accor div h3 a span[name="Question"]').text('Chapter '+current);
						  $('#accor div h3 a span[name="Question"]').attr('ch',current);
              $('#contentContainer').html(data);			
          })
				}
	 });
	 /*-------------------------------------------------------------------------*/
	 $('#ITS_next').live('click', function(event) {
      $.get('ITS_screen_AJAX2.php', { ajax_args: "getContent", ajax_data: ''}, function(data) {
        //alert($(this).data('chosen'));
        /*$('#contentContainer').html(data);  
				$("#dialog").dialog({ autoOpen: false, resizable: false, width:425 });
				$("#dialog").dialog('close');*/
      })
			/*$("#dialog").dialog({ autoOpen: false, resizable: false, width:425 });*/
	 })
	 	 /*-------------------------------------------------------------------------*/
	 $('.ITS_navigation[name=updateReview_index]').live('click', function(event) {
	    var ch  = $(this).attr("ch");
	    var del = $(this).attr("del"); //alert(ch+' - '+del);
      $.get('ITS_screen_AJAX2.php', { ajax_args: "reviewMode", ajax_data: ch+','+del}, function(data) {
				//$('#ITS_meta').html(data);  
				$('#reviewContainer').html(data);
      })
	 })
	 /*-------------------------------------------------------------------------*/
	 $('#matchingInstruction').live('click', function() {
		      //var show = $( "#dialog" ).dialog( "isOpen" );
          $("#dialog").dialog('open');
					$(".ui-dialog-titlebar").css({background: '#999', border: 'none' });
	 })
	 /*-------------------------------------------------------------------------*/
		$('.ansCheck').live('click', function() {
				//alert($(this).attr("id"));
				var chkid = '#'+$(this).attr("for");
				$('#errorContainer').css({display: 'none'});
				// selection restriction
        var check_info = chkid.split("_");    
        for(var c = 1; c <= check_info[4]; c++) {
          if ( c==check_info[2] ) { 
              //alert('#check_'+check_info[1]+'_'+c);
							//$('#check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).attr("checked",true); //$(obj).is(':checked')
              //alert($('#check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).val());
							//alert($('#check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).attr("checked"));
							$(this).addClass('ansCheck_sel');
							$(this).attr("checked",true);
							//alert($(this).attr("id"));
							//$('#check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).addClass('ansCheck_sel');
					}
					else {
					    $('#check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).attr("checked",false); //$(obj).is(':checked')
              $('#label_check_'+check_info[1]+'_'+c+'_'+check_info[3]+'_'+check_info[4]).removeClass('ansCheck_sel');
					}
        }
	 })
	 /*-------------------------------------------------------------------------*/
		$('span[name=ITS_MC]').live('click', function() {	
		  var current = $(this).attr("id");
		  $('#errorContainer').css({display: 'none'});
       $('span[name=ITS_MC]').each(function(index) {
			 //alert(current +'=='+ $(this).attr("id"));
			   if ( current == $(this).attr("id") ) {$(this).removeClass('CHOICE_ACTIVE').addClass('CHOICE_SELECTED');}
				 else                                 {$(this).removeClass('CHOICE_SELECTED').addClass('CHOICE_ACTIVE');}
			 });
	 })
	 /*-------------------------------------------------------------------------*/
	 $('#ITS_submit').live('click', function() {
	      var qid    = $(this).attr("qid");
        var qtype  = $(this).attr("qtype");
	      var values = new Array();
				//alert(qid+qtype+values);
				switch (qtype.toUpperCase()) {
         //----------//
         case 'M':
         //----------//
        	 $('.ansCheck_sel').each(function(index) {	  //:checked
					   //alert($(this).attr("id"));
    			   var check_info = $(this).attr("id").split("_");
    				 if (!values[check_info[4]-1]) { values[check_info[4]-1] = '';}
						 //alert('values['+check_info[2]+'] = '+check_info[3]);
    				 values[check_info[2]-1] = check_info[3];
		       })
         break;
         //----------//
         case 'MC':
         //----------//    
				 var alphaChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  				$('span[class="CHOICE_SELECTED"]').each(function(index) {	    //name=ITS_MC
				     //if ($(this).hasClass("CHOICE_ACTIVE")) { };
					   var check_info = $(this).attr("id").split("_");
             //values[check_info[1]-1] = check_info[1];
						 //if (!values[check_info[2]-1]) { values[check_info[2]-1] = '';}
						 //values[0] = check_info[1]; 
						 values[0] = alphaChars.charAt(check_info[1]-1);
  				})
				 break;
				 //----------//
         case 'C':
         //----------//
				   var val = $('#ITS_TA').val();
				   values[0] = val;
				 break;
				}
				var chosen = values.join();
				if (chosen) {
				var c      = $(this).attr("c");
				//alert(qid+'~'+qtype+'~'+chosen+'~'+c);
				$.get('ITS_screen_AJAX2.php', { ajax_args: "recordChapterAnswer", ajax_data: qid+'~'+qtype+'~'+chosen+'~'+c}, function(data) {
				    //alert($(this).data('chosen'));
						$('#contentContainer').html(data); 
						//$("#dialog").dialog({ autoOpen: false, resizable: false, width:425 });
        })
				//--- update Scores ---//
				$.get('ITS_screen_AJAX2.php', {ajax_args: "updateScores", ajax_data: ''}, function(data) {
				    //alert($(this).data('chosen'));
            $('#scoreContainerContent').html(data); 
        })
		} 
		else {
		   $('#errorContainer').html('Please select an answer.').css({display: 'inline'});
		}
				//$("#dialog").dialog('close');
				/*
	      var myans = Array();
				myans = $('input.ansCheck:checkbox').attr("checked",true).attr("id");
	     alert(myans.length);
        var qid   = $(this).attr("qid");
        var qtype = $(this).attr("qtype");
        var L = $("li[name=matchingLeft]").length;                 // no. left choices
        var M = $("INPUT[@name='check'][type='checkbox']").length; // no. matrix elements
        var values = new Array();
        
        for (var r = 0; r < M/L; r++) {
            for (var c = 1; c <= L; c++) {
              if ($('#check_'+(r+1)+'_'+c).attr("checked")) { values[r] = c; }
            }
            if (values[r] == null) { values[r] = ''; }
        }
        var chosen = values.join();
				//alert(qid+'~'+qtype+'~'+chosen);

        $.get('ITS_screen_AJAX2.php', { ajax_args: "recordChapterAnswer", ajax_data: qid+'~'+qtype+'~'+chosen}, function(data) {
				    //alert($(this).data('chosen'));
            $('#contentContainer').html(data);
						$('.check').button();  			
						$('.ui-widget-content .ui-state-default').css({background: '#ffffff', border: '2px solid #cccccc',color: '#080000',cursor: "default"});     
						$('.ui-button').css({ width: '20px', height: '20px' });
	          $('.ui-button-text-only .ui-button-text').css({ padding: '0.1em' });   
        })*/
	 })
	 /*-------------------------------------------------------------------------*/
	/*-------------------------------------------------------------------------*/	
});
</script>
<script type="text/javascript">
    $(function() {
      var stop = false;
      $("#hel h3").click(function(event) {
      	if (stop) {
      		event.stopImmediatePropagation();
      		event.preventDefault();
      		stop = false;
      	}
      });
      $("#accor").accordion({header: "> div > h3"}).sortable({axis: "y",handle: "h3",stop: function(event, ui) {stop = true;}});
      $("#accor").accordion({autoHeight: false,navigation: true,collapsible:true,fillSpace:false}); //active: 1,
			$('.ITS_ghost').css({height: 'auto'});
			//$( ".selector" ).accordion( "option", "clearStyle", true );
      $("#selectable").selectable();
      $("#sortable1").sortable({ connectWith: '.connectedSortable'}).disableSelection();
			$("input.check").button();  
});
</script>
</body>
</html>
