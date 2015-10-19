var pagina = 1;
var conteo = 0;
var muestras = new Array();
var actual = 0;

// LOAD
$(document).ready(function()
{
    // CALENDARIOS
    $(function() {$( "#fecha_toma_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#fecha_toma_hasta" ).datepicker({showOn: "button",buttonImage: urlprefix+"img/calendar.gif",buttonImageOnly: true,showAnim: "slideDown"});});
    $(function() {$( "#fecha_recepcion_desde" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    $(function() {$( "#fecha_recepcion_hasta" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});

    $("#validacionesDiv").hide();
    $("#envio").hide();
    $("#all").hide();

    // Cambio de área de análisis
    $("#area").change(function()
    {
        if(conteo!=0)
        {
            var retVal = confirm("Debe enviar muestras de un \xe1rea \xfanicamente, si cambia de \xe1rea se perder\xe1n las muestras de la lista de env\xedo. \xbfContinuar?");

            if(retVal)
            {
                getEve();
                borrarTabla();
                $("#resultadosBusqueda").html("");

//                if($("#area").val()!=0)
//                    buscarMuestras();
            }
            // else cancelar cambio de índice
        }
        else{
            getEve();
        }
    });

    $("#rechazada").change(function(){
        $("#recha").val($(this).val());
    });

    $("#evento").change(function()
    {
        pagina = 1;
    });
});


function borrarTabla()
{   
    $("#envio").hide();
    for(var row in muestras)
       $("#eR"+muestras[row]).remove();
    muestras = new Array();
    conteo = 0;
    $("#area").attr('disabled', false);
}

function borrarLista()
{
    var retVal = confirm("\xbfBorrar el listado de env\xedo?");
    if(retVal)
        borrarTabla();
}

function getEve()
{
    $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
            idarea: $("#area").val(),
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
    $("#nombre").val("");
    $("#apellido").val("");
    $("#identificador").val("");
    $("#no_historia_clinica").val("");
    $("#rechazada").val(2);
    
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

function buscarMuestras()
{
    var Message = '';

    if($("#area").val()==0)
            Message+='- Por favor seleccione un &aacute;rea de env&iacute;o.<br/>';

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
    else{
        $("#validacionesDiv").hide();
        obtenerMuestras();
    }
}

function obtenerMuestras()
{
    $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/envioMuestras.php',
       data: "id="+jQuery.trim($("#identificador").val()) + "&his="+jQuery.trim($("#no_historia_clinica").val())
             + "&n="+jQuery.trim($("#nombre").val()) 
             + "&a="+jQuery.trim($("#apellido").val())
             + "&are="+ ($("#area").val()==0?'':$("#area").val())
             + "&ev="+ ($("#evento").val()==0?'':$("#evento").val())
         
             + "&gd="+jQuery.trim($("#codigo_global_desde").val()) + "&gh="+jQuery.trim($("#codigo_global_hasta").val())
             + "&cd="+jQuery.trim($("#codigo_correlativo_desde").val()) + "&ch="+jQuery.trim($("#codigo_correlativo_hasta").val())
             + "&td="+jQuery.trim($("#fecha_toma_desde").val()) + "&th="+jQuery.trim($("#fecha_toma_hasta").val())
             + "&rd="+jQuery.trim($("#fecha_recepcion_desde").val()) + "&rh="+jQuery.trim($("#fecha_recepcion_hasta").val())+"&pagina="+pagina,
       success: function(data){
           $("#resultadosBusqueda").html(data);
       }
     });
}

function seleccionarMuestra(numM)
{
    var c1, c2, c3;
    var clasetd = ' class="dxgv" ';
    var clasetr = ' class="dxgvDataRow_PlasticBlue" ';
    var num = ' id="eR'+numM + '"';

    // si la muestra no está agregarla
    if(jQuery.inArray(numM, muestras)==-1)
    {
        muestras.push(numM);
        c1 = $("#g"+numM).text();
        c2 = $("#c"+numM).text();
        c3 = '<a title="Quitar muestra del listado" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"'
             + 'href="javascript:quitarMuestra('+numM+')"><span class="ui-icon ui-icon-trash"></span></a></a>';
        $("#envio > tbody").append("<tr"+num+clasetr+"><td"+clasetd+">"+c1+"</td><td"+clasetd+">"+c2+"</td><td"+clasetd+">"+c3+"</td></tr>");
        conteo++;
        $("#area").attr('disabled', true);
    }
    else
        alert("La muestra que seleccion\xf3 ya se encuentra en la lista.");

    if(conteo > 0)
        $("#envio").show();
}

function quitarMuestra(idMuestra)
{
    if(conteo>0)
    {
        var retVal = confirm("\xbfEliminar la muestra del listado de env\xedo?");
        if( retVal == true )
        {
            conteo--;
            $("#eR"+idMuestra).remove();

            // borrar del array
            delete muestras[jQuery.inArray(idMuestra, muestras)];
        }
    }

    if(conteo==0){
        $("#area").attr('disabled', false);
        $("#envio").hide();
    }
}

function Enviar()
{
    if(confirm("\xbfEnviar las muestras seleccionadas?"))
    {
        var param = '';

        for(var row in muestras)
        {
           param+=muestras[row]+'-';
        }
        param = param.substr(0, param.length-1);
        window.location = urlprefix+'reportes/enviarMuestras.php?m='+param;
    }
}

function marcarTodas()
{
    
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        obtenerMuestras();
    }

}