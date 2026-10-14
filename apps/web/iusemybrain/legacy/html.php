<?php $title="Meine erste html-Seite"; include 'geheim.php'; include 'header.php';?>
<script type="text/javascript">
	var aktId = '1';
	function load(id){
		$("#warten").show();
		//alert("aktuelle Seite: html" + aktId + ".php");
		id = parseInt(id);
		aktId = parseInt(aktId);
		// alteId nicht mehr aktiv
		document.getElementById(aktId).removeAttribute('class');
		// disabled Felder entfernen
		document.getElementById('prev').removeAttribute('class');
		document.getElementById('next').removeAttribute('class');
        if (id == 0){
            if(aktId == 1){
                id = 1;
            }
            else{
                id = aktId-1;
            }
        }
        else if (id == 6){
        	if(aktId == 5){
                id = 5;
            }
            else{
                id = aktId+1;
            }
        }
		if (id == 1){
			// prev disabled
			document.getElementById('prev').setAttribute('class', 'disabled');
		}
		else if (id == 5){
			// prev disabled
			document.getElementById('next').setAttribute('class', 'disabled');
		}
        aktId = id;
        // id aktiv
        document.getElementById(id).setAttribute('class', 'active');
        //alert("aufgerufene Seite: html" + id + ".php");
        $("#inhalt").load("tutorials/html/html" + id + ".php");
        $("#warten").hide();
	}
/*
    	$("#logWarten").show();
    	inputName = $('#loginName').val();
    	inputPasswort = $('#loginPasswort').val();
    	art = "login";
           $.ajax({
        	   		type: "POST",
        	   		url: "validate.php",
        	   		cache: false,
        	   		data: { nickname: inputName, passwort: inputPasswort, art: art},
        	   		success: function(data){
        	   		$("#logWarten").hide();
                    if(data=='nichtAlleWerte'){
                    	alert(unescape("Bitte));
*/
</script>
<br>
<div align="center">
	<img style="display: none;" id="warten" src="img/warten.gif"/>
</div>
<div class="pagination pagination-centered pagination-large">
	<ul class="pointer">
		<li id="prev" class="disabled"><a onclick="load('0');">«</a></li>
		<li id="1" class="active"><a onclick="load('1');">1</a></li>
		<li id="2" ><a onclick="load('2');">2</a></li>
		<li id="3" ><a onclick="load('3');">3</a></li>
		<li id="4" ><a onclick="load('4');">4</a></li>
		<li id="5" ><a onclick="load('5');">5</a></li>
		<li id="next"><a onclick="load('6');">»</a></li>
	</ul>
</div>
<br>
<div align="center" class="inhalt" id="inhalt">
</div>

<script type="text/javascript">
	$("#inhalt").load("tutorials/html/html1.php");
</script>

<?php include 'footer.php';?>