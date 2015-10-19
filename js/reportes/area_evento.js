var pagina = 1;
var partes;

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
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento").html(options);
            $("#resultado").html('<option value="0">Seleccione...</option>');
        })
    });





    $("#as").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/distritos.php',{
            idas: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#ds").html(options);
            $("#establecimiento").html('<option value="0">Seleccione</option>');
        })
    });

    $("#ds").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/servicios.php',{idas:$("#as").val(), idds:$("#ds").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '-' + j[i].tipo + '-' + j[i].hosp + '">' + j[i].optionDisplay + '</option>';
            }
            $("#establecimiento").html(options);
        })
    });


    $("#evento").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/rfinales.php',{e:$(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '-' + j[i].tipo + '-' + j[i].hosp + '">' + j[i].optionDisplay + '</option>';
            }
            $("#resultado").html(options);
        })
    });

    $("#establecimiento").change(function()
    {
        changeServicio();
    });
});

function pdf_xls()
{
    if($("#pdf").is(':checked'))
    {
        $("#xls").attr('checked','');
    }
    else
    {
        $("#xls").attr('checked',true)
    }
}

function xls_pdf()
{
    if($("#xls").is(':checked'))
    {
        $("#pdf").attr('checked','');
    }
    else
    {
        $("#pdf").attr('checked',true)
    }
}


function changeServicio()
{
    var texto = $("#establecimiento").val();
    partes = texto.split('-');
    $("#tipo_establecimiento").val(partes[1]);
    $("#centro").val(partes[0]);
}



function getEve()
{
    $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
            idarea: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento").html(options);
        })
}

function getEstablecimiento(){
    $.getJSON(urlprefix + 'js/dynamic/unidades.php',{
        idmun: $("#mun").val(),
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';

        for (var i = 0; i < j.length; i++){
            options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
        }
        $("#est").html(options);
    });
}

function getMunicipios(){
    if($("#dep").val()!=0){
        $.getJSON(urlprefix + 'js/dynamic/municipios.php',{
            iddep: $("#dep").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#mun").html(options);
        });
    }
}


function borrarFiltro()
{
    $("#nombres").val("");
    $("#apellidos").val("");

    $("#as").val("");
    $("#ds").val("");
    
    $("#area").val(0);
    $("#evento").val(0);
    $("#estado").val(0);
    $("#resultado").val(0);

    $("#codigo_global_desde").val("");
    $("#codigo_global_hasta").val("");
    $("#codigo_correlativo_desde").val("");
    $("#codigo_correlativo_hasta").val("");

    $("#fecha_toma_desde").val("");
    $("#fecha_toma_hasta").val("");
    $("#tipo_establecimiento").val(0);
    $("#establecimiento").val(0);
    $("#otro").val("");
    $("#establecimiento").attr('disabled',false);
    $("#centro").val(0);
}


function buscarMuestra()
{
    var Message = '';

    if($("#area").val()==0)
        Message+='<br/> - Por favor seleccione el &aacute;rea de an&aacute;lisis.';

    if($("#evento").val()==0)
        Message+='<br/> - Por favor seleccione el evento.';

    if(jQuery.trim($("#fecha_toma_desde").val())!='')
    {
        if(!isDate($("#fecha_toma_desde").val()))
            Message+='<br/> - Por favor ingrese una fecha de conclusi&oacute;n (desde) adecuada.';
    }

    if(jQuery.trim($("#fecha_toma_hasta").val())!='')
    {
        if(!isDate($("#fecha_toma_hasta").val()))
            Message+='<br/> - Por favor ingrese una fecha de conclusi&oacute;n (hasta) adecuada.';
    }

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

    if(Message!='')
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:' + Message);
    }
    else
    {
        $("#errores").hide();
        $("#error").html('');
        generarReporte();
        //$("#formBuscar").submit();
    }
        
}

function otroLugar()
{
    if(jQuery.trim($("#otro").val())!="")
    {
        $("#establecimiento").val(0);
        $("#establecimiento").attr('disabled',true);
        $("#tipo_establecimiento").val(0);
    }
    else
        $("#establecimiento").attr('disabled',false);
}


// Envía parámetros de reporte y nombre de persona responsable
function generarReporte()
{   
    var nombre ='';
    var atencion = '';
    nombre = prompt("Por favor ingrese el nombre del responsable que firma:");
    nombre = jQuery.trim(nombre);

    if(nombre!='')
    {
        $("#errores").hide();
        $("#error").html(' ');

        atencion = prompt("Por favor ingrese a qui\xe9n va dirigido este reporte (si es opcional presione Cancelar)");
        atencion = jQuery.trim(atencion);

        window.open(urlprefix+'reportes/area_evento/reporteAreaEvento.php?n='+jQuery.trim($("#nombres").val())
        + '&ap='+jQuery.trim($("#apellidos").val()) + '&a='+$("#area").val() + '&e='+$("#evento").val() +'&evNom='+ $("#evento option:selected").text()
        + '&dep='+$("#dep").val() + '&mun='+$("#mun").val() + '&es='+$("#est").val()
        + '&o='+jQuery.trim($("#otro").val()) + '&r='+$("#resultado").val()
        +'&tipo='+ ($("input[@name='file']:checked").val() == '1'?'1':'2')
        + '&estado='+$("#estado").val() + '&gd='+jQuery.trim($("#codigo_global_desde").val()) + '&gh='+jQuery.trim($("#codigo_global_hasta").val())
        + '&cd='+jQuery.trim($("#codigo_correlativo_desde").val()) + '&ch='+jQuery.trim($("#codigo_correlativo_hasta").val())
        + '&dd='+$("#fecha_toma_desde").val()+'&dh='+$("#fecha_toma_hasta").val()+'&firma='+jQuery.trim(nombre)+'&atencion='+jQuery.trim(atencion)+'&aa='+ $("#area option:selected").text(), 'Reporte',
        "toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes", window.width, window.height);
    }
    else
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte: <br/>- Por favor ingrese el nombre del responsable');
    }
}