    <!-- CENTER DIV CONTENT -->
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <title>Universal vertical center with CSS</title>
  <style>
	#ANSWER  {
    margin: 0; 
    padding: 0;
    text-align: center;
		border: 1px solid red;
}
#ANSWER #container { 
    width: 200px; /*SET your width here*/
    margin: 0 auto;
    text-align: left;
		border: 1px solid purple;
} 
    .greenBorder {border: 1px solid green;} /* just borders to see it */
  </style>
</head>

<body>
<div id="ANSWER">
<div id="container">type your content here</div>
</div>
  <div class="greenBorder" style="display: table; height: 400px; #position: relative; overflow: hidden;">
    <div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle;">
      <div class="greenBorder" style=" #position: relative; #top: -50%">
        any text<br>
        any height<br>
        any content, for example generated from DB<br>
        everything is vertically centered
      </div>
    </div>
  </div>
</body>
</html>
