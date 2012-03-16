/*
ITS - main JavaScript function file for JS only functions

Author(s): Greg Krudysz
Date: Aug-28-2009
*/
//-------------------------------------------------//
function ITS_QCONTROLS(button) {
//-------------------------------------------------//
var NAV_TXT = document.getElementById('ITS_NAVIGATE_NUM');

//alert(button);

switch(button)
{
case 'PREV':
  if (NAV_TXT.value==1){ NAV_TXT.value = 1;               }
	else 							   { NAV_TXT.value = NAV_TXT.value-1; }
  break;    
case 'NEXT':
  NAV_TXT.value = Number(NAV_TXT.value)+1;
  break;
default:
  //NAV_TXT.value = button; 
}
//admin_txt.innerHTML = Q_obj.name;
ITS_QCONTROL(NAV_TXT.value,'ITS_question_container','QUESTION');
}
//-------------------------------------------------//
function ITS_QCONTROL_EDITMODE() { 
//-------------------------------------------------//
var spans = document.getElementsByTagName('span');
	
 if (spans) {
 	 for (var i = 0; i < spans.length; i++) {
			if (spans[i].className == 'ITS_QCONTROL') {
					 spans[i].innerHTML='<a href=`#` class=`ITS_edit` onclick=`ITS_QCONTROL_EDIT("'+spans[i].id+'")`>Edit</a>';
			}
	 }
 }
}
//-------------------------------------------------//
function ITS_QCONTROL_EDIT(obj) {
//-------------------------------------------------//
alert('HELLO');
// obj: 'ITS_TITLE' | 'ITS_QUESTION'

var NAV_TXT = document.getElementById('ITS_NAVIGATE_NUM');

	// get question number and type
	var qinfo = document.getElementById("ITS_NAVIGATE_NUM");
	var obj_ctrl = document.getElementById(obj);
	var obj_targ = document.getElementById(obj+"_TARGET");
	
	//obj_targ.innerHTML.replace(/</g,'&lt;').replace(/\n/g,'<br>');
	//alert(obj.offsetwidth);
	//alert(escape(obj_targ.value).split('%0A').length);
	//var s=obj_targ.innerHTML.split("\r\n");
	//alert(s.length);
	
	// NEEDS WORK: dynamically adjust number of TA lines
	if (obj=='ITS_QUESTION'){
		 var nRows = 5;
	} else {
		 var nRows = 1;
	}
	obj_targ.innerHTML = '<textarea id=`TXA_'+obj+'_TARGET` width=`100%` cols=`80%` rows=`'+nRows+'`>'+obj_targ.innerHTML+'</textarea>'
	obj_ctrl.innerHTML = '<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("'+NAV_TXT.value+'","'+obj+'_TARGET","CANCEL")`>Cancel</a><b> ]</b>&nbsp;<b>[ </b> <a href=`#` onclick=`ITS_QCONTROL("'+NAV_TXT.value+'","'+obj+'_TARGET","SAVE")`>Save</a><b> ]</b>'
}
//-------------------------------------------------//
function ITS_BROWSE_FILE(obj) {
//-------------------------------------------------//
var object = document.getElementById(obj);
object.innerHTML = '<textarea id=`TXA_'+obj+'_TARGET` width=`100%` cols=`80%` rows=`'+nRows+'`>'+obj_targ.innerHTML+'</textarea>'
//"<input type=file name=`new_class`>&nbsp;&nbsp;"
}
//-------------------------------------------------//
function ITS_form_submit(form,msg) { 
//-------------------------------------------------//
document.getElementById(form).submit();
document.getElementById('Students_Message').innerHTML=msg;
}
//-------------------------------------------------//
function ITS_submit(form) {
//-------------------------------------------------//
alert("in ITS_submit");
alert(form);

   if(form.options.selectedIndex == 0){
      alert('Please choose an option');
      return false;
   }
   else{
      c = confirm('You chose ' + sel.options[sel.selectedIndex].value + '\nDo you want to continue?');
      
      if(c){ sel.form.submit(); } else { sel.selectedIndex = 0; }
   }
	 
//document.getElementById("add_class_Form").submit();
//document.location="students.php";
//document.forms["form_add_class"].reset();
}
//-------------------------------------------------//
function Admin_Controls(obj) {
//-------------------------------------------------//
//var frame_admin = parent.frames['Questions_frame'].document;
var q_button = obj.document.getElementById("newQuestion");
//var preview_sp = obj.document.getElementById("admin_control_1_1_3");

switch(obj.value) {
 //------------//
 case 'New':
 //------------//
 q_button.value = 'Cancel';
 obj.document.getElementById("admin_control_1_1_1").style.visibility = 'hidden';
 obj.document.getElementById("admin_control_2_1_1").style.visibility = 'hidden';
 obj.document.getElementById("admin_control_2_1_2").style.visibility = 'hidden';
 obj.document.getElementById("admin_control_2_1_4").style.visibility = 'hidden';
 obj.document.getElementById("admin_control_3_1_1").style.visibility = 'visible';
 obj.document.getElementById("admin_control_3_1_1").innerHTML='<input type=button onclick=Admin_Controls(this) name=previewQuestion value=Preview >';
 obj.form.action = 'Question_New.php';
 break;
 //------------//    
 case 'Cancel':
 //------------//
 q_button.value = 'New';
 obj.document.getElementById("admin_control_1_1_1").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_1").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_2").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_3").innerHTML='';
 obj.document.getElementById("admin_control_2_1_4").style.visibility = 'visible';
 obj.document.getElementById("admin_control_3_1_1").innerHTML='';
 obj.form.action = 'Question.php';
 break; 
 //------------//
 case 'Preview':
 //------------//
 var QN_form = parent.frames['Question_frame'].document.getElementById('Question_New_form');
 QN_form.submit();
 QN_form = 'Question_Save.php';
 obj.value = 'Save';
 break;
 //------------//    
 case 'Save':
 //------------//
 var QS_form = parent.frames['Question_frame'].document.getElementById('Question_Save_form');
 QS_form.submit();
 obj.document.getElementById("admin_control_1_1_1").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_1").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_2").style.visibility = 'visible';
 obj.document.getElementById("admin_control_2_1_3").innerHTML='';
 obj.document.getElementById("admin_control_2_1_4").style.visibility = 'visible';
 obj.document.getElementById("admin_control_3_1_1").style.visibility = 'hidden';
 obj.document.getElementById("admin_control_4_1_1").children[0].value = 'New';
 obj.document.getElementById("admin_control_3_1_1").innerHTML='';
 //obj.value = 'Saved';
 //var QSd_form = parent.frames['Question_frame'].document.getElementById('Question_Saved_form');
 //QSd_form.submit();
 break;
}
}
//-------------------------------------------------//
function testing() {
//-------------------------------------------------//
alert('in testing');
}
//-------------------------------------------------//

/*
 var spans = document.getElementsByTagName('span');
 if (spans) {
 		for (var i = 0; i < spans.length; i++) {
				if (spans[i].className == 'editMode') {
					 //spans[i].style.color = 'Red';
					 //spans[i].innerHTML=xmlHttp.responseText;
					 spans[i].innerHTML='<b>[ </b> <a href=`?` style=`align: right;` onclick=`changeColor()`>Edit</a><b> ]</b>';
				}
		}
 }
//alert("Href of this a element is : " + anchorTags[i].innerHTML + "\n");
//setHandler(eds[i],'onclick','document.location=this.getElementsByTagName("hello")[0].href');
*/
