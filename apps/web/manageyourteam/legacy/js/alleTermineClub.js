var source = new Array();
source[0] = 'functions/alleTermineClub.php?clubID=' + CLUB_ID + '&teamID=0&appointmentType=0';
var newSource = new Array();
newSource[0] = source[0];
$(document).ready(loadCalendar);

// Kalender laden
function loadCalendar(){
	$('#calendar').fullCalendar({
		eventSources: [source[0]],
		header : {
			left : 'prev,next, today',
			center : 'title',
			right : 'month,agendaWeek,agendaDay'
		},
		titleFormat : {
			month : 'MMMM yyyy', // Mai 2014
			week : "d. [MMM] [yyyy] { '-' d. MMM yyyy}", // 26. Mai - 1. Jun 2014
			day : 'dddd, d. MMM yyyy' // Montag, 26. Mai 2014
		},
		columnFormat : {
			month : 'ddd', // Mo
			week : 'ddd d.M', // Mo 26.5
			day : 'dddd, d.M' // Montag 26.5
		},
		firstDay : 1,
		editable : false,
		eventMouseover : function(event, jsEvent, view) {
			// Beim darüberfahren mit der Maus sieht man die dazugehörige Info
				$('.fc-event-inner', this).append(
						'<div id="' + event.id + '" class="hover-end" style="display: inline;"><br>'
							+ event.info
						+ '</div>');
		},
		eventMouseout : function(event, jsEvent, view) {
			$('#' + event.id).remove();
		},
		timeFormat : 'H:mm' + '\n'
	});
}

// Kalender neu laden
function reloadCalendar(clubID){
	// Gewählte Mannschaft abfragen
	var teamID = $('#teamID').val();
	// Gewählte Art abfragen
	var appointmentType = $('#appointmentType').val();
	newSource[0] = '/functions/alleTermineClub.php?clubID=' + clubID + '&teamID=' + teamID + '&appointmentType=' + appointmentType;
    $('#calendar')
        .fullCalendar('removeEventSource', source[0])
        .fullCalendar('refetchEvents')
        .fullCalendar('addEventSource', newSource[0])
        .fullCalendar('refetchEvents');
    source[0] = newSource[0];
}