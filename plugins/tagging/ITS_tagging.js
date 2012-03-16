
//-------------------------------------------------//
function ITS_content_select3(non_obj){
//-------------------------------------------------//
var non_obj_name = non_obj.attributes.getNamedItem("name").value;
var arr = new Array();

arr = document.getElementsByName(non_obj_name);

for(var i = 0; i < arr.length; i++) {
  var obj = document.getElementsByName(non_obj_name).item(i);
  obj.className = 'TAG_ACTIVE';
  obj.onclick   = new Function('ITS_content_select3(this)');
}
non_obj.className = 'TAG_SELECTED';
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

//alert("Question "+qid+" is: " + sessionStorage.getItem('ITS_LAB_'+qid));     // accessing it
//alert("Hello " + sessionStorage.fullname);                        // another way of accessing the variable
//sessionStorage.removeItem('fullname');                            // finally unset it 

return false;
}
//-------------------------------------------------//
function submitTags(url,windowname) {
//-------------------------------------------------//
	tagwindow = window.open( url, windowname, "status = 1, height = 150, width = 350, resizable = 0" );
	tagwindow.moveTo(450,300);
}
//-------------------------------------------------//
function clearText(field) {
	if (field.defaultValue == field.value)  field.value = '';
}
//-------------------------------------------------//
function returnText(field) {
	if (field.value == '') field.value = field.defaultValue;
}
//-------------------------------------------------//
/*
 * Referred from http://sixrevisions.com/tutorials/javascript_tutorial/create_lightweight_javascript_tooltip/
 * */
var tooltip=function(){
	 var id = 'tt';
	 var top = 3;
	 var left = 3;
	 var maxw = 700;
	 var speed = 10;
	 var timer = 20;
	 var endalpha = 95;
	 var alpha = 0;
	 var tt,t,c,b,h;
	 var ie = document.all ? true : false;
	 return{
	  show:function(v,w){
	   if(tt == null){
	    tt = document.createElement('div');
	    tt.setAttribute('id',id);
	    t = document.createElement('div');
	    t.setAttribute('id',id + 'top');
	    c = document.createElement('div');
	    c.setAttribute('id',id + 'cont');
	    b = document.createElement('div');
	    b.setAttribute('id',id + 'bot');
	    tt.appendChild(t);
	    tt.appendChild(c);
	    tt.appendChild(b);
	    document.body.appendChild(tt);
	    tt.style.opacity = 0;
	    tt.style.filter = 'alpha(opacity=0)';
	    document.onmousemove = this.pos;
	   }
	   tt.style.display = 'block';
	   c.innerHTML = v;
	   tt.style.width = w ? w + 'px' : 'auto';
	   if(!w && ie){
	    t.style.display = 'none';
	    b.style.display = 'none';
	    tt.style.width = tt.offsetWidth;
	    t.style.display = 'block';
	    b.style.display = 'block';
	   }
	  if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
	  h = parseInt(tt.offsetHeight) + top;
	  clearInterval(tt.timer);
	  tt.timer = setInterval(function(){tooltip.fade(1)},timer);
	  },
	  pos:function(e){
	   var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
	   var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
	   tt.style.top = (u - h) + 'px';
	   tt.style.left = (l + left) + 'px';
	  },
	  fade:function(d){
	   var a = alpha;
	   if((a != endalpha && d == 1) || (a != 0 && d == -1)){
	    var i = speed;
	   if(endalpha - a < speed && d == 1){
	    i = endalpha - a;
	   }else if(alpha < speed && d == -1){
	     i = a;
	   }
	   alpha = a + (i * d);
	   tt.style.opacity = alpha * .01;
	   tt.style.filter = 'alpha(opacity=' + alpha + ')';
	  }else{
	    clearInterval(tt.timer);
	     if(d == -1){tt.style.display = 'none'}
	  }
	 },
	 hide:function(){
	  clearInterval(tt.timer);
	   tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
	  }
	 };
	}();
//-------------------------------------------------//