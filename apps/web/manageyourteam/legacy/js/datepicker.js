$(function() {
	$("#datepicker").datepicker({
		changeMonth: true,
    	changeYear: true,
		showOn: "both",
		buttonImage: "/images/calendar.gif",
		buttonText: "Tag ausw�hlen",
		buttonImageOnly: true,
		dayNames: [ "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag" ],
		dayNamesMin: [ "Mo", "Di", "Mi", "Do", "Fr", "Sa", "So" ],
		monthNames: [ "Januar", "Februar", "M�rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" ],
		monthNamesShort: [ "Jan", "Feb", "M�r", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez" ],
		showWeek: true,
		weekHeader: "KW",
		minDate: new Date(2006, 2, 15),
		maxDate: "+0d",
		dateFormat: "dd.mm.yy"
	});
});