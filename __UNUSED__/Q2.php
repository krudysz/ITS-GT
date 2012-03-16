<html>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    //define config object
var dialogOpts = {
        title: "My First AJAX dialog",
        modal: true,
        autoOpen: false,
        height: 500,
        width: 500,
        open: function() {
        //display correct dialog content
        $("#example").load("showtext.php");}
        };
$("#example").dialog(dialogOpts);    //end dialog
    
    $('#showdialog').click(
        function (){
            $("#example").dialog("open");
            return false;
        }
    );

});
</script>
</head>
<body>
    <a class="ajax" href="http://www.google.com">
      Open as dialog
    </a>
<div id="showdialog">show it</div>
<div id="example"></div>

    <script type="text/javascript">
    $(function (){
        $('a.ajax').click(function() {
            var url = this.href;
            var dialog = $('<div style="display:hidden"></div>').appendTo('body');
            // load remote content
            dialog.load(
                url, 
                {},
                function (responseText, textStatus, XMLHttpRequest) {
                    dialog.dialog();
                }
            );
            //prevent the browser to follow the link
            return false;
        });
    });
    </script>
</body>
</html>

