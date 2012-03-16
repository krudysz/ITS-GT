/*===
JavaScript functions
===*/
//-------------------------------------------------//
function JS_fcn1(obj){
//-------------------------------------------------//
alert('in ... JS_fcn1():\n\n send to AJAX_server.php via AJAX');

//usage: Javascript: ITS_AJAX('my_file.php',array('arg1','arg2'),'other data',JS_FUNCTION,OBJ);
ITS_AJAX('AJAX_server.php','myDiv','my data',JS_fcn2,myDiv);
}
//-------------------------------------------------//
function JS_fcn2(obj,str){
//-------------------------------------------------//
alert('in ... JS_fcn2(): \n\n update "myDiv" with string: '+str);

obj.innerHTML = str;
obj.style.color = "red";  // change text color to red
}
//-------------------------------------------------//


