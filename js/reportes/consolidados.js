$(document).ready(function()
{
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



function borrarFiltro()
{
    $("#evento").val(0);
    $("#semana_desde").val("");
    $("#semana_hasta").val("");
    $("#anio_desde").val("");
    $("#anio_hasta").val("");
}


function validar()
{
    var Message = '';

//    if($("#area").val()==0)
//        Message+= '<br/>- Por favor seleccione un area de analisis.';

    if(jQuery.trim($("#semana_desde").val())!='' && jQuery.trim($("#semana_hasta").val())!='')
    {
        if($("#semana_desde").val()>$("#semana_hasta").val())
            Message+= '<br/>- Por favor revise las semanas ingresadas.';
    }

    if(jQuery.trim($("#anio_desde").val())!='' && jQuery.trim($("#anio_hasta").val())!='')
    {
        if($("#anio_desde").val()>$("#anio_hasta").val())
            Message+= '<br/>- Por favor revise los a&ntilde;os ingresados.';
    }

    if(jQuery.trim($("#anio_desde").val())!='')
    {
        if(!parseInt($("#anio_desde").val()))
            Message+= '<br/>- Por favor revise el a&ntilde;o desde.';
    }
    
    if(jQuery.trim($("#anio_hasta").val())!='')
    {
        if(!parseInt($("#anio_hasta").val()))
            Message+= '<br/>- Por favor revise el a&ntilde;o hasta.';
    }

    if(jQuery.trim($("#semana_desde").val())!='')
    {
        if(!parseInt($("#semana_desde").val()))
            Message+= '<br/>- Por favor revise la semana desde.';
    }

    if(jQuery.trim($("#semana_hasta").val())!='')
    {
        if(!parseInt($("#semana_hasta").val()))
            Message+= '<br/>- Por favor revise la semana hasta.';
    }

    if(Message!='')
    {
        $("#error").html('Imposible generar reporte: '+Message);
        $("#error").show();
        $("#errores").show();
    }
    else
    {
        $("#error").html('');
        $("#errores").hide();
        generarReporte();
    }
}

function generarReporte()
{
//    if($("#area").val()!=0)
//    {
        $("#error").html('');
        $("#errores").hide();
        window.open (urlprefix+'reportes/consolidados/consolidados.php?a='+$("#area").val()+'&e='+$("#evento").val()+'&sd='+$("#semana_desde").val()
        +'&sh='+$("#semana_hasta").val() +'&ad='+$("#anio_desde").val() +'&ah='+$("#anio_hasta").val() +'&tipo='+jQuery('#radio_form input:radio:checked').val(),
        "mywindow","menubar=1,resizable=1", window.width, window.height);
//    }
//    else
//    {
//        $("#error").html('Imposible generar reporte: <br>- Por favor seleccione el area de analisis.');
//        $("#error").show();
//        $("#errores").show();
//    }
}