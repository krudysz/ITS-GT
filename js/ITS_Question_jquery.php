<?php
/* =============================================================
  Author(s): Gregory Krudysz
  Last Update: Feb-19-2012
/* ============================================================= */
?>
<script type="text/javascript">
    $(document).ready(function() { 
		/*-------------------------------------------------------------------------*/ 
        $('.tag_del').live('click', function() { 
			var tid   = $(this).attr('tid');
			var tname = $(this).html();
			//var parentTag = $(this).parent().get(0).tagName;alert(parentTag);
			$(this).parent().parent().parent().parent().hide(800, function () {
				$(this).remove();
			});
			/*
			$.get('ITS_tag_AJAX.php', {
                ajax_args: "tag_del", 
                ajax_data: tid+','+tname
            }, function(data) {
                $('div.taginfo').html(data);							
            });*/
		});              
        /*-------------------------------------------------------------------------*/ 
        $('.tagref').live('click', function() {  
			var tid   = $(this).attr('tid');
			var tname = $(this).html();
			$.get('ITS_tag_AJAX.php', {
                ajax_args: "practiceMode", 
                ajax_data: tid+','+tname
            }, function(data) {
                $('div.taginfo').html(data);							
            });
		if ($("div.taginfo").is(":hidden")) {
			$("div.taginfo").slideDown("slow");
		} else {
			$("div.taginfo").hide();
		}
		}); 	
        /*-------------------------------------------------------------------------*/	
        $("#selectQtype").change(function() { doChange(); }).attr("onchange", function() { doChange(); });
        /*-------------------------------------------------------------------------*/
        function doChange() {			
            /*
      var sid     = $("#sortProfile").attr("sid");
      var section = $("#sortProfile").attr("section");
      var status  = $("#sortProfile").attr("status");
      var ch      = $("#sortProfile").attr("ch");
      var orderby = $("#sortProfile option:selected").text();
             */
            alert('change');
            /*
                        $.get('ITS_admin_AJAX.php', { ajax_args: "orderProfile", ajax_data: sid+'~'+section+'~'+status+'~'+ch+'~'+orderby}, function(data) {
                          //alert(data);
                                //$("#userProfile").html(data); 
                                $("#selectQtype").change(function() { doChange(); });
      });
             */			
        }	
        /*================= UPLOADIFY ===========================================*/
        /*== NOT WORKING IN FF ?? ==*//*
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
        //$("#select_class").buttonset(); /*== NOT WORKING IN FF ?? ==*/
        /*-------------------------------------------------------------------------*/
        $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/
		/*
		$("input[name='question_nav']").live('click', function(event) {
			
		  var nav = $(this).val();
		  var qid = $('#ITS_QCONTROL_TEXT').attr("value");
		  
		  switch(nav){
                case '>>': qid++;break;
                case '<<': qid--;break;
		  }
		  
		  //alert(nav+' , '+qid);

          $.get('ITS_admin_AJAX.php', { ajax_args: "getQuestionMeta", ajax_data: qid}, function(data) {       
                alert(data);
                //$('#metaContainer').html(data);
          });				
		});
		*/	      	
        /*-------------------------------------------------------------------------*/
        $('#testme2').live('click', function(event) {
			$("#ImgDlg").dialog('close');
            //if ($.browser.ff && event.which == 1) { alert('ff'); }
            //if (event.which != 0) return true;
            //if (event.which != 1) return true;														
            //var del = $(this).attr("del"); //
            //console.log('find was called');
            //var qid = $('#ITS_QCONTROL_TEXT').attr("value");
            //var del = $(this).attr("del"); //alert(delta);
            var qid = 17;
            //alert($('#X11').attr("id"));
            //$('#X11').html("id");
/*
            $("form").submit(function () { 
				alert('form');
				//return false; 
				}); // so it won't submit

            $.get('ITS_admin_AJAX.php', { ajax_args: "uploadImage", ajax_data: qid}, function(data) {
				//alert(data);
                $('#X11').html(data);  
            })    
*/                   
        });	
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
            //$(this).css('background', 'red');
            //alert('da');
            //var del = $(this).attr("del"); //alert(delta);
            //$.get('ITS_admin_AJAX.php', { ajax_args: "getQuestionMeta", ajax_data: qid}, function(data) {
            //   $('#metaContainer').html(data);  
            //})
        });			
        /*-------------------------------------------------------------------------*/
        $( "#createQuestion" ).live('click', function(event) {
            var qtype = $(this).attr("qtype");
            //alert(qtype);

            $.get('ITS_admin_AJAX.php', { ajax_args: "createQuestion", ajax_data: 'new~'+qtype }, function(data) {
                //alert(data);
                var dialog = $(data).appendTo('#ITS_question_container');
                dialog.attr("id","createQuestionDialog");
                dialog.dialog({show:'blind',hide:'slide',resizable:true,width:'80%',height:'auto',modal:false,
                    /*
                                                                 buttons: { "Add Question": function() { $( this ).dialog( "close" );
                                                                        $.get('ITS_admin_AJAX.php', { ajax_args: "AddQuestionDialog", ajax_data: uid}, function(data) {
                                          //alert(data); //$('#contentContainer').html(data); 
                                                });
                                                 },
                                          Cancel: function() { $( this ).dialog( "close" ); }
                                     },*/
                    close: function() {
                        //alert('on closing ...');
                    }
                });
                //alert('coic');
                //$("#createQuestionDialog").css("background","#FF9");
                $("li[name='qtype']").each(function(index) {	
                    if ($(this).attr("qtype")==qtype){$(this).children("a").attr('id','current');}
                    else                             {$(this).children("a").attr('id','');}
                })
                //$("#ITS_qtype").change(function() { doChange1(); }).attr("onchange", function() { doChang1();  });
                /*------------*/
            });
        });
		
        function doChange1() {			
            //$('#ITS_qtype').css('border','2px solid red');
            /*
      var sid     = $("#sortProfile").attr("sid");
      var section = $("#sortProfile").attr("section");
      var status  = $("#sortProfile").attr("status");
      var ch      = $("#sortProfile").attr("ch");
      var orderby = $("#sortProfile option:selected").text();
                        //alert(sid+'~'+orderby);
      $.get('ITS_admin_AJAX.php', { ajax_args: "orderProfile", ajax_data: sid+'~'+section+'~'+status+'~'+ch+'~'+orderby}, function(data) {
                          //alert(data);
                                $("#userProfile").html(data); 
                                $("#sortProfile").change(function() { doChange(); });
      });			
             */
        }
        /*-------------------------------------------------------------------------*/		
        $( "li[name='qtype']" ).live('click', function(event) {
            //alert($("#Qform").attr("id"));
            var qtype = $(this).attr("qtype");
            $("li[name='qtype']").each(function(index) {	
                if ($(this).attr("qtype")==qtype){$(this).children("a").attr('id','current');}
                else                             {$(this).children("a").attr('id','');}
            });
            var a,ans, n;
            var css = 'text ui-widget-content ui-corner-all ITS_Q';
            var lbl = '<b>ANSWERS:</b><br>';
            var sel = '<input type="button" name="changeAnswer" id="remAnswer" v="-" value="&mdash;" class="ITS_buttonQ" /><br>'
					 +'<input type="button" name="changeAnswer" id="addAnswer" v="+" value="+"       class="ITS_buttonQ" />';		
            switch(qtype){
                //------------------//
                case 'mc':
                    //------------------//
                    n = 4;
                    ans = '<table id="ITS_Qans" class="ITS_Qans" n="'+n+'" qtype="'+qtype+'">';
                    for (a=1;a<=n;a++){     
                        ans += '<tr>'
                            +'<td width="10%"><label for="answer'+a+'">answer&nbsp;'+a+'</label></td>'
                            +'<td width="60%"><textarea name="answer'+a+'" id="answer'+a+'" value="" class="'+css+'" /></td>'
                            +'<td width="10%"><label for="weight'+a+'">weight&nbsp;'+a+'</label></td>'
                            +'<td width="20%"><input type="text" name="weight'+a+'" id="weight'+a+'" value="" class="'+css+'" /></td>'
                            +'</tr>';
                    }
                    ans += '</table>'
						  +'<input type="hidden" name="answers" id="answers" value="'+n+'" />';
                    ans = '<td>'+lbl+sel+'</td>'
                        +'<td>'+ans+'</td>';
                    break;
                //------------------//
            case 'm':
                //------------------//
                n = 4;
                ans = '<table id="ITS_Qans" class="ITS_Qans" n="'+n+'" qtype="'+qtype+'">';
                for (a=1;a<=n;a++){      
                    ans += '<tr>'
                        +'<td width="10%"><label for="L'+a+'">L&nbsp;'+a+'</label></td>'
                        +'<td width="50%"><textarea name="L'+a+'" id="answer'+a+'" value="" class="'+css+'" /></td>'
                        +'<td width="10%"><label for="R'+a+'">R&nbsp;'+a+'</label></td>'
                        +'<td width="50%"><textarea name="R'+a+'" id="R'+a+'" value="" class="'+css+'" /></td>'
                        +'</tr>';								 
                }
                ans += '</table>'
						+'<input type="hidden" name="answers" id="answers" value="'+n+'" />';
                ans = '<td>'+lbl+sel+'</td>'
                    +'<td>'+ans+'</td>';
                break;
            //------------------//
        case 'c':
            //------------------//
            lbl = '<b>Number of Variables:</b><br>';
            n = 1;
            /*ans = '<table id="ITS_Qans" class="" n="'+n+'" qtype="'+qtype+'">'
                +'<tr>'
                +'<td width="10%"><label for="formula">formula</label></td>'
                +'<td width="90%" colspan="6"><textarea name="formula" id="formula" value="" class="'+css+'" /></td>'
                +'<td><input type="hidden" name="vals" id="vals" value="'+n+'" /></td>'
                +'</tr>';
                */
            // Khyatis Changes
            
            ans = '<table id="ITS_Qans" class="" n="'+n+'" qtype="'+qtype+'">'
                +'<tr><td><LABEL>Number of Formulaes</LABEL></td>'
                +'<td><input type="button" value="+" id="add_fcount" class="ITS_buttonQ"></td><td><input type="button" id="dec_fcount" value="-" class="ITS_buttonQ"></td>'
				+'<td  width="90%">Weights must sum up to 100</td></tr>';
			ans +='<tr><input type="hidden" name="answers" id="answers" value="1" /></tr>';
			ans +='<tr class="formla" id="formulaes1"><td width="10%"><label for="text1">Text</label></td>'
                +'<td width="30%" ><textarea name="text1" id="text1" value="" class="'+css+'" /></td>'
   				+'<td width="10%"><label for="formula">Formula</label></td>'
			    +'<td width="30%" colspan="4"><textarea name="formula" id="formula" value="" class="'+css+'" /></td>'
                + '<td width="10%"><label for="weight1">Weight</label></td><td width="20%"><input type="text" class="'+css+'" MAXLENGTH=3 name="weight1" id="weight1"></td>'
                +'</tr>';
            ans+='<tr><td><input type="hidden" name="vals" id="vals" value="'+n+'" /></td></tr>';
            
             // Khyatis Changes
            for (a=1;a<=n;a++){							
                ans += '<tr>'
                    +'<td width="10%"><label for="value'+a+'">value&nbsp;'+a+'</label></td>'
                    +'<td width="40%"><input type="text" name="val'+a+'" id="answer'+a+'" value="" class="'+css+'" /></td>'
                    +'<td width="10%"><label for="minvalue'+a+'">min</label></td>'
                    +'<td width="10%"><input type="text" name="min_val'+a+'" id="minvalue'+a+'" value="" class="'+css+'"/></td>'
                    +'<td width="10%"><label for="maxvalue'+a+'">max</label></td>'
                    +'<td width="10%"><input type="text" name="max_val'+a+'" id="maxvalue'+a+'" value="" class="'+css+'"/></td>'													
                    +'</tr>';
            }
            ans += '</table>';
            ans = '<td>'+lbl+sel+'</td>'
                +'<td>'+ans+'</td>';
            break;			
        //------------------//
    case 's':
        //------------------//
        n = 1;
        ans = '<table id="ITS_Qans" class="ITS_Qans" n="'+n+'" qtype="'+qtype+'">'
            +'<tr>'
            +'<td width="10%"><label for="answer'+n+'">answer&nbsp;'+n+'</label></td>'
            +'<td width="85%"><textarea name="ans" id="answer" value="" class="'+css+'" /></td>'
            +'</tr>'
            +'</table>';										
        ans = '<td>'+lbl+sel+'</td>'
            +'<td>'+ans+'</td>';
        break;				 
    //------------------//
case 'p':
    //------------------//
    n = 1;
    ans = '<table id="ITS_Qans" class="ITS_Qans" n="'+n+'" qtype="'+qtype+'">'
        +'<tr>'
        +'<td width="10%"><label for="template">template</label></td>'
        +'<td width="85%"><textarea name="template" id="template" value="" class="'+css+'" /></td>'									 
        +'</tr>'
        +'<tr>'
        +'<td width="10%"><label for="answer">answer</label></td>'
        +'<td width="85%"><textarea name="answer" id="answer" value="" class="'+css+'" /></td>'
        +'</tr>'
        +'</table>';										
    ans = '<td>'+lbl+'</td><td>'+ans+'</td>';
    break;				 	 
}
$("#ansQ").html(ans);
});

/* NEW CHANGE by Khyati - 15th Feb 2012*/
$('#add_fcount').live('click',function() {
	//alert('hello');
	var css   = 'text ui-widget-content ui-corner-all ITS_Q';
	var n1 =  parseInt($("#answers").val());
	var new_value  = n1+1;
   
    if(new_value<=4){
	 $("#answers").val(new_value);
		var tr = '<tr id="formulaes'+new_value+'">'
					+'<td width="10%"><label for="text">Text'+new_value+'</label></td>'
					+'<td width="30%" ><textarea name="text'+new_value+'" id="text'+new_value+'" value="" class="'+css+'" /></td>'
                    +'<td width="10%"><label for="formula">Formula'+new_value+'</label></td>'
					+'<td width="90%" colspan="6"><textarea name="formula'+new_value+'" id="formula'+new_value+'" value="" class="'+css+'" /></td>'
					+'<td width="10%"><label for="weight1">Weight'+new_value+'</label></td><td width="40%"><input type="text" MAXLENGTH=3 name="weight'+new_value+'" class="'+css+'" id="weight'+new_value+'"></td>'
					+'</tr>';
		$("#formulaes"+n1).after(tr);
	}
    
});


$('#dec_fcount').live('click',function() {
	var css   = 'text ui-widget-content ui-corner-all ITS_Q';
	var n1 =  parseInt($("#answers").val());
	var new_value  = n1-1;
    if(new_value>0){
		$("#answers").val(new_value);
		$("#formulaes"+n1).remove();
	}
    
});
/*   NEW CHANGE ENDS   */
/*-------------------------------------------------------------------------*/		
$( "input[name=changeAnswer]" ).live('click', function(event) {

var css   = 'text ui-widget-content ui-corner-all ITS_Q';
var qtype = $("#ITS_Qans").attr("qtype");
var n  = Number($("#ITS_Qans").attr("n"));
var v  = $(this).attr("v");  
var td = '';
var n1 = n+1;   //alert(n+' '+v+' '+qtype);
switch(v){
    case '+':
        if (n<23){ 
            switch(qtype){
                case 'mc':
                    td = '<td width="10%"><label for="answer'+n1+'">answer&nbsp;'+n1+'</label></td>'
                        +'<td width="40%"><textarea name="answer'+n1+'" id="answer'+n1+'" value="" class="'+css+'" /></td>'
                        +'<td width="10%"><label for="weight'+n1+'">weight&nbsp;'+n1+'</label></td>'
                        +'<td width="40%"><input type="text" name="weight'+n1+'" id="weight'+n1+'" value="" class="'+css+'" /></td>';
                    break;
                case 'm':				
                    //alert('aa');											
                    td = '<td width="10%"><label for="L'+n1+'">L&nbsp;'+n1+'</label></td>'
                        +'<td width="50%"><textarea name="L'+n1+'" id="answer'+n1+'" value="" class="'+css+'" /></td>'
                        +'<td width="10%"><label for="R'+n1+'">R&nbsp;'+n1+'</label></td>'
                        +'<td width="50%"><textarea name="R'+n1+'" id="R'+n1+'" value="" class="'+css+'" /></td>';
                    //alert(td);	 
                    break;			 
                case 'c':					
                    //alert(qtype+' '+n);
                    td = '<td width="10%"><label for="value'+n1+'">value&nbsp;'+n1+'</label></td>'
                        +'<td width="20%"><input type="text" name="val'+n1+'" id="answer'+n1+'" value="" class="'+css+'" /></td>'
                        +'<td width="10%"><label for="minvalue'+n1+'">min</label></td>'
                        +'<td width="20%"><input type="text" name="min_val'+n1+'" id="minvalue'+n1+'" value="" class="'+css+'" /></td>'
                        +'<td width="10%"><label for="maxvalue'+n1+'">max</label></td>'
                        +'<td width="20%"><input type="text" name="max_val'+n1+'" id="maxvalue'+n1+'" value="" class="'+css+'" /></td>';
                    
                    $("#vals").attr("value",n1);
                    break;		
                case 's':					
                    //alert(qtype+' '+n);
                    td = '<td width="15%"><label for="answer'+n1+'">answer&nbsp;'+n1+'</label></td>'
                        +'<td width="25%"><textarea name="ans'+n1+'" id="answer'+n1+'" value="" class="'+css+'" /></td>';
                    break;												 
                }				
                $("#ITS_Qans tbody").append('<tr>'+td+'</tr>');
                $("#ITS_Qans").attr("n",eval(n+v+1));	// n (+/-) 1
            }
            break;
        default:
            if (n>1){ 
                $("#answer"+n).parent().parent().remove();
                //$("#answer"+n).css("border","2px solid red");
                $("#ITS_Qans").attr("n",eval(n+v+1));
                switch(qtype){
                    case 'mc':  $("#vals").attr("value",n+v+1);
                        break;
                }
            }
            break;
        }
    });	
    /*-------------------------------------------------------------------------*/				
    $( "#cloneQuestion" ).live('click', function(event) {
        var qid = $(this).attr("qid");
        var qtype = $(this).attr("qtype");
        //alert(qtype);
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
            $("li[name='qtype']").each(function(index) {	
                if ($(this).attr("qtype")==qtype){$(this).children("a").attr('id','current');}
                else                             {$(this).children("a").attr('id','');}
            })    
        });
        //$( "#dialog-form" ).dialog("open");
    }); 	
    /*-------------------------------------------------------------------------*/		
    $( "#deleteQuestion" ).live('click', function(event) {
        var qid    = $(this).attr("qid");
        var qtype  = 'mc'; //$(this).attr("qtype");
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
    });		
    /*-------------------------------------------------------------------------*/		
    $("#submitDialog").live('click', function(event) {	
        var qtype = $("#ITS_Qans").attr("qtype");
        var n     = $("#ITS_Qans").attr("n");
        if(qtype=="mc" || qtype=="m"){
			$("#answers").val(n);
			alert('value odf answers now is made' + n + 'lets check:' + $("#answers").val());
		}
        var str   = $("#Qform").serialize();  // name,value & ...
        
        
       // -> khyatis modification: commented str       = 'qtype='+qtype+'&answers='+n+'&'+str;
         str       = 'qtype='+qtype+'&'+str;
        // 
        //alert($("#ITS_Qans").attr("n"));
        $.get('ITS_admin_AJAX.php', { ajax_args: "addQuestion", ajax_data: str}, function(data) {   
            $('#ITS_question_container').append(data);
        });
        /*
                         $( "#users tbody" ).append( "<tr>" +
                                "<td></td>" +
                                                        "<td>MC</td>" +
                                                        "<td>" + $("#title").val() + "</td>" + 
                                                        "<td>" + $("#image").val() + "</td>" + 
                                                        "<td>" + $("#question").val() + "</td>" +
                                                "</tr>" );
         */
        $("#createQuestionDialog").remove();			
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
    /*-------------------------------------------------------------------------*/		 
    $("#ansUpdate").live('click', function(event) {	
        var action = $(this).attr("action");
        var qid = $("#answers").attr("qid");// alert(qid);
        var N = $("#answers").val();  
        //alert(qid+'~'+action+'~'+N);
        $.get('ITS_admin_AJAX.php', { ajax_args: "editAnswers", ajax_data: qid+'~'+action+'~'+N}, function(data) {   
            $('#ITS_Qans').html(data);
        });		
    });		 
    /*-------------------------------------------------------------------------*/		
    $("#cancelDialog").live('click', function(event) {	
        $("#createQuestionDialog").remove();   //detach();
    });	
    /*-------------------------------------------------------------------------*/		
    $("#ITS_image_TARGET").live('click', function(event) {	
        //alert('da');
        //$("#dialog-form").remove();   //detach();
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
    $(".ITS_button[name='editMode']").live('click', function(event) {
		// EDIT TEXT BOXES
        $('span.ITS_QCONTROL').each(function(index) {
            //<a href="#" name="ITS_EDIT" class="ITS_EDIT" ref="'+$(this).attr("id")+'"> Edit </a>
            $(this).html('<div id="navEdit"><ul id="navlistEdit"><li><a href="#" name="ITS_EDIT" ref="'+$(this).attr("id")+'"> Edit </a></li></ul></div>');
            var idd = $(this).attr("id");
            //alert('#'+idd+'_TARGET');
            $('#'+idd+'_TARGET').css({'border': '2px dotted silver'});
        });
        // EDIT IMAGES
        $('span.ITS_ICONTROL').each(function(index) {
			//alert($(this).attr("ref"));
            //<a href="#" name="ITS_EDIT" class="ITS_EDIT" ref="'+$(this).attr("id")+'"> Edit </a>
            $(this).html('<div id="navEdit"><ul id="navlistEdit"><li><a href="#" name="ITS_IMG" ref="'+$(this).attr("ref")+'"> Image </a></li></ul></div>');
            var idd = $(this).attr("id");
            //alert('#'+idd+'_TARGET');
            $('#'+idd+'_TARGET').css({'border': '2px dashed #666','padding':'0.25em','overflow':'auto'});
        });        
        
    });		
    /*-------------------------------------------------------------------------*/
    $("a.[name='ITS_EDIT']").live('click', function(event) {
		
        //alert('ITS_EDIT');
        var obj_id = $(this).attr("ref");
        var tar_id = obj_id+'_TARGET';
        $('#'+tar_id).css({'border': '2px solid blue','background-color':'#FFFFFF'});
			
        var str = $('#'+tar_id).attr("code");
        //alert(str);
			
        //--- width | height ---//
        var wt = $('#'+tar_id).width();
        if (wt < 100 ) { wt = 100; }
        if (obj_id=='ITS_QUESTION'){ 
            var ht = 120; 
            if (wt < 800 ) { wt = 800; }
        } 
        else { var ht = 30; }
        //---------------------//
			
        //var aastr = $('#'+tar_id).html();
        //alert(aastr)
			
        /* MATCHING JUNK *//*
            var str = $('#'+tar_id).html();
                        //var matches = str.match(/<img[^>]*latex=(/[^"]+(?=(" ")|"$)/ig);
                        // match: '<img *>'
			
                        var latex_pattern = /<img[^>](.*?)>/gi;
                        var matches = str.match(latex_pattern);
                  //var strr = str.replace(latex_pattern,"<b>$1</b>");
			
                        if(matches!=null) {
                          //alert(matches.length);
                                //alert(matches[0]);
                        }
			
                        //var matches = str.match(/<img[^>](.*?)>/i);
      //alert(matches.length+' : '+matches[0]+' '+matches[1]+' '+matches[2]);
                        /*
                        if(matches!=null) {
                                var tex_code = matches[1].match(/latex="(.*?)"/i);
          //alert(tex_code[1]);
          var str = str.replace(matches[0],'<latex>'+tex_code[1]+'</latex>');
         */
        /*
                                for(var i = 0; i < matches.length; i++) {
                                  //alert(matches[i]);
          
                                        //var tex_code = matches[i].match(/latex="(.*?)"/i);
          //alert(tex_code[1]);
          //var str = str.replace(matches[i],'<latex>'+tex_code[1]+'</latex>');
        }*/
        //}
        /*-----------------------*/
			
        // Call DB for string
        var TXA_str = '<textarea class="ITS_EDIT" id="TXA_'+tar_id+'" style="height:'+ht+'px;width:'+wt+'px">'+str+'</textarea>';
        //alert('TXA_'+tar_id);
        //onclick="trackPos(this)"
        $('#'+tar_id).html(TXA_str);
        /*
                        codemirror = CodeMirror.fromTextArea('TXA_ITS_QUESTION_TARGET', {
          height: "250px",
          width: "100%",
          parserfile: "parsesql.js",
          stylesheet: "/Content/codemirror/sqlcolors.css",
          path: "/Scripts/codemirror/",
          textWrapping: false,
          tabMode: "default",
          onChange: onTextChanged,
          initCallback: function() {
            codemirror.focus();
          }
        });*/

        //var list = '<ul class="ITS_EDIT"><li><a href="#" class="ITS_EDIT" name="ITS_SAVE" ref="'+obj_id+'">SAVE</a></li><li><a href="#" class="ITS_EDIT" name="ITS_CANCEL" ref="'+obj_id+'">CANCEL</a></li></ul>';
        //var list = '<a href="#" class="ITS_EDIT" name="ITS_SAVE" ref="'+obj_id+'"> Save </a><br><a href="#" class="ITS_EDIT" name="ITS_CANCEL" ref="'+obj_id+'"> Cancel </a>';
        var list = '<div id="navEdit"><ul id="navlistEdit"><li id="ITS_tagLATEX" class="ITS_addTAG" ref="'+obj_id+'" tag="<latex></latex>"><b>&lt;</b>latex<b>&gt;</b></li><li id="ITS_tagIMG" class="ITS_addTAG" ref="'+obj_id+'" tag="<image></image>" style="margin-bottom:20px"><b>&lt;</b>image<b>&gt;</b></li><li id="active"><a href="#" name="ITS_SAVE" ref="'+obj_id+'"> Save </a></li><li><a href="#" name="ITS_CANCEL" ref="'+obj_id+'"> Cancel </a></li></ul></div>';

        $('#'+obj_id).html(list);
        $("textarea.ITS_EDIT").resizable({handles: "se"});	
    });				
    /*-------------------------------------------------------------------------*/
    $("a[name='ITS_SAVE']").live('click', function(event) {
        var obj_id = $(this).attr("ref");
        var tar_id = obj_id+'_TARGET';			
        var tar_str = $('#TXA_'+tar_id).val();
        $('#'+tar_id).attr("code",tar_str);

        var ajx_str = htmlEncode(tar_str); //encodeURIComponent(tar_str);
        var qid = $('#ITS_QCONTROL_TEXT').val();
        //alert(ajx_str);
	
        var field = obj_id.replace(/ITS_/,'');
        var args = qid+',SAVE,'+field;

        $.get('ITS_control.php', { ajax_args: args, ajax_data: tar_str}, function(data) {  	   
            $('#'+tar_id).html(data); //htmlDecode //alert(data);
        });

        // Control ==> 'Edit'
        var editList = '<div id="navEdit"><ul id="navlistEdit"><li><a href="#" name="ITS_EDIT" ref="'+obj_id+'"> Edit </a></li></ul></div>';
        $('#'+obj_id).html(editList);
    });
    /*-------------------------------------------------------------------------*/
    $("a[name='ITS_IMG']").live('click', function(event) {
		var qid = $('#ITS_QCONTROL_TEXT').attr("value");
		var fld = $(this).attr("ref");
//alert(qid+' - '+fld);				
        var dialog = $('<div id="ImgDlg" style="ITS" fld="'+fld+'"></div>').appendTo('body');
        dialog.dialog({
			title: 'UPLOAD IMAGE FROM:',
            resizable: true,
            width: 'auto',
            height: 'auto',
            modal: false,
            buttons: { "My Computer": function() { 
						//$( this ).dialog( "close" );
                        $('#ImgDlg').html('<center><form name="ITS_file" action="ajax/ITS_image.php" enctype="multipart/form-data" method="POST"><input id="ITS_image_file" name="ITS_image" size="10" type="file"><input id="ITS_image_upload" name="upload" value="Upload" type="submit"><input type="hidden" name="qid" value="'+qid+'"><input type="hidden" name="fld" value="'+fld+'"></form></center>');
						$("#ImgDlg").dialog( "option", "title","From My Computer:");
						//$('#ITS_image_file').css('border','1px solid red');

                },"SERVER": function() { 
					/* Solution 1: Redirect to another page */
					window.location.replace('Image.php?id='+qid+'&f='+fld);
					//*/
					/* Solution 2: Redirect to lower div-page //
                    $.get('server_browser2.php', { ajax_args: "deleteQuestion", ajax_data: 0}, function(data) {
                        //$('#ImgDlg').html(data);
                        $( this ).dialog( "close" );
                        $('#ITS_question_container').html(data);
                    });
                    */
					/* Solution 3: Redirect to pop-up //              			   
					$.get('server_browser2.php', { ajax_args: "deleteQuestion", ajax_data: 0}, function(data) {
					    $("#ImgDlg").dialog( "option", "position","left-top","width",500,"height",500);
                        $('#ImgDlg').html(data);
                    });
                    */
                },"delete": function() { 
						//$( this ).dialog( "close" );
                        $('#ImgDlg').html('<center><input name="ITS_image_delete" value="Yes, delete it" type="button" qid="'+qid+'" fld="'+fld+'"><input name="ITS_image_delete" value="No" type="button">');
						$("#ImgDlg").dialog( "option", "title","Delete this image from question?");
						//$('#ITS_image_file').css('border','1px solid red');

                },
                Cancel: function() { $( this ).dialog( "close" ); }
            }
        });
		/*
                var dialog = $(this).parent().appendTo('#ITS_question_container');
                dialog.attr("id","createImageDialog");
					   resizable:false,width:'80%',height:'auto',modal:false,
                       buttons: { "Local": function() { 
                                    $.get('ITS_admin_AJAX.php', { ajax_args: "AddQuestionDialog", ajax_data: uid}, function(data) {/*alert(data); //$('#contentContainer').html(data);*/ 
  /*                                  
                                    });
                                                 },
                                  "Server": function() {},
                                  Cancel: function() { $( this ).dialog( "close" ); }
                                     },
                    close: function() {}
				});*/
        /*
        var obj_id = $(this).attr("ref");
        var tar_id = obj_id+'_TARGET';			
        var tar_str = $('#TXA_'+tar_id).val();
        $('#'+tar_id).attr("code",tar_str);

        // Control ==> 'Edit'
        var editList = '<div id="navEdit"><ul id="navlistEdit"><li><a href="#" name="ITS_EDIT" ref="'+obj_id+'"> Edit </a></li></ul></div>';
        $('#'+obj_id).html(editList);
        */
    });    
    /*-------------------------------------------------------------------------*/
    $('#ITS_image_upload').live('click', function(event) {
			$("#ImgDlg").dialog('close');    
    });
    /*-------------------------------------------------------------------------*/
    $('input[name="ITS_image_delete"]').live('click', function(event) {
            if ($(this).val()=='Yes, delete it'){
			   var qid = $(this).attr('qid');
			   var fld = $(this).attr('fld');
              
			   $.get('ajax/ITS_image.php', {
				  ajax_args: "delete", 
				  ajax_data: qid+'~'+0+'~'+fld
			   }, function(data) {
				  $("#ImgDlg").dialog('close');		
				  window.location.replace('Question.php?qNum='+qid);	
			   });	     
			} else {
			   $("#ImgDlg").dialog( "option", "title","Image:").html(''); 
			}			   
    });    
    /*-------------------------------------------------------------------------*/
    $(".ITS_addTAG").live('click', function(event) {
        var obj_id = $(this).attr("ref");	
        var tag    = $(this).attr("tag");
        $("#TXA_"+obj_id+"_TARGET").insertAtCaret(tag);
    });		
    /*-------------------------------------------------------------------------*/		
    $("span.ITS_concept").live('click', function(event) { 
        var obj_id = $(this).attr("id");
        //alert(obj_id);
        /*
       $.get('ITS_admin_AJAX.php', { ajax_args: "getConcept", ajax_data: qid}, function(data) {
       $('#metaContainer').html(data);  
    })*/
        $(this).append('<div> SOME MATH </div>'); 
    });	
    /*-------------------------------------------------------------------------*/
    $("a[name='ITS_CANCEL']").live('click', function(event) {
        // Text-area "code" + "path" ==> target		
        var obj_id = $(this).attr("ref");
        var tar_id = obj_id+'_TARGET';			
        var cstr = $('#'+tar_id).attr("code");
        var path = $('#'+tar_id).attr("path"); //'/cgi-bin/mimetex.exe?';
        var str  = cstr.replace(/<latex>\s*([^>]*)?<\/latex>/gi ,'<img latex="$1" src="'+path+'$1"/>');
        //alert(str);
			
        // CSS
        $('#'+tar_id).html(str).css({'border': '2px dotted silver'});
			
        /*$('#'+target).html(function() {
                                var decoded = $("<div/>").html(tar_str).text(); //decode html entities
        return decoded;
        });*/
			
        // Control ==> 'Edit'
        var editList = '<div id="navEdit"><ul id="navlistEdit"><li><a href="#" name="ITS_EDIT" ref="'+obj_id+'"> Edit </a></li></ul></div>';
        $('#'+obj_id).html(editList);
    });		
    /*-------------------------------------------------------------------------*/	 
    /*$("#myImage").live('click', function(event) {
                        //var str = 'my string'; //$(this).innerHTML;
                        //var str = '<textarea class="ITS_EDIT" id="TXA_'+obj+'_TARGET" style="height:'+ht+'px">'+str+'</textarea>';
      var str = '<textarea class="ITS_EDIT" id="TXA_IMAGE_TARGET">Text</textarea>';
      
                        $(this).append(str);
     //obj_ctrl.innerHTML = '<a href="#" class="ITS_EDIT" onclick=ITS_QCONTROL("SAVE","'+obj+'")>SAVE</a><br>&nbsp;<br><a href="#" class="ITS_EDIT" onclick=ITS_QCONTROL("CANCEL","'+obj+'")>CANCEL</a>'
                });*/
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

//===========================================//	
function htmlEncode(value){ return $('<div/>').text(value).html(); }
function htmlDecode(value){ return $('<div/>').html(value).text(); }
/*------------------------------------------ */
$.fn.insertAtCaret = function (tagName) {
    return this.each(function(){
        if (document.selection) {
            //IE support
            this.focus();
            sel = document.selection.createRange();
            sel.text = tagName;
            this.focus();
        }
        else if (this.selectionStart || this.selectionStart == '0') {
            //MOZILLA/NETSCAPE support
            startPos = this.selectionStart;
            endPos = this.selectionEnd;
            scrollTop = this.scrollTop;
            this.value = this.value.substring(0, startPos) + tagName + this.value.substring(endPos,this.value.length);
            this.focus();
            this.selectionStart = startPos + tagName.length/2;
            this.selectionEnd = startPos + tagName.length/2;
            this.scrollTop = scrollTop;
        } 
        else {
            this.value += tagName;
            this.focus();
        }
    });
};
/*------------------------------------------ */
function renderQuestionAnswer(str){
    //alert('in it');
    var ques_str = '';
    var pattern = "<img src=\"/cgi-bin/mimetex.exe?(.*?)\">";
    var matches = str.match(pattern);
    if(matches!=null){
        //Pattern exists
        var replacement_str = '<latex>'+matches[0].slice(31,-2)+'</latex>';
        var myRegExp = new RegExp(pattern);
        var str = str.replace(myRegExp,replacement_str);
        //alert(str);
    } 
    else {
        //alert(str);	
    }
    return str;
}	
/*------------------------------------------ */
</script>
