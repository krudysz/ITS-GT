<!--
var set=false;
var v=0;
var a="0";
var cookie_date;
function loadStars()
{
   star1 = new Image();
   star1.src = "icons/star1.gif";
   star2 = new Image();
   star2.src= "icons/star2.gif";
}

function highlight(x) {
   star1 = new Image();
   star1.src = "rating/icons/star1.gif";
   star2 = new Image();
   star2.src= "rating/icons/star2.gif";
	 
   if (set==false)
   {
      y=x*1+1
      switch(x)
      {
         case "1":
            document.getElementById(x).src= star2.src;
            document.getElementById('vote').innerHTML="Very Easy";
            break;
         case "2":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Easy";
            break;
         case "3":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Moderate";
            break;
         case "4":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Hard";
            break;
         case "5":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Very Hard";
            break;
      }
   }
}

function losehighlight(x)
{
   if (set==false)
   {
      for (i=1;i<6;i++)
      {
         document.getElementById(i).src=star1.src;
         document.getElementById('vote').innerHTML="";
      }
   }
}

function setStar(x)
{
   y=x*1+1
   if (set==false)
   {
      switch(x)
      {
         case "1":
            a="1"; 
            flash(a);
            break;
         case "2":
            a="2"; 
            flash(a);
            break;
         case "3":
            a="3";
            flash(a);
            break;
         case "4":
            a="4"; 
            flash(a);
            break;
         case "5": 
            a="5"; 
            flash(a);
            break;
      }
      set=true; 
     // //send variable a to php  
     location.href="index.php?rating="+ a;
     // document.write(a);  	
      //document.getElementById('vote').innerHTML="Thank you for your vote! You can vote again in a few seconds"
      //send variable a to php or a cookie  
           
     // write_cookie("rating",a);
     //location.href="$filename?rating="+ a;
      setTimeout(remove_highlight,2000);   
      setTimeout(delete_cookie("rating"));  
   } 
}

function remove_highlight()
{
	if(set=true)
	{
		set=false;
	    a="0";
	    v=0;
      for (i=1;i<6;i++)
      {	     
         document.getElementById(i).src=star1.src;
         document.getElementById('vote').innerHTML=""
      }
  	}
	
	
}

function flash()
{
   y=a*1+1
   switch(v)
   {
      case 0:
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star1.src;
         }
         v=1
         setTimeout(flash,200)
         break;
      case 1: 
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star2.src;
         }
         v=2
         setTimeout(flash,200)
         break;
      case 2:
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star1.src;
         }
         v=3
         setTimeout(flash,200)
         break;
      case 3:
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star2.src;
         }
         v=4
         setTimeout(flash,200)
         break;
      case 4:
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star1.src;
         }
         v=5
         setTimeout(flash,200)
         break;
      case 5:
         for (i=1;i<y;i++) 
         {
            document.getElementById(i).src= star2.src;
         }
         v=6
         setTimeout(flash,200)
         break;
   }
}

function write_cookie(name,value)
{	
	document.cookie=name+"="+value; 
}

function set_cookie ( name, value  )
{
 cookie_string = name + "=" + escape ( value ); 
  document.cookie = cookie_string;
}

function delete_cookie ( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}
-->