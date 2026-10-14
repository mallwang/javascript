$(document).ready(function(){
	// Gehaltserhoehung wurde geklickt
	$('form.gehaltserhoehung').submit(function(){
		iElemA = $("input[name='id']", this).val();
		iElemB = $("input[name='name']", this).val();
		iElemC = $("input[name='gehalt']", this).val();
		iElemD = $("input[name='grundwert']", this).val();
		var Eingabe = confirm(unescape("M%F6chten Sie " + iElemB + " wirklich ein neues Gehalt in H%F6he von " + iElemC + "%u20AC bieten?"));
		if (Eingabe){
			$.ajax({
				type:	'POST',
				url:	'gehaltserhoehung.php',
				data:{id: iElemA, grundwert: iElemD, gehalt: iElemC},
				success: function(data){
					alert("inAjax");
					alert(data);
					if(data=='ok'){
						alert(unescape(iElemB + " freut sich %FCber ein h%F6heres Gehalt!"));
						document.location='arbeitnehmer.php';
					}
					else if(data=='keineZahl'){
						alert(unescape("Eingabe falsch! Bitte %FCberpr%FCfen Sie ihre Eingabe!"));
	                }
					else if(data=='gehaltZuKlein'){
						alert(unescape("Das Gehalt muss im positiven Bereich sein!"));
	                }
	                //alert(data);
				}
			});
		}
		else{
		}
		return false;
	});
	
	// Entlassung wurde geklickt
	$('a.entlassung').click(function(){
		iElemA = $("input[name='id']", this).val();
		iElemB = $("input[name='name']", this).val();
		iElemC = $("input[name='gehalt']", this).val();
		iElemD = $("input[name='grundwert']", this).val();
		
		var abfindung = iElemC*10;
		var Eingabe = confirm(unescape("M%F6chten Sie " + iElemB + " wirklich k%FCndigen? Dies w%FCrde " + abfindung + "%u20AC Abfindung kosten."));
		if (Eingabe){
			$.ajax({
				type:	'POST',
				url:	'entlassen.php',
				data:{id: iElemA, arbeitswert: iElemD, abfindung: abfindung},
				success: function(data){
					//alert("inAjax");
//					alert(data);
					if(data=='ok'){
						alert(unescape(iElemB + " wurde entlassen!"));
						document.location='arbeitnehmer.php';
					}
					else if(data=='nichtOk'){
						alert(unescape("Fehler! Bitte nochmal versuchen"));
	                }
					else if(data=='noBargeld'){
						alert(unescape("Sie haben nicht genug Bargeld, um die Abfindung zu bezahlen!"));
	                }
	                //alert(data);
				}
			});
		}
		else{
		}
		return false;
	});
});