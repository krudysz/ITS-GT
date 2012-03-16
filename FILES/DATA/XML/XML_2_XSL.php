<html>
<head>
<link rel="stylesheet" href="css/XML.css">
<script>
//---------------------------------------------------------------//
function loadXMLDoc(dname) {
//---------------------------------------------------------------//
  if   (window.XMLHttpRequest) { xhttp=new XMLHttpRequest(); }
  else                         { xhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
  
  xhttp.open("GET",dname,false);
  xhttp.send("");
	
return xhttp.responseXML;
}
//---------------------------------------------------------------//
function displayResult(table) {
//---------------------------------------------------------------//
  xml = loadXMLDoc(table+".xml");
  xsl = loadXMLDoc(table+".xsl");
	
  // code for IE
  if (window.ActiveXObject) {
    ex=xml.transformNode(xsl);
    document.getElementById("example").innerHTML=ex;
  }
  // code for Mozilla, Firefox, Opera, etc.
  else if (document.implementation && document.implementation.createDocument) {
    xsltProcessor=new XSLTProcessor();
    xsltProcessor.importStylesheet(xsl);
    resultDocument = xsltProcessor.transformToFragment(xml,document);
    document.getElementById("example").appendChild(resultDocument);
  }
}
//---------------------------------------------------------------//
</script>
</head>
<?php 
$table = $_GET["table"];
$onload = 'onload="displayResult(\''.$table.'\')"';
?> 
<body <?php  echo $onload;?>>
<div id="example"/>
</body>
</html>