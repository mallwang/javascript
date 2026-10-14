var xmlHttp;

function suggest(suchbegriff) {
xmlHttp=httpXMLobjects();
if (xmlHttp==null) {
  alert ("Browser does not support AJAX");
  return;
}
if (suchbegriff.length==0) { 
  document.getElementById("ausgabe").innerHTML="";
  return;
}
else {
	//URL vorbereiten, Zufallszahl umgeht den Browsercache  
	var aufruf="suche.php"+"?q="+suchbegriff+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",aufruf,true);
	xmlHttp.send(null);
	}
} 
function stateChanged() { 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
 document.getElementById("ausgabe").innerHTML=xmlHttp.responseText;
 } 
}
//AJAX-Standards 
//Weniger interessant

function httpXMLobjects() {
var xmlHttp=null;
try {
 // Fuer Firefox, Opera und Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e) {
 // Der Internet Explorer wills wieder anders
 try {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e) {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

function add_user(b) {
 var inhalt_alt;
 var inhalt_neu;
 inhalt_alt = document.getElementById("e").value;
 if (inhalt_alt == ""){
  document.getElementById("e").value = b;
 }
 else{
 document.getElementById("e").value = inhalt_alt + ", " + b;
 }
}
