$(document).ready(function() { 
    /*================= UPLOADIFY ===========================================*/
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
});