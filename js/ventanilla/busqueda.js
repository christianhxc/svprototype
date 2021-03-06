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

    $("#rechazada").change(function(){
        $("#recha").val($(this).val());
    });
});

function confirmarAnular(muestra)
{
    if(confirm("\xbfEst\xe1 seguro que desea anular esta muestra?"))
        window.location = urlprefix + "ventanilla/ingreso.php?action=B&m="+muestra;
}


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
    $("#rechazada").val(2);
    $("#ubicacion").val(0);
    $("#tipo_muestra").val(0);
    
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

    if(jQuery.trim($("#fecha_toma_desde").val())!='')
    {
        if(!isDate($("#fecha_toma_desde").val()))
            Message+='- Por favor ingrese una fecha inicial de toma de muestra adecuada.<br/>';
    }

    if(jQuery.trim($("#fecha_toma_hasta").val())!='')
    {
        if(!isDate($("#fecha_toma_hasta").val()))
            Message+='- Por favor ingrese una fecha final de toma de muestra adecuada.<br/>';
    }

    if(jQuery.trim($("#fecha_recepcion_desde").val())!='')
    {
        if(!isDate($("#fecha_recepcion_desde").val()))
            Message+='- Por favor ingrese una fecha inicial de recepci&oacute;n de muestra adecuada.<br/>';
    }

    if(jQuery.trim($("#fecha_recepcion_hasta").val())!='')
    {
        if(!isDate($("#fecha_recepcion_hasta").val()))
            Message+='- Por favor ingrese una fecha final de recepci&oacute;n de muestra adecuada.<br/>';
    }

    // CODIGOS GLOBAL Y CORRELATIVO
    if(jQuery.trim($("#codigo_global_desde").val())!='')
    {
        if(!parseInt($("#codigo_global_desde").val(), 10))
            Message+='- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo global (desde).<br/>';
    }

    if(jQuery.trim($("#codigo_global_hasta").val())!='')
    {
        if(!parseInt($("#codigo_global_hasta").val(), 10))
            Message+='- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo global (hasta).<br/>';
    }

    if(jQuery.trim($("#codigo_correlativo_desde").val())!='')
    {
        if(!parseInt($("#codigo_correlativo_desde").val(), 10))
            Message+='- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo correlativo (desde).<br/>';
    }

    if(jQuery.trim($("#codigo_correlativo_hasta").val())!='')
    {
        if(!parseInt($("#codigo_correlativo_hasta").val(), 10))
            Message+='- Por favor ingrese &uacute;nicamente n&uacute;meros para buscar por c&oacute;digo correlativo (hasta).<br/>';
    }


    if(Message!='')
    {
        $("#validacionesDiv").show();
        $("#validaciones").html(Message);
    }
    else
        $("#formBuscar").submit();
        
}
