/*
ITS_book.js - JavaScript function file supporting "dSPFirst.php" only functions

Author(s): Greg Krudysz
Date: Aug-17-2010
*/
//-------------------------------------------------//
function ITS_chapter_select(non_obj){
//-------------------------------------------------//
alert('in ITS_chapter_select');
// !!-- obj.value is not supported on Gecko browsers --!!
var non_obj_value = non_obj.attributes.getNamedItem("value").value;
var non_obj_name  = non_obj.name;

var arr = new Array();
arr = document.getElementsByName(non_obj_name);

for(var i = 0; i < arr.length; i++) {
  var obj = document.getElementsByName(non_obj_name).item(i);
  obj.id = '';
  obj.onclick   = new Function('ITS_chapter_select(this)');
}
non_obj.id = 'current';  // non_obj.className = 'active';

// DEBUG: 
//alert('IN : '+non_obj_name+'  '+non_obj_value);
//alert(sessionStorage.getItem('meta')==null);

sessionStorage.setItem(non_obj.name,non_obj_value);
	
if (sessionStorage.getItem('chapter')=='undefined') { sessionStorage.setItem('chapter','1'); }       // chapter
if (sessionStorage.getItem('meta')=='undefined')    { sessionStorage.setItem('meta','paragraph'); }  // meta
if (sessionStorage.getItem('meta')==null)           { sessionStorage.setItem('meta','paragraph'); }  // meta

var ch = sessionStorage.getItem('chapter');
var m  = sessionStorage.getItem('meta');

// DEBUG: alert(ch+'  '+m);

// -- ITS_AJAX(php_file,php_arguments,user_data,js_file,target_obj) -- //
ITS_AJAX('ITS_screen_AJAX2.php','newChapter',ch,ITS_screen,'contentContainer');
//ITS_AJAX('ITS_book_AJAX.php',non_obj.name,ch+','+m,ITS_book,'page'); //bookContainer 

return false;
}
//-------------------------------------------------//
function ITS_book_select(non_obj){
//-------------------------------------------------//
// !!-- obj.value is not supported on Gecko browsers --!!
var non_obj_value = non_obj.attributes.getNamedItem("value").value;
var non_obj_name  = non_obj.name;

var arr = new Array();
arr = document.getElementsByName(non_obj_name);

for(var i = 0; i < arr.length; i++) {
  var obj = document.getElementsByName(non_obj_name).item(i);
  obj.id = '';
  obj.onclick   = new Function('ITS_book_select(this)');
}
non_obj.id = 'current';  // non_obj.className = 'active';

// DEBUG: 
//alert(non_obj_name+'  '+non_obj_value);
sessionStorage.setItem(non_obj.name,non_obj_value);
	
if (sessionStorage.getItem('chapter')=='undefined') { sessionStorage.setItem('chapter','1'); }       // chapter
if (sessionStorage.getItem('meta')=='undefined')    { sessionStorage.setItem('meta','paragraph'); }  // meta

var ch = sessionStorage.getItem('chapter');
var m  = sessionStorage.getItem('meta');

// DEBUG: alert(ch+'  '+m);

// -- ITS_AJAX(php_file,php_arguments,user_data,js_file,target_obj) -- //
ITS_AJAX('ITS_book_AJAX.php',non_obj.name,ch+','+m,ITS_book,'bookContainer'); 

return false;
}
//-------------------------------------------------//
function ITS_book(obj,txt){
//-------------------------------------------------//
	   var ta_obj = document.getElementById(obj);
		 ta_obj.innerHTML = txt;
}
//-------------------------------------------------//