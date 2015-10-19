$(document).ready(function() {
	$('form input').keypress(function(e){
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)){
			$('form:frmSearch').submit();
			e.preventDefault();
		}
	});

    $("#fecha_notificacion_desde").datepicker({
//		showOn: 'button',buttonImage: urlprefix + 'images/calendar.png',buttonImageOnly: true,dateFormat: 'dd/mm/yy'
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showOn: "both",
                buttonImage: urlprefix+"img/calendar.gif",
                buttonImageOnly: true,
                showAnim: "slideDown"
	});
        
                

	$("#fecha_notificacion_hasta").datepicker({
//		showOn: 'button',buttonImage: urlprefix + 'images/calendar.png',buttonImageOnly: true,dateFormat: 'dd/mm/yy'
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                showOn: "both",
                buttonImage: urlprefix+"img/calendar.gif",
                buttonImageOnly: true,
                showAnim: "slideDown"
	});
});