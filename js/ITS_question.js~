$(document).ready(function() { 
	/*-------------------------------------------------------------------------*/
	  /*================= UPLOADIFY ===========================================*/
		/* NOT WORKING IN FF ?? */
     $("#file_upload").uploadify({
        'uploader'  : 'uploadify/uploadify.swf',
        'script'    : 'uploadify/uploadify.php',
        'cancelImg' : 'uploadify/cancel.png',
        'folder'    : 'ITS_FILES/QTI/images',
    	  'multi'     : true,
        'auto'      : true,
    	  'fileExt'   : '*.jpg;*.gif;*.png',
        'fileDesc'  : 'Image Files (.JPG, .GIF, .PNG)'
     });
		 /*================= UPLOADIFY ===========================================*/
	   $(".ITS_select").change(function() { document.profile.submit(); });
		 $("#select_class").buttonset();
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

				alert('a');
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
	 //function doChange() {alert('ch');}
	 /*
	 	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		$( "#dialog-form:ui-dialog" ).dialog( "destroy" );
		var Qtitle = $( "#title" ),
			Qimage = $( "#image" ),
			Qquestion = $( "#question" ),
			allFields = $( [] ).add( Qtitle ).add( Qimage ).add( Qquestion ),
			tips = $( ".validateTips" );

		$( "#dialog-form" ).dialog( {		  
			autoOpen: false,
			height: 950,
			width: 850,
			modal: true,
			buttons: {
				"Create New Question": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					if ( true ) {
						$( "#users tbody" ).append( "<tr>" +
							"<td>" + Qtitle.val() + "</td>" + 
							"<td>" + Qimage.val() + "</td>" + 
							"<td>" + Qquestion.val() + "</td>" +
						"</tr>" ); 
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {$( this ).dialog( "close" );}
			},
			close: function() {allFields.val( "" ).removeClass( "ui-state-error" );}
		});
	});*/
/*----------------------*/
  });
