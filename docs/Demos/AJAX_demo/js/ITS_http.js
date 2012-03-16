/*
ITS_http.js - AJAX function file for question control
						  objects: CANCEL | SAVE, called from ITS.js

Author(s): Greg Krudysz
Date: Sep-05-2008
*/
var xmlHttp
//-------------------------------------------------//
function ITS_QCONTROL(qNum,qTarget,Control)
//-------------------------------------------------//
//  qNum    - question number
//  qTarget - upadte container id
//  Control - PHP (ITS_Control.php) operation: 'QUESTION' | 'CANCEL' | 'SAVE'
{ 
//alert(qTarget);
// alert("TXA_"+qTarget);
// fetch unaltered string from the DB according to qNum and qType
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return;
}

//var qinfo_obj = parent.frames[1].document.getElementById('ITS_question_info');
var qinfo_obj = document.getElementById('ITS_NAVIGATE');
//var qNum = qinfo_obj.Q_num;

//alert(qNum);
//die();
//qinfo_obj.setAttribute("CurrentTarget",qTarget);
//alert("TXA_"+qTarget);
obj = document.getElementById("TXA_"+qTarget);
//alert(obj);
// replace "+" character (denotes space in xmlHttp) 
//-strTarget = document.getElementById("TXA_"+qTarget).value
//var Target = strTarget.replace('+',"%2B"); 
//-Target = encodeURIComponent(strTarget);

//alert(Target);
var Target = 'Hello';

var url="ITS_control.php"
url=url+"?qNum="+qNum
url=url+"&qTarget="+qTarget
url=url+"&Control="+Control
url=url+"&Target="+Target
url=url+"&sid="+Math.random()

//alert(escape(str));
//alert(escape(document.getElementById("TXA_"+qTarget).value));

xmlHttp.onreadystatechange=ITS_QCONTROL_UPDATE
xmlHttp.open("POST",url,true)
xmlHttp.send(null)
}
//-------------------------------------------------//
function ITS_QCONTROL_UPDATE() { 
//-------------------------------------------------//
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { // obj_targ.innerHTML.replace(/</g,'&lt;').replace(/\n/g,'<br>');

	// workaround to get/set current target
	//var qinfo_obj = document.getElementById('ITS_question_info');
  //document.getElementById(qinfo_obj.getAttribute("CurrentTarget")).innerHTML = xmlHttp.responseText;

	var strTarget = document.getElementById("ITS_question_container");
	strTarget.innerHTML = xmlHttp.responseText;

	// set controls back to edit mode
  //ITS_QCONTROL_EDITMODE()
 }
}
//-------------------------------------------------//
function htmlEncode(s) {
//-------------------------------------------------//
 return s.replace(/&(?!\w+([;\s]|$))/g, "&pi;")
 .replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
//-------------------------------------------------//
 function escapeHTMLEncode22(str) {
  var div = document.createElement('div');
  var text = document.createTextNode(str);
  div.appendChild(text);
  return div.innerHTML;
 }
//-------------------------------------------------//
function GetXmlHttpObject() {
//-------------------------------------------------//
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e) {
 //Internet Explorer
 try {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e) {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
//-------------------------------------------------//