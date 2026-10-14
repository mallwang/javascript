$(document).ready(function(){
	$('form.checkme').submit(function(){
		iElemA = $("input[name='id']", this).val();
		iElemB = $("input[name='gehalt']", this).val();
		var Eingabe = confirm(unescape("M%F6chten Sie dieses Gebot in H%F6he von " + iElemB + "%u20AC wirklich abgeben?"));
		if(Eingabe){
			//alert(iElemA);
			//alert(iElemB);
			$.ajax({
				type:	'POST',
				url:	'verhandeln.php',
				data:{id:iElemA, gehalt:iElemB},
				success: function(data){
					//alert("inAjax");
					//alert(data);
					if(data=='gebotGroesser'){
						alert(unescape("Sie haben bereits ein h%F6heres Angebot abgegeben!"));
						$('.angebotUebermittelt', this).hide();
						$('.bereitsGeboten', this).show();
					}
					else if(data=='gehaltZuKlein'){
						alert(unescape("Ihr Angebot muss gr%F6%DFer als das aktuelle Gehalt sein"));
	                }
	                else if(data=='nichtGueltig'){
						alert(unescape("Eingabe falsch! Bitte %FCberpr%FCfen Sie ihre Eingabe!"));
	                }
					else if(data=='ok'){
						alert(unescape("Ihr Angebot wurde %FCbermittelt"));
						$('.angebotUebermittelt', this).show();
						$('.bereitsGeboten', this).hide();
						document.location='arbeitsmarkt.php';
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