<?php
/* ============================================================= */
  $LAST_UPDATE = 'Sep-11-2011';
  /*
  Author(s): Gregory Krudysz
  /* ============================================================= */
?>
<script type="text/javascript">
	$(function() {
		alert('xx');
      $(".ITS_select").change(function() { document.ece2025.submit(); });
			$("#select_class").buttonset();
  });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
	  alert('yy');
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
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
