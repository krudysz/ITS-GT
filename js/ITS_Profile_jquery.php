<?php
/* ============================================================= */
$LAST_UPDATE = 'Aug-24-2011';
  /*
  Author(s): Gregory Krudysz
  /* ============================================================= */
?>
<script type="text/javascript">
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$("#select_class").buttonset();
  });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
		/*-------------------------------------------------------------------------*/		
		 $("#deleteButton").live('click', function(event) {
		    var uid = $(this).attr("uid");
		    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		    $( "#deleteDialog:ui-dialog" ).dialog( "destroy" );
	      $( "#deleteDialog" ).dialog({
    			resizable: false,
    			height: 300,
					width: 500,
    			modal: true,
    			buttons: { "Delete Now": function() { $( this ).dialog( "close" );
    									$.get('ajax/ITS_admin.php', { ajax_args: "deleteDialog", ajax_data: uid}, function(data) {
                			  //alert(data); //$('#contentContainer').html(data); 
            					});
					         },
        				           Cancel: function() { $( this ).dialog( "close" ); }
        			     }
        }); 
		 });
		/*-------------------------------------------------------------------------*/		
		 $("#sortProfile").change(function() { doChange(); }).attr("onchange", function() { doChange(); });
	 /*-------------------------------------------------------------------------*/
	 	function doChange() {			
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
    }	
	 /*-------------------------------------------------------------------------*/
  });
</script>
