$(function() {
    $( "#fecha_ini" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_fin" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});


// LOAD
$(document).ready(function() {
    $("#divIntalacion").hide();
    $( "#unidad_notificadora" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#unidad_notificadora").val($("<div>").html(li.selectValue).text());
            $("#id_un").val(li.extra[0]);
        },
        autoFill:false
    });
 });
 
function tipoReporte(){
    var tipReporte = $("#drpTipoReporte").val(); 
    if (tipReporte == 1){
        $("#divIntalacion").show();
    }
    else {
        $("#unidad_notificadora").val("");
        $("#id_un").val("");
        $("#divIntalacion").hide();
    }
}

function borrarFiltro(){
  
    $("#drpCaso").val(0);
    $("#drpCondicion").val(0);
    
    $("#anio_ini").val("");
    $("#anio_fin").val("");
}


function validarReporte()
{
    var Message = "";
    var nombreUn = "";
    var numReporte = $("#drpReporte").val();    
    var fechaIni = $("#fecha_ini").val();
    var fechaFin = $("#fecha_fin").val();
    var tipReporte = $("#drpTipoReporte").val(); 
    var idUn = 0;
    if (fechaIni=="" || fechaFin=="")
        Message+="<br/> - Debe ingresar las fechas iniciales y finales para tener un rango de fechas";
    if ( validarFechas(fechaFin,fechaIni))
        Message+="<br/> - La fecha inicial debe ser menor a la fecha final";
        
    if(numReporte == '-1')  
        Message+="<br/> - Debe seleccionar el reporte que desea generar";
    
    if(tipReporte == '-1')
        Message+="<br/> - Debe seleccionar el tipo de reporte que desea generar";
    if (tipReporte == '1'){
        if($("#id_un").val() == '')
            Message+="<br/> - Selecciono reporte por instalacion de salud, debe seleccionar la instalacion de salud";
    }

    if(Message!='')
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:' + Message);
    }
    else
    {
        $("#errores").hide();
        $("#error").html('');
        if(tipReporte == 1){
            idUn = $("#id_un").val();
            nombreUn = $("#unidad_notificadora").val();
        }
        generarReporte(numReporte, fechaIni, fechaFin, idUn, nombreUn);
        //$("#formBuscar").submit();
    }        
}

// Envía parámetros de reporte
function generarReporte(numReporte, fechaIni, fechaFin, idUn, nombreUn){
    $("#errores").hide();
    $("#error").html(' ');
    window.open(urlprefix+'reportes/vicits/PuenteReportesConsolidadosVicits.php?r='+numReporte+'&fi='+fechaIni+'&ff='+fechaFin+'&un='+idUn+'&nun='+nombreUn
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

function trim(myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}