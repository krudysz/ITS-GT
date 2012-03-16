//=================================================//
/*
ITS_AJAX.js - set of AJAX functions

  usage: Javascript: ITS_AJAX('my_file.php',array('arg1','arg2'),2,JS_FUNCTION,OBJ);
	  ... in php use as: echo '... onClick=ITS_AJAX(\'my_file.php\',\'array('arg1','arg2')\',...)';
	              PHP: $args = $_GET['ajax_args'];
				 Javascript: JS_FUNCTION('php_string');
				 
Author(s): Greg Krudysz
Date: Sep-9-2009
*/
//=================================================//

/*
   AJAX.onreadystatechange = function() {                      // When the browser has the request info..
            if (AJAX.readyState==4 || AJAX.readyState=="complete") { //   see if the complete flag is set.
               LayerID.innerHTML=AJAX.responseText;                  //   It is, so put the new data in the object's layer
               delete AJAX;                                          //   delete the AJAX object since it's done.
               updating=false;                                       //   Set the updating flag to false so we can do a new request
               that.callback();                                      //   Call the post-processing function.
            }

*/
var xmlHttp;
//-------------------------------------------------//
function ITS_AJAX(php_file,php_arguments,user_data,js_file,target_obj) { 
//-------------------------------------------------//
//alert(user_data)
xmlHttp=GetXmlHttpObject()

if (xmlHttp==null) {
 alert ("Browser does not support HTTP Request")
 return;
}
// redirect to "php_file(php_arguments)"
var url=php_file
url=url+"?ajax_args="+php_arguments
url=url+"&ajax_data="+user_data
url=url+"&sid="+Math.random()

xmlHttp.onreadystatechange=function() {
        if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
								js_file(target_obj,xmlHttp.responseText);
								//delete xmlHttp;
    }
xmlHttp.open("POST",url,true)
xmlHttp.send(null)

return false;
}
//-------------------------------------------------//
function GetXmlHttpObject() {
//-------------------------------------------------//
var xmlHttp=null;
try       {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e) {
  //Internet Explorer
  try       { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");   }
  catch (e) { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}
  }
return xmlHttp;
}
//=================================================//