// Suche nach Vereinen in der DB
function liveSearch(str_input) {
	$("#waitLiveSearch").show();
	if (str_input.length == 0) {
		// TODO: mit jQuery schreiben
		$('#placeholderLiveSearch').html("");
		$('#placeholderLiveSearch').attr("style", "border: 0px");
		$("#waitLiveSearch").hide();
		return;
	}
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$("#waitLiveSearch").hide();
			$('#placeholderLiveSearch').html(xmlhttp.responseText);
			$('#placeholderLiveSearch').attr("style", "border: 1px solid #A5ACB2");
		}
	}
	xmlhttp.open("GET", "functions/liveSearch.php?q=" + str_input, true);
	xmlhttp.send();
}

// Neues Fenster öffnen
function openNewWindow(str_ClubName) {
	var width = 1200;
	var height = 900;
	var left = (screen.width/2)-(width/2);
	var top = (screen.height/2)-(height/2);
//	window.open('http://myt_neu.localhost/' + str_ClubName,str_ClubName,'width='+width+',height='+height+',left='+left+',menubar=yes,location=no');
	window.open('http://www.manageyourteam.de/' + str_ClubName,str_ClubName,'width='+width+',height='+height+',left='+left+',menubar=yes,location=no');
}