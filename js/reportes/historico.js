var pagina = 1;

// LOAD
$(document).ready(function()
{

    // CALENDARIOS
    $(function() {$( "#fecha_toma_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#fecha_toma_hasta" ).datepicker({showOn: "button",buttonImage: urlprefix+"img/calendar.gif",buttonImageOnly: true,showAnim: "slideDown"});});
    $(function() {$( "#fecha_recepcion_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#fecha_recepcion_hasta" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});

    $("#validacionesDiv").hide();

    // Cambio de área de análisis
    $("#area").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
            idarea: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento").html(options);
        })
    });
});


function getEve()
{
    $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
            idarea: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento").html(options);
        })
}


function borrarFiltro()
{
    $("#nombres").val("");
    $("#apellidos").val("");
    $("#identificador").val("");

    $("#no_historia_clinica").val("");     
    $("#area").val(0);
    $("#evento").val(0);

    $("#codigo_global_desde").val("");
    $("#codigo_global_hasta").val("");
    $("#codigo_correlativo_desde").val("");
    $("#codigo_correlativo_hasta").val("");

    $("#fecha_toma_desde").val("");
    $("#fecha_toma_hasta").val("");
    $("#fecha_recepcion_desde").val("");
    $("#fecha_recepcion_hasta").val("");
}


function buscarMuestra()
{
    var Message = '';


    // CODIGOS GLOBAL Y CORRELATIVO
    if(jQuery.trim($("#codigo_global_desde").val())!='')
    {
        if(!parseInt($("#codigo_global_desde").val(), 10))
            Message+='<br/>- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo global (desde).';
    }

    if(jQuery.trim($("#codigo_global_hasta").val())!='')
    {
        if(!parseInt($("#codigo_global_hasta").val(), 10))
            Message+='<br/>- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo global (hasta).';
    }

    if(jQuery.trim($("#codigo_correlativo_desde").val())!='')
    {
        if(!parseInt($("#codigo_correlativo_desde").val(), 10))
            Message+='<br/>- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo correlativo (desde).';
    }

    if(jQuery.trim($("#codigo_correlativo_hasta").val())!='')
    {
        if(!parseInt($("#codigo_correlativo_hasta").val(), 10))
            Message+='<br/>- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo correlativo (hasta).';
    }

    if(jQuery.trim($("#fecha_toma_desde").val())!='')
    {
        if(!isDate($("#fecha_toma_desde").val()))
            Message+='<br/>- Por favor ingrese una fecha inicial de toma de muestra adecuada.';
    }

    if(jQuery.trim($("#fecha_toma_hasta").val())!='')
    {
        if(!isDate($("#fecha_toma_hasta").val()))
            Message+='<br/>- Por favor ingrese una fecha final de toma de muestra adecuada.';
    }

    if(jQuery.trim($("#fecha_recepcion_desde").val())!='')
    {
        if(!isDate($("#fecha_recepcion_desde").val()))
            Message+='<br/>- Por favor ingrese una fecha inicial de recepci&oacute;n de muestra adecuada.';
    }

    if(jQuery.trim($("#fecha_recepcion_hasta").val())!='')
    {
        if(!isDate($("#fecha_recepcion_hasta").val()))
            Message+='<br/>- Por favor ingrese una fecha final de recepci&oacute;n de muestra adecuada.';
    }


    if(Message!='')
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:'+Message);
    }
    else
        $("#formBuscar").submit();
        
}

// Envía parámetros de reporte y nombre de persona responsable
function generarReporte(id, tip)
{   
    var nombre ='';
    nombre = prompt("Por favor ingrese el nombre del responsable que firma:");
    nombre = jQuery.trim(nombre);

    if(nombre!=''){
        window.open(urlprefix+'reportes/reporteHistorico.php?lista='+id+'&tip='+tip+'&firma='+nombre,  'Reporte',
        "toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes", window.width, window.height);
    }
    else
        alert("Por favor ingrese el nombre del responsable");
}