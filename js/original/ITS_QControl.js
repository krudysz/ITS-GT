/*
ITS_QControl - main JavaScript function file for JS only functions

Author(s): Greg Krudysz
Date: Mar-14-2011
*/
//-------------------------------------------------//
function ITS_QCONTROL(CONTROL,TARGET) {
//-------------------------------------------------//
//--- Update Question Controls ---//

// get question number INPUT TEXT object
var QCTR_TEXT = document.getElementById('ITS_QCONTROL_TEXT');
var DATA = '';

switch(CONTROL) {
  /*------------------------------------------------------------------*/
  case 'PREV':
	/*------------------------------------------------------------------*/
    if (QCTR_TEXT.value==1){ QCTR_TEXT.value = 1;                 }
  	else 							     { QCTR_TEXT.value = QCTR_TEXT.value-1; }
    break;    
	/*------------------------------------------------------------------*/
  case 'NEXT':
	/*------------------------------------------------------------------*/
    QCTR_TEXT.value = Number(QCTR_TEXT.value)+1;
    break;
  /*------------------------------------------------------------------*/
  case 'CANCEL':
	/*------------------------------------------------------------------*/
  	var obj_ctrl = document.getElementById(TARGET);
  	obj_ctrl.innerHTML = '<a href="#" class="ITS_edit" name="ITS_EDIT_QCONTROL" onclick=ITS_QCONTROL_EDIT("'+TARGET+'")>Edit</a>';
		
		break;
	/*------------------------------------------------------------------*/
  case 'SAVE':
	/*------------------------------------------------------------------*/
  	var obj_ctrl = document.getElementById(TARGET);
  	obj_ctrl.innerHTML = '<a href="#" class="ITS_edit" name="ITS_EDIT_QCONTROL" onclick=ITS_QCONTROL_EDIT("'+TARGET+'")>Edit</a>';

  	DATA = document.getElementById("TXA_"+TARGET+"_TARGET").value;
  	DATA = encodeURIComponent(DATA);
  	//alert(DATA);
  	break;
	/*------------------------------------------------------------------*/
}

var args = new Array();
args[0]  = QCTR_TEXT.value; // question number
args[1]  = CONTROL;         // control
args[2]  = TARGET;          // target

ITS_AJAX("ITS_control.php",args,DATA,ITS_QCONTROL_UPDATE,TARGET);
}
//-------------------------------------------------//
function ITS_QCONTROL_UPDATE(obj_id,text) {
//-------------------------------------------------//
// -- called from ITS_QCONTROL: updates 'object_id' container
//alert(obj_id);

  switch(obj_id){
  case 'ITS_question_container':
    document.getElementById(obj_id).innerHTML = text;
    break;    
  default:
    document.getElementById(obj_id+"_TARGET").innerHTML = text;
  	break;
  }
}
//-------------------------------------------------//
function ITS_QCONTROL_EDITMODE(obj) { 
//-------------------------------------------------//
// -- displays EDIT-MODE: [Edit] links
/*
var spans = document.getElementsByTagName('span');
	
 if (spans) {
 	 for (var i = 0; i < spans.length; i++) {
			if (spans[i].className == 'ITS_QCONTROL') {
					 //spans[i].innerHTML='<a href="#" class="ITS_EDIT" name="ITS_EDIT_QCONTROL" onclick=ITS_QCONTROL_EDIT("'+spans[i].id+'")>EDIT</a>';
					   spans[i].innerHTML='<a href="#" class="ITS_EDIT" name="ITS_EDIT_QCONTROL" onclick=ITS_QCONTROL_EDIT("'+spans[i].id+'")>EDIT</a>';
			}
	 }
 }*/
}
//-------------------------------------------------//
function ITS_QCONTROL_EDIT(obj) {
//-------------------------------------------------//
// obj: 'ITS_TITLE' | 'ITS_QUESTION'
  //alert(obj);
	var obj_ctrl = document.getElementById(obj);
	var obj_targ = document.getElementById(obj+"_TARGET");
	/*
	//obj_targ.innerHTML.replace(/</g,'&lt;').replace(/\n/g,'<br>');
	//alert(obj.offsetwidth);
	//alert(escape(obj_targ.value).split('%0A').length);
	//var s=obj_targ.innerHTML.split("\r\n");
	//alert(s.length);
  */
	// NEEDS WORK: dynamically adjust size of TA window
	if (obj=='ITS_QUESTION'){ var ht = 120; } 
	else                    { var ht = 30;  }
	
	var str      = obj_targ.innerHTML();

  //var pattern = "<img src=\"/cgi-bin/mimetex.exe?(.*?)\">"
	//var pattern = '</?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?>'; 
	//var pattern = "<img latex=\"(.*?)\">";
	//var pattern = "/<img[^>]+>/";
	//var pattern = '/<IMG([^>]*)\ssrc=(["].*?)/';
	//var pattern = '/\< *[img][^\>]*[.]*\>/i';
	//"<img * latex=\"(.*?)\" *>"
	//var patt1 = '/<img[^>]*latex="(.*?)"*\>/';
	
  var matches = str.match(/<img[^>]*latex="(.*?)"*\>/i);
  //alert(matches);
	if(matches!=null) {
    var str = str.replace(matches[0],'<latex>'+matches[1]+'</latex>'); 
  }
  obj_targ.innerHTML = '<textarea class="ITS_EDIT" id="TXA_'+obj+'_TARGET" style="height:'+ht+'px">'+str+'</textarea>';
	//obj_ctrl.innerHTML = '<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("'+NAV_TXT.value+'","'+obj+'_TARGET","CANCEL")`>Cancel</a><b> ]</b>&nbsp;<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("'+NAV_TXT.value+'","'+obj+'_TARGET","SAVE")`>Save</a><b> ]</b>'
  //obj_ctrl.innerHTML = '<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("CANCEL")`>Cancel</a><b> ]</b>&nbsp;<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("SAVE")`>Save</a><b> ]</b>'
  obj_ctrl.innerHTML = '<a href="#" class="ITS_EDIT" onclick=ITS_QCONTROL("SAVE","'+obj+'")>SAVE</a><br>&nbsp;<br><a href="#" class="ITS_EDIT" onclick=ITS_QCONTROL("CANCEL","'+obj+'")>CANCEL</a>';
  $("textarea.ITS_EDIT").resizable({handles: "se"});
}
//-------------------------------------------------//