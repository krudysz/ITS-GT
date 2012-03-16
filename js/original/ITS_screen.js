/*
ITS - main JavaScript function file for JS only functions

Author(s): Greg Krudysz
Date: Mar-17-2010
*/

//obj.childNodes[1].style.display = 'block';
//-------------------------------------------------//
function ITS_menu(obj){
//-------------------------------------------------//
 var str = '<img src="phpimg/ITS_stem.php?t=s&d=1,1,2,1&title=y[n]" class="ITS_pzplot" onClick="ITS_plot_reset(this.parentNode)">';
	
 creatediv(5,str,200,200,100,100);
 creatediv(5,str,200,200,300,100);
 creatediv(5,str,200,200,100,500);
 creatediv(5,str,200,200,300,500);
}
//-------------------------------------------------//
function creatediv(id, html, width, height, left, top) { 
//-------------------------------------------------//
//alert('creatediv');
   var newdiv = document.createElement('div'); 
   newdiv.setAttribute('id', id);

   //if (width) { newdiv.style.width = 200;}
   //if (height){ newdiv.style.height = 200;}
   
   if ((left || top) || (left && top)) {
	     newdiv.style.className = "ITS_popImage";
       newdiv.style.position = "absolute";
			 newdiv.style.left = left; 
       newdiv.style.top = 0;
			 newdiv.style.margin   = "50px 0 0 100px";
			 //alert(newdiv.style.top);
       //if (left) { newdiv.style.left = left; }  
       //if (top)  { newdiv.style.top = top;   }
   }

   newdiv.style.background = "#fff";
   newdiv.style.border = "2px solid #666";
   
   if (html) { newdiv.innerHTML = html;      }
	 else      { newdiv.innerHTML = "nothing"; }
 
   document.body.appendChild(newdiv);

	 return false;
}
//-------------------------------------------------//
function ITS_plot(obj){
//-------------------------------------------------//
  var str = '<img src="phpimg/ITS_stem.php?t=s&d=1,1,2,1&title=y[n]" class="ITS_pzplot" onClick="ITS_plot_reset(this.parentNode)">';
	obj.innerHTML = str;
	
  //var helloWorld = new ajaxObject('helloContainer','showtext.php');
	return false;
}
//-------------------------------------------------//
function ITS_showImage(obj){
//-------------------------------------------------//
  var im = '<img src="'+obj.src+'" style="float:left;height:120px;cursor: pointer;" onclick="ITS_killImage(this);">';

  var b=creatediv('ITS_viewImage',im,200,200,200,200);
	return false;
}
//-------------------------------------------------//
function ITS_killImage(obj){
//-------------------------------------------------//
  obj.parentNode.removeChild(obj);
	return false;
}
//-------------------------------------------------//
function ITS_plot_reset(obj){
//-------------------------------------------------//
	obj.innerHTML = '<div class="ITS_plot" onClick="ITS_plot(this)">plot</div>';
	return false;
}
//-------------------------------------------------//
function ITS_changeConcepts(non_obj){
//-------------------------------------------------//
// update lab header
// selected object
sel_obj = document.getElementById(non_obj.name);

var selectedClass = sel_obj.className;
var noneClass     = non_obj.className;

sel_obj.id          = '';
sel_obj.className   = noneClass;
sel_obj.name        = non_obj.name;

non_obj.id          = non_obj.name;
non_obj.className   = selectedClass;
non_obj.name        = '';

// update events
sel_obj.onclick     = non_obj.onclick;
non_obj.onclick     = '';

ITS_AJAX('ITS_screen_AJAX.php','updateConcept',non_obj.innerHTML,ITS_screen,'contentContainer');
}
//-------------------------------------------------//
function ITS_content_select(non_obj){
//-------------------------------------------------//
/** -- timer -- **//*
var date1 = new Date();
var milliseconds1 = date1.getTime();
/** -- timer -- **/
var non_obj_name = non_obj.attributes.getNamedItem("name").value;
var arr = new Array();
arr = document.getElementsByName(non_obj_name);

for(var i = 0; i < arr.length; i++) {
  var obj = document.getElementsByName(non_obj_name).item(i);
  obj.className = '';
  obj.onclick   = new Function('ITS_content_select(this)');
}
non_obj.className = 'active';
non_obj.onclick   = '';

var class_info = non_obj_name.split("_");
var class_fcn  = class_info[0];
var class_prop = class_info[1];


/** -- timer -- **//*
var date2 = new Date();
var milliseconds2 = date2.getTime();

var difference = milliseconds2 - milliseconds1;
alert(difference);
/** -- timer -- **/
//alert(class_fcn+' - '+class_prop);
ITS_AJAX('ITS_screen_AJAX.php',class_fcn,class_prop+','+non_obj.innerHTML,ITS_screen,'contentContainer'); 

return false;
}
//-------------------------------------------------//
function ITS_content_select2(non_obj){
//-------------------------------------------------//
var non_obj_name = non_obj.attributes.getNamedItem("name").value;
var arr = new Array();
arr = document.getElementsByName(non_obj_name);

//alert(non_obj_name);

for(var i = 0; i < arr.length; i++) {
  var obj = document.getElementsByName(non_obj_name).item(i);
  obj.className = 'CHOICE_ACTIVE';
  obj.onclick   = new Function('ITS_content_select2(this)');
}
non_obj.className = 'CHOICE_SELECTED';
non_obj.onclick   = '';

//*****************************************//
//alert(non_obj.qid+' '+non_obj.n);

var class_info = non_obj_name.split("_");
var class_fcn  = class_info[0];
var class_prop = class_info[1];

var qid = non_obj.attributes.getNamedItem("qid").value;
var qn = non_obj.attributes.getNamedItem("n").value;

var ans = String.fromCharCode(64+parseInt(qn));  
sessionStorage.setItem('ITS_LAB_'+qid,ans);
//alert('ANSWER: '+'ITS_LAB_'+qid,ans);

/*
//--- enable tagContainer ---//
var tag_obj = document.getElementById('tagContainer');
tag_obj.style.display = 'block';

//--- enable ratingContainer ---//
var tag_obj = document.getElementById('ratingContainer');
tag_obj.style.display = 'block';
//---------------------------//
*/
//alert("Question "+qid+" is: " + sessionStorage.getItem('ITS_LAB_'+qid));     // accessing it
//alert("Hello " + sessionStorage.fullname);                        // another way of accessing the variable
//sessionStorage.removeItem('fullname');                            // finally unset it 


//alert(data.attributes.getNamedItem("runat").value)
/** -- timer -- **//*
var date2 = new Date();
var milliseconds2 = date2.getTime();

var difference = milliseconds2 - milliseconds1;
alert(difference);
/** -- timer -- **/
//alert(class_fcn+' - '+class_prop);
//ITS_AJAX('ITS_screen_AJAX.php',class_fcn,class_prop+','+non_obj.innerHTML,ITS_screen,'contentContainer'); 

return false;
}
//-------------------------------------------------//
function ITS_content_nav(obj,nav){
//-------------------------------------------------//
var obj_name = obj.attributes.getNamedItem("name").value;
var class_info = obj_name.split("_");
var class_fcn  = class_info[0];
var class_prop = class_info[1];
ITS_AJAX('ITS_screen_AJAX.php',class_fcn,class_prop+','+nav,ITS_screen,'contentContainer');
}
//-------------------------------------------------//
function ITS_Q(screen){
//-------------------------------------------------//
 //alert('Under construction ...');

 ITS_AJAX('ITS_screen_AJAX.php','updateHeader',screen,ITS_screen,'headerContainer');
 str = "ITS_AJAX('ITS_screen_AJAX.php','getContent',"+screen+",ITS_screen,'contentContainer');"
 setTimeout(str,250); 
	
 return false;
}
//-------------------------------------------------//
function ITS_MSG(flag){
//-------------------------------------------------//
var mC_display;
var mI_display;

if (flag) {mC_display = 'block';
	 				 mI_display = 'none'  }
else      {mC_display = 'none';
					 mI_display = 'block'}

var ta_obj = document.getElementById('messageContainer');
ta_obj.style.display = mC_display;

var mi_obj = document.getElementById('messageIcon');
mi_obj.style.display = mI_display;
}
//-------------------------------------------------//
function NAV_MOUSE(obj,button){
//-------------------------------------------------//
 obj.src = 'phpimg/ITS_button.php?o='+button;
}
//-------------------------------------------------//
function ITS_lab_submit(obj,qid,qtype){
//-------------------------------------------------//
//alert(qid+' - '+qtype);
var chosen = '';
switch (qtype.toUpperCase()) {
 //----------//
 case 'P':
 //----------//
  var x=document.getElementsByName('ITS_'+qid);
  chosen = x[0].value;
 break;
 //----------//
 case 'M':
 //----------//
  var values = new Array();
  var x=document.getElementsByName('ITS_'+qid);				
  for (i=0; i<x.length; i++) {
	  //alert(x[i].value);
		values[i] = x[i].value;
    //if (x[i].value) { chosen[i] = x[i].value; }	
  }
	chosen = values.join();
	break;
 //----------//
 case 'MC':
 //----------//  
 /*
  var x=document.getElementsByName('ITS_'+qid);				
  for (i=0; i<x.length; i++) {
    if (x[i].checked) { chosen = x[i].value; }
  }*/
	chosen = sessionStorage.getItem('ITS_LAB_'+qid);
	//alert('SUBMIT: '+'ITS_LAB_'+qid);
  break;
	//----------//
}	
//alert(chosen);
if (chosen){
/*
  for (i=0; i<x.length; i++) {
    var y=document.getElementById('TextAlphabet'+x[i].value);
	  if (x[i].checked) { // chosen
	     chosen = x[i].value;
		   x[i].style.color = "red";
		   //y.style.color = "blue";
    }else{
		   y.style.color = "silver";
    }
	}
	*/
	//alert(chosen);
	//alert(qid);
	ITS_AJAX('ITS_screen_AJAX.php','recordAnswer',qid+'~'+qtype+'~'+chosen,ITS_screen,'contentContainer');
} else {
  ITS_AJAX('ITS_screen_AJAX.php','message','Select an answer.',ITS_screen,'errorContainer');
}
}
//-------------------------------------------------//
function ITS_submit(obj,qid){
//-------------------------------------------------//
var x=document.getElementsByName('ITS_'+qid)				
var chosen = '';

for (i=0; i<x.length; i++) {
  if (x[i].checked) { chosen = x[i].value; }
} 

if (chosen){
  for (i=0; i<x.length; i++) {
    var y=document.getElementById('TextAlphabet'+x[i].value);
	  if (x[i].checked) { // chosen
	     chosen = x[i].value;
		   x[i].style.color = "red";
		   //y.style.color = "blue";
    }else{
		   y.style.color = "silver";
    }
	}
	ITS_AJAX('ITS_screen_AJAX.php','getAnswer',qid+','+chosen,ITS_screen,'answerContainer');
} else {
  ITS_AJAX('ITS_screen_AJAX.php','message','Select an answer.',ITS_screen,'errorContainer');
}
}
//-------------------------------------------------//
function ITS_screen(obj,txt){
//-------------------------------------------------//
	   var ta_obj = document.getElementById(obj);
		 ta_obj.innerHTML = txt;
		  /*
		 switch (obj) {
 		 	 //----------//
 		   case 'headerContainer':
 		   //----------//
			 ITS_AJAX('ITS_screen.php','showExercises',1,ITS_screen,'contentContainer');
		 }
		 */
		 /*
	   var footer_div = document.createElement('div');
     footer_div.setAttribute('id','ITS_footer'+obj.innerHTML);	 
		 
		 //var oInput = document.createElement('textarea');
		 //oInput.innerHTML = "HELLO";
		 //footer_div.appendChild(oInput);
		 
		 footer_div.style.position   = "absolute";
     footer_div.style.width      = 0.6*dim.width;
     footer_div.style.height     = "auto";  
     footer_div.style.left       = 0.15*dim.width; //obj.offsetLeft;
     footer_div.style.background = "#FEFEFE";
     footer_div.style.border     = "3px solid #FFCC00";
	   footer_div.style.display    = "block";
		 footer_div.style.padding    = "10px";
     footer_div.innerHTML        = "<sup>"+obj.innerHTML+"</sup> "+str;
     document.body.appendChild(footer_div);	
		 footer_div.style.top        = obj.offsetTop-footer_div.offsetHeight;
	 } else {
	 
	   var footer_div = document.getElementById('ITS_footer'+obj.innerHTML);
		 
		 if (footer_div) {
		 //alert('true');
		   document.body.removeChild(footer_div);
		   //footer.style.display = 'none'; 		 footer_div.style.text-align = "center";
		 }//else{alert('false');}
		 	 */
}
//-------------------------------------------------//