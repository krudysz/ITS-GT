/*
ITS - main JavaScript function file for JS only functions

Author(s): Greg Krudysz
Date: Feb-19-2009
*/
//-------------------------------------------------//
function footerIMG(obj,str,flag){
//-------------------------------------------------//
	 if (flag) { 
	 
     var dim = screen_size();
	 
	   var footer_div = document.createElement('div');
     footer_div.setAttribute('id','ITS_footerIMG'+obj.innerHTML);	 
		 
		 footer_div.style.position   = "absolute";
     footer_div.style.width      = "auto"; //0.1*dim.width;
     footer_div.style.height     = "auto";  
     footer_div.style.left       = 0.5*dim.width; //obj.offsetLeft;
     footer_div.style.background = "gainsboro";
     footer_div.style.border     = "3px solid #FFCC00";
	   footer_div.style.display    = "block";

		 footer_div.style.textAlign  = "right";
     footer_div.innerHTML        = str;
     document.body.appendChild(footer_div);	
		 footer_div.style.top        = obj.offsetTop+10;
		 
		 document.body.style.cursor = 'pointer';
	 } else {
	 
	   var footer_div = document.getElementById('ITS_footerIMG'+obj.innerHTML);
		 
		 if (footer_div) {
		 //alert('true');
		   document.body.removeChild(footer_div);
			 document.body.style.cursor = 'default';
		   //footer.style.display = 'none'; 		 footer_div.style.text-align = "center";
		 }//else{alert('false');}
	 }	
}
//-------------------------------------------------//
function footer(obj,str,flag){
//-------------------------------------------------//
	 if (1) { 
	 
     var dim = screen_size();
		 
	 
	   var footer_div = document.createElement('div');
     footer_div.setAttribute('id','ITS_footer'+obj.innerHTML);	 
		 
		 //var oInput = document.createElement('textarea');
		 //oInput.innerHTML = "HELLO";
		 //footer_div.appendChild(oInput);
		 
		 footer_div.style.position   = "absolute";
     footer_div.style.width      = 0.1*dim.width;
     footer_div.style.height     = "auto";  
     footer_div.style.left       = 0.5*dim.width; //obj.offsetLeft;
     footer_div.style.background = "#FEFEFE";
     footer_div.style.border     = "3px solid #FFCC00";
	   footer_div.style.display    = "block";
		 footer_div.style.padding    = "0px";
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
	 }	
}
//-------------------------------------------------//
function screen_size(){
//-------------------------------------------------//
  // screen size
	var dim = {width:0, height:0};

  if (parseInt(navigator.appVersion)>3) {
	//alert(navigator.appName.indexOf("Microsoft"));
    if (navigator.appName=="Netscape") {
      dim.width  = window.innerWidth;
      dim.height = window.innerHeight;
    }
    if (navigator.appName.indexOf("Microsoft")!=-1) {
      dim.width  = document.body.offsetWidth;
      dim.height = document.body.offsetHeight;
    }
  }
  return dim;
}
/*
//-------------------------------------------------//
function footer(obj,str,flag){
//-------------------------------------------------//
	 if (flag) { 
	 
     var dim = screen_size();
		 //alert(dim.width+" , "+dim.height);
	 
	   var footer_div = document.createElement('div');
     footer_div.setAttribute('id','ITS_footer'+obj.innerHTML);
		 footer_div.setAttribute('class','ITS_footer');
		 footer_div.createElement('input');
		 footer_div.style.position   = "absolute";
     footer_div.style.width      = 0.6*dim.width;
     footer_div.style.height     = 100;  
     footer_div.style.left       = 0.15*dim.width; //obj.offsetLeft;
     footer_div.style.top        = obj.offsetTop-160;
     footer_div.style.background = "#EFEFEF";
     footer_div.style.border     = "3px solid #FFCC00";
	   footer_div.style.display    = "block";
		 footer_div.style.padding    = "10px";
     footer_div.innerHTML        = "<sup>"+obj.innerHTML+"</sup> "+str;
	
     document.body.appendChild(footer_div);	
	 } else {
	 
	   var footer_div = document.getElementById('ITS_footer'+obj.innerHTML);
		 
		 if (footer_div) {
		 //alert('true');
		   document.body.removeChild(footer_div);
		   //footer.style.display = 'none'; 		 footer_div.style.text-align = "center";
		 }//else{alert('false');}
	 }	
}
function getPosition(e) {
    e = e || window.event;
    var cursor = {x:0, y:0};
    if (e.pageX || e.pageY) {
        cursor.x = e.pageX;
        cursor.y = e.pageY;
    } 
    else {
        var de = document.documentElement;
        var b = document.body;
        cursor.x = e.clientX + 
            (de.scrollLeft || b.scrollLeft) - (de.clientLeft || 0);
        cursor.y = e.clientY + 
            (de.scrollTop || b.scrollTop) - (de.clientTop || 0);
    }
    return cursor;
}
*/
