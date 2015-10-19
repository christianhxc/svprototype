var pagina = 1;
var partes;

// LOAD
$(document).ready(function()
{

    // CALENDARIOS
    $(function() {$( "#toma_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#toma_hasta" ).datepicker({showOn: "button",buttonImage: urlprefix+"img/calendar.gif",buttonImageOnly: true,showAnim: "slideDown"});});
    $(function() {$( "#r_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#r_hasta" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#inicio_hasta" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#inicio_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});

    $("#validacionesDiv").hide();

    // Cambio de área de análisis
    $("#area").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
            idarea: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Todos...</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento").html(options);
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
            $("#establecimiento").html('<option value="0">N/A</option>');
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



    $("#establecimiento").change(function()
    {
        changeServicio();
    });
});


function changeServicio()
{
    var texto = $("#establecimiento").val();
    partes = texto.split('-');
    $("#tipo_establecimiento").val(partes[1]);
    $("#centro").val(partes[0]);
}



function getEve()
{
    if($("#area").val()!=0){
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
            });
    }
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
            options += '<option value="0">Seleccione...</option>';

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

    $("#codigo_global_desde").val("");
    $("#codigo_global_hasta").val("");

    $("#inicio_desde").val("");
    $("#inicio_hasta").val("");

    $("#toma_desde").val("");
    $("#toma_hasta").val("");

    $("#r_desde").val("");
    $("#r_hasta").val("");

    $("#codigo_correlativo_desde").val("");
    $("#codigo_correlativo_hasta").val("");
    $("#establecimiento").val(0);

    $("#tipo_establecimiento").val(0);
    $("#otro").val("");
    $("#establecimiento").attr('disabled',false);
    $("#centro").val(0);
}


function buscarMuestra()
{
    var Message = '';

    if($("#area").val()==0)
        Message+='<br/> - Por favor seleccione el &aacute;rea de an&aacute;lisis.';

    if(jQuery.trim($("#toma_desde").val())!='')
    {
        if(!isDate($("#toma_desde").val()))
            Message+='<br/> - Por favor ingrese una fecha de toma inicial adecuada.';
    }

    if(jQuery.trim($("#toma_hasta").val())!='')
    {
        if(!isDate($("#toma_hasta").val()))
            Message+='<br/> - Por favor ingrese una fecha de toma final adecuada.';
    }

    if(jQuery.trim($("#r_hasta").val())!='')
    {
        if(!isDate($("#r_hasta").val()))
            Message+='<br/> - Por favor ingrese una fecha de recepci&oacute;n final adecuada.';
    }

    if(jQuery.trim($("#r_desde").val())!='')
    {
        if(!isDate($("#r_desde").val()))
            Message+='<br/> - Por favor ingrese una fecha de recepci&oacute;n inicial adecuada.';
    }

    if(jQuery.trim($("#inicio_desde").val())!='')
    {
        if(!isDate($("#inicio_desde").val()))
            Message+='<br/> - Por favor ingrese una fecha de inicio de s&iacute;ntomas adecuada.';
    }

    if(jQuery.trim($("#inicio_hasta").val())!='')
    {
        if(!isDate($("#inicio_hasta").val()))
            Message+='<br/> - Por favor ingrese una fecha de inicio de s&iacute;ntomas final adecuada.';
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


// Envía parámetros de reporte
function generarReporte()
{
    $("#errores").hide();
    $("#error").html(' ');
    window.open(urlprefix+'reportes/exportables/reporteExportable.php?n='+jQuery.trim($("#nombres").val())
    + '&ap='+jQuery.trim($("#apellidos").val()) + '&a='+$("#area").val() + '&e='+$("#evento").val()
    + '&dep='+$("#dep").val() + '&mun='+$("#mun").val() + '&es='+$("#est").val() + '&o='+jQuery.trim($("#otro").val())
    + '&estado='+$("#estado").val() + '&gd='+jQuery.trim($("#codigo_global_desde").val()) + '&gh='+jQuery.trim($("#codigo_global_hasta").val())
    + '&cd='+jQuery.trim($("#codigo_correlativo_desde").val()) + '&ch='+jQuery.trim($("#codigo_correlativo_hasta").val())
    + '&id='+$("#inicio_desde").val()+'&ih='+$("#inicio_hasta").val()
    + '&td='+$("#toma_desde").val()+'&th='+$("#toma_hasta").val()
    + '&rd='+$("#r_desde").val()+'&rh='+$("#r_hasta").val()
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}