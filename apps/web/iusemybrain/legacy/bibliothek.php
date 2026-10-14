<?php $title="Bibliothek"; include 'header.php';?>

<link href="css/dataTables.min.css" rel="stylesheet" />
<script type="text/javascript" src="js/dataTables.min.js"></script>

<!-- ******************************************
 **** KOMPLETTE AUFTEILUNG IN ZWEI SPALTEN ****
 ****************************************** -->
<div align="center" class='ueber'>
	<br><br><br><br><br><br>

<!-- ******************************************
	 ***** SUCHFORMULAR UND ERGEBNISLISTE *****
	 ****************************************** -->
		<div class='teil1'>
		
		<!-- *************************************
	 	************  FORMULAR SUCHE *************
	 	************************************** -->
			<form id="neueintrag" class="bs-docs-example form-horizontal" style="display: ;">
			
			<!-- SUCHFELD -->
			<div class="control-group">
				<label class="control-label">Suche:</label>
				<div class="controls">
					<input type="text" id="suche" onkeyup="showBooks();" placeholder="Suche nach Titel, Autor, Jahr" /><br>
				</div>
			</div>
			
			<!-- KATEGORIE -->
			<div class="control-group">
				<label class="control-label">Kategorie:</label>
				<div class="controls">
					<select id="selectKategorie" onchange="showBooks()">
						<option value="ALL">Alle</option>
						<option value="IT">Informatik</option>
						<option value="SP">Sprachen</option>
						<option value="ER">Ern&auml;hrung</option>
						<option value="RO">Romane</option>
					</select>
				</div>
			</div>
			
			<!-- DATEIFORMAT -->
			<div class="control-group">
				<label class="control-label">Dateiformat:</label>
				<div class="controls">
					<select id="selectDateiformat" onchange="showBooks();">
						<option value="ALL">Alle</option>
						<option value="PD">PDF</option>
						<option value="EP">epub</option>
					</select>
				</div>
			</div>
			
			<!-- FEHLERMELDUNGEN & BUTTON -->
			<div class="control-group">
				<div class="controls">
					<form id="frmAbsenden1" class="form-horizontal">
					
						<!-- MELDUNGEN -->
						<div id="errorSenden1" style="display: none;" class="alert alert-error"></div>
						<div id="successSenden1" style="display: none;" class="alert alert-success"></div>
						
						<!-- WARTEGRAFIK -->
						<img style="display: none;" id="wait1" src="img/loading.gif" />
					</form>
				</div>
			</div>
		</form>
	</div>
	
	<!-- *******************************************
	 ***************  ERGEBNISTABELLE **************
	 ******************************************* -->
	<div class='teil2'>
		<table id="example" class="display" cellspacing="0" width="90%"></table>
		<!-- <div id="result" style="margin-left: 20px; position: absolute;"></div> -->
	</div>
</div>
	
<!-- JAVASCRIPT CODE -->
<script type="text/javascript">

	// FUNKTION ZUM ANZEIGEN DER ERGEBNISTABELLE
	function showBooks(){
	
		// Fehlermeldungen l�schen
		$('#errorSenden1').hide();
		$('#successSenden1').hide();
		
		// Wartezeichen anzeigen
		$("#wait1").show();
		
		// Alle Auswahlfelder abfragen
		var kategorie = $('#selectKategorie').val();	// Wert der selektierten Kategorie (z.B. "", "IT")
		var dateiformat = $('#selectDateiformat').val();	// Wert des selektierten Dateiformats (z.B. "", "PD")
		var suche = $('#suche').val();	// Wert des Suchfeldes

// 		alert("kat: " + kategorie + " dat: " + dateiformat + " suche: " + suche);
		
		// Wenn Suche nicht ausgef�llt ist, dann als Zeichen ein "*"
		if (suche == ""){
			suche = "*";
		}
		
		// Ajax-Aufruf
		$.ajax({
	   		type: "POST",
	   		url: "resultBibliothek.php",
	   		cache: false,
	   		data: { kategorie: kategorie, dateiformat: dateiformat, suche: suche},
	   		success: function(data){
// 		   		alert(data);
		   		// Wartezeichen verstecken
		   		$("#wait1").hide();
	        	$("#example").html(data);
		   	}
		});
	}
	showBooks();
	$('#example').dataTable( {
        "language": {
        	"sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
            "sInfo":            "_START_ bis _END_ von _TOTAL_ Eintr&auml;gen",
            "sInfoEmpty":       "0 bis 0 von 0 Einträgen",
            "sInfoFiltered":    "(gefiltert von _MAX_ Eintr&auml;gen)",
            "sInfoPostFix":     "",
            "sInfoThousands":   ".",
            "sLengthMenu":      "_MENU_ Eintr&auml;ge anzeigen",
            "sLoadingRecords":  "Wird geladen...",
            "sProcessing":      "Bitte warten...",
            "sSearch":          "Suchen",
            "sZeroRecords":     "Keine Eintr&auml;ge vorhanden.",
            "oPaginate": {
                "sFirst":       "Erste",
                "sPrevious":    "Zur&uuml;ck",
                "sNext":        "N&auml;chste",
                "sLast":        "Letzte"
            },
            "oAria": {
                "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
            }
        },
        "bFilter": false,
        "paging":   false,
        "info":     false
    } );
	
</script>
<?php include 'footer.php';?>	