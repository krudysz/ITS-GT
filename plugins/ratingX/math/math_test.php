<?php
//include
include_once("math1.php");

?>


<HEAD>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=1,statusbar=1,menubar=1,resizable=1,width=720,height=600,left = 280,top = 100');");
}
// End -->
</script>

<A HREF="javascript:popUp('../graphs/draw_graph.php?id=3&greg=12')">Open the Popup Window</A>

<head> Difficulty Rating System </head>

<body onload="loadStars()">
<img src="../icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="1" style="width:30px; height:30px; float:left;" />
<img src="../icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="2" style="width:30px; height:30px; float:left;" />
<img src="../icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="3" style="width:30px; height:30px; float:left;" />
<img src="../icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="4" style="width:30px; height:30px; float:left;" />
<img src="../icons/star1.gif" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" id="5" style="width:30px; height:30px; float:left;" /><br /><br />
<div id="vote" style="font-family:tahoma; color:red;"></div>

<div id="current rating">
Current Rating:
onload="loadStars()"

</div>
</body>


<script language="Javascript">
<!--
var set=false;
var v=0;
var a;
function loadStars()
{
   star1 = new Image();
   star1.src = "../icons/star1.gif";
   star2 = new Image();
   star2.src= "../icons/star2.gif";
}

function highlight(x)
{
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
            document.getElementById('vote').innerHTML="Easy"
            break;
         case "3":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Moderate"
            break;
         case "4":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Hard"
            break;
         case "5":
            for (i=1;i<y;i++)
            {
               document.getElementById(i).src= star2.src;
            }
            document.getElementById('vote').innerHTML="Very Hard"
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
         document.getElementById('vote').innerHTML=""
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
            a="1" 
            flash(a);
            break;
         case "2":
            a="2" 
            flash(a);
            break;
         case "3":
            a="3" 
            flash(a);
            break;
         case "4":
            a="4" 
            flash(a);
            break;
         case "5": 
            a="5" 
            flash(a);
            break;
      }
      set=true;
      document.getElementById('vote').innerHTML="Thank you for your vote!"
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
-->
</script>