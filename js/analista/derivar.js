var conteo = 0;
var filaEditada=-1;
var flag=false;
var pruebas = new Array();

// LOAD
$(document).ready(function()
{
    cargarDerivaciones();
    cargarAreasAlicuota();

    $("#area_alicuota").change(function()
    {
        if($("#area_alicuota").val()!=0)
            cargarEventosAlicuota();
        else
            $("#evento_alicuota").html('<option value="0">Seleccione...</option>');
    });
});

function mostrarMensaje(tipo)
{
    var mensaje = '';

    switch(tipo)
    {
        case 1:
            mensaje = 'Derivaci\xf3n asignada a la muestra en ventanilla que NO ha sido confirmada recibida.';
            break;
        case 2:
            mensaje = 'Derivaci\xf3n asignada en \xC1rea de an\xe1lisis que NO ha sido confirmada recibida.';
            break;
        case 3:
            mensaje = 'Derivaci\xf3n ya asignada que no ha sido enviada desde ventanilla.';
            break;
    }

    alert(mensaje);
}

function agregarDerivacion()
{
    var header = 'Imposible agregar derivaci&oacute;n: <br/>'
    var mensaje = '';
    if($("#area_alicuota").val()==0)
        mensaje+='- Por favor seleccione el &aacute;rea de an&aacute;lisis<br/>';

    if($("#evento_alicuota").val()==0)
        mensaje+='- Por favor seleccione el evento de la derivaci&oacute;n<br/>';

    if(mensaje=='')
    {
       $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/agregarDerivacion.php',
       data: "id="+ $("#muestra").val()+"&sec="+$("#area_alicuota").val()+"&ev="+$("#evento_alicuota").val()+"&tip="+$("#idEventoTipoMuestra").val(),
       
       success: function(data)
       {
           if(data=='1')
           {
               $("#errores").hide();
               $("#mensajes").show();
               cargarDerivaciones();
               $("#exitoGuardar").html("Derivaci&oacute;n agregada correctamente.");
           }
           else if(data=='2')
           {
               $("#mensajes").hide();
               $("#errores").show();
               $("#agregarError").html("Imposible agregar, la derivaci&oacute;n que seleccion&oacute; ya existe.");
           }
           else
           {
               $("#mensajes").hide();    
               $("#errores").show();
               $("#agregarError").html("Ocurri&oacute; un error, por favor intente nuevamente.");
           }
       }
     });
    }
    else
   {
        $("#errores").show();
        $("#mensajes").hide();
        $("#agregarError").html(header + mensaje);
   }
}

function cargarDerivaciones()
{
       $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/obtenerDerivaciones.php',
       data: "id="+ $("#muestra").val(),
       success: function(data){
           $("#derivaciones").html(data);
       }
     });
}

function cargarAreasAlicuota()
{
    $.getJSON(urlprefix + 'js/dynamic/areas_analisis_derivacion.php',{
        a: $("#idArea").val(),
        e: $("#idEvento").val(),
        t: $("#idEventoTipoMuestra").val(),
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';

        for (var i = 0; i < j.length; i++){
            options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
        }
        $("#area_alicuota").html(options);
        $("#evento_alicuota").html('<option value="0">Seleccione...</option>');
    })
}

function cargarEventosAlicuota()
{
    $.getJSON(urlprefix + 'js/dynamic/eventos_derivacion.php',{
    a:$("#idArea").val(),
    e:$("#idEvento").val(),
    d:$("#area_alicuota").val(),
    t:$("#idEventoTipoMuestra").val(),
    ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';

        for (var i = 0; i < j.length; i++){
            options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
        }
        $("#evento_alicuota").html(options);
    })
}

function eliminar(der)
{
    if(confirm("\xbfEst\xe1 seguro(a) que desea anular esta al\xedcuota?"))
    {
       $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/borrarDerivacion.php',
       data: "id="+ der,
       success: function(data)
       {
           if(data=='1')
           {
               $("#errores").hide();
               $("#mensajes").show();
               $("#exitoGuardar").html("&#161;Derivaci&oacute;n anulada correctamente&#33;");
               cargarDerivaciones();
           }
           else if(data=='2')
           {
               $("#mensajes").hide();
               $("#errores").show();
               $("#agregarError").html("Imposible anular la derivaci&oacute;n que seleccion&oacute; porque ya fue enviada.");
           }
           else if(data=='3')
           {
               $("#mensajes").hide();
               $("#errores").show();
               $("#agregarError").html("Imposible borrar derivaci&oacute;n ingresada en ventanilla porque no ha sido confirmada recibida.");
           }
           else
           {
               $("#mensajes").hide();
               $("#errores").show();
               $("#agregarError").html("Ocurri&oacute; un error, por favor intente nuevamente.");
           }
       }
     });
    }
}