var conteo = 0;
var filaEditada=-1;
var flag=false;
var pruebas = new Array();
var pruebasAsignadas = new Array();

// LOAD
$(document).ready(function()
{
    if($("#p").val()!='')
    {
        pruebasAsignadas = $("#p").val().split(' ');
    }

    conteo = $("#conteo").val();

    for(i=0; i<conteo; i++){
        pruebas[i]=i+1;
    }

    $("#btnEditar").hide();
    $(function() {$( "#fecha" ).datepicker({showOn: "button", buttonImage: urlprefix+"img/calendar.gif", buttonImageOnly: true, showAnim: "slideDown"});});
    // Cambio de área de análisis
    $("#estado").change(function()
    {
        if($("#estado").val() == estadoAdecuado)
        {
            $("#motivo").html('<option value="'+motivoAdecuado+'">No corresponde</option>');
            $("#motivo").val(motivoAdecuado);
            $("#motivo").attr('disabled',true);
        }
        else
        {
            $("#motivo").attr('disabled',false);
            $.getJSON(urlprefix + 'js/dynamic/motivos.php',{
                e: $(this).val(),
                ajax: 'true'
            }, function(j){
                var options = '';
                options += '<option value="0">Seleccione...</option>';

                for (var i = 0; i < j.length; i++){
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#motivo").html(options);
            })
        }
    });

    $("#prueba").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/resultados.php',{
            e: $("#idEvento").val(),
            t: $("#idEventoTipoMuestra").val(),
            p: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#resultado").html(options);
        })
    });

    $("#resultadoFinal").change(function()
    {
        var found = buscarResultadoFinal();
        if(found)
        {
            // Set a No Aplica en tipo y subtipo con valores correspondientes
            $("#tipo1").html('<option value="'+noAplicaTipo+'">'+stringNoAplica+'</option>'); $("#tipo1").val(noAplicaTipo);
            $("#subtipo1").html('<option value="'+noAplicaSubtipo+'">'+stringNoAplica+'</option>'); $("#subtipo1").val(noAplicaSubtipo);
            $("#tipo2").html('<option value="'+noAplicaTipo+'">'+stringNoAplica+'</option>'); $("#tipo2").val(noAplicaTipo);
            $("#subtipo2").html('<option value="'+noAplicaSubtipo+'">'+stringNoAplica+'</option>'); $("#subtipo2").val(noAplicaSubtipo);
        }
        else
        {
            $.getJSON(urlprefix + 'js/dynamic/tipos.php',{
                e: $("#idEvento").val(),
                r: $(this).val(),
                ajax: 'true'
            }, function(j){
                var options = '';
                options += '<option value="0">Seleccione...</option>';

                for (var i = 0; i < j.length; i++){
                    if(flag)
                        options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                    else
                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#tipo1").html(options);
                $("#tipo2").html(options);
                $("#subtipo1").html('<option value="0">Seleccione...</option>');
                $("#subtipo2").html('<option value="0">Seleccione...</option>');
            })
        }

        $("#tipo1").attr('disabled', found);
        $("#subtipo1").attr('disabled', found);

        // Deshabilita resultado específico 2
        $("#tipo2").attr('disabled', true);
        $("#subtipo2").attr('disabled', true);
    });

    $("#tipo1").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/subtipos.php',{
            e: $("#idEvento").val(),
            r: $('#resultadoFinal').val(),
            tp: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#subtipo1").html(options);
        })

        // Deshabilita resultado específico 2
        $("#tipo2").attr('disabled', true);
        $("#subtipo2").attr('disabled', true);
    });

    $("#subtipo1").change(function()
    {
        if($(this).val()!=0)
        {
            // Deshabilita resultado específico 2
            $("#tipo2").attr('disabled', false);
            $("#subtipo2").attr('disabled', false);
        }
        else
        {
            // Deshabilita resultado específico 2
            $("#tipo2").attr('disabled', true);
            $("#subtipo2").attr('disabled', true);
        }
    })

    $("#tipo2").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/subtipos.php',{
            e: $("#idEvento").val(),
            r: $('#resultadoFinal').val(),
            tp: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#subtipo2").html(options);
        })
    });

});


function estado()
{
    if($("#estado").val() == estadoAdecuado)
    {
        $("#motivo").html('<option value="'+motivoAdecuado+'">No corresponde</option>');
        $("#motivo").val(motivoAdecuado);
        $("#motivo").attr('disabled',true);
    }
    else
    {
        $("#motivo").attr('disabled',false);
    }
}

function buscarResultadoFinal()
{
    var found = false;
    var x;
    for(x=0; x<conclusiones.length; x++)
    {
        if(conclusiones[x]==$("#resultadoFinal").val())
        {
            found = true;
            break;
        }
    }

    if($("#idEvento").val()== rabia && $("#resultadoFinal").val()== conclusiones[0])
        found = false;

    return found;
}

function cambioPrueba(fila,idPrueba){
        $.getJSON(urlprefix + 'js/dynamic/resultados.php',{
            e: $("#idEvento").val(),
            t: $("#idEventoTipoMuestra").val(),
            p: idPrueba,
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
            }
            $("#resultado").html(options);
        });
}

function validarAgregarEditar()
{
    var Message = '';

    if($("#prueba").val()==0)
            Message+='<br/>- Por favor seleccione una prueba.';

    if($("#resultado").val()==0)
            Message+='<br/>- Por favor seleccione un resultado.';

    if(jQuery.trim($("#fecha").val())=="")
            Message+='<br/>- Por favor ingrese la fecha en la que se realiz&oacute; la prueba.';
    else
    {
        if(!compararFechas($("#fecha").val()))
            Message+= "<br/>- La fecha la prueba no puede ser mayor que la fecha de hoy.";
    }

    if(Message!='')
    {
        $("#errores").show();
        $("#agregarError").html('Imposible agregar:' +Message);

        $("#mensajes").hide();
        $("#exitoGuardar").html(' ');
        return false;
    }
    else
    {
        $("#errores").hide();
        $("#agregarError").html('');
        return true;
    }
}

function yaExiste(prueba)
{
    if(conteo==0)
        return false;
    else
    {
        if(jQuery.inArray(prueba, pruebasAsignadas)<= -1)
            return false
        else
            return true;
    }
}

function agregarPrueba()
{
    if(validarAgregarEditar())
    {
        if(yaExiste($("#prueba").val()))
            alert("La prueba que desea agregar ya fue asignada.");
        else
        {
            $("#f"+filaEditada).html($('#fecha').val());
            $("#c"+filaEditada).html($('#comentario').val());

            var correlativo = 0;
            if(conteo==0)
                correlativo = 1;
            else
                correlativo = pruebas[pruebas.length-1]+1;

            pruebas.push(correlativo);
            pruebasAsignadas.push($("#prueba").val());

            var claseTR = ' class="dxgvDataRow_PlasticBlue" ';
            var claseTD = ' class="dxgv" ';
            var prueba = '<td id="p' +correlativo+ '" '+ claseTD + '>' + $('#prueba :selected').text()+'<input id="ip'+correlativo+'" type="hidden" value="'+$("#prueba").val()+'"/></td>';
            var resultado = '<td id="r' +correlativo+ '" '+ claseTD + '>'+ $('#resultado :selected').text()+'<input id="ir'+correlativo+'" type="hidden" value="'+$("#resultado").val()+'"/></td>';
            var fecha = '<td id="f' +correlativo+ '" '+ claseTD + '>'+$('#fecha').val()+'</td>';
            var comentarios = '<td id="c' +correlativo+ '" '+ claseTD + '>'+$('#comentario').val()+'</td>';
            var btns = ' <td align="center" style="width: 26px;">'+
                                '<a href="javascript:borrarPrueba('+correlativo+')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Borrar prueba">'+
                                    '<span class="ui-icon ui-icon-trash"></span></a></td>'+
                                '<td align="center" style="width: 26px;">'+
                                '<a href="javascript:editarPrueba('+correlativo+')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Editar prueba">'+
                                    '<span class="ui-icon ui-icon-pencil"></span></a></td>';

            var fila = '<tr id="pr'+(correlativo)+'" '+ claseTR+'>' +prueba + resultado + fecha + comentarios + btns + '</tr>';
            $("#pruebas > tbody").append(fila);
            $("#btnEditar").hide();
            $("#btnAgregar").show();
            filaEditada = -1;
            reset();

            if(conteo==0)
                $("#np").remove();

            conteo++;
            $("#final").show();
        }
    }
}

function editarPrueba(fila)
{
    filaEditada = fila;
    $("#btnEditar").show();
    $("#btnAgregar").hide();

    flag =true;
    $("#prueba").val($("#ip"+fila).val());
    cambioPrueba(fila, $("#ip"+fila).val());

    $("#comentario").val($("#c"+fila).text());
    $("#fecha").val($("#f"+fila).text());
    flag=false;
}

function borrarPrueba(fila)
{
    var retVal = confirm('\xbfBorrar esta prueba?');
    if(retVal)
    {
        conteo--;
        $("#pr"+fila).remove();

        pruebas.splice(jQuery.inArray(fila, pruebas), 1);
        pruebasAsignadas.splice(jQuery.inArray($("#ip"+fila).val(), pruebasAsignadas), 1);

        if(conteo==0){
            $("#pruebas > tbody").append('<tr class="dxgvDataRow_PlasticBlue" id="np"><td colspan="8" align="center">Muestra sin pruebas asignadas</td></tr>');
        }

        $("#btnEditar").hide();
        $("#btnAgregar").show();
        filaEditada=-1;
        reset();
    }
}


function guardarEditar()
{
    if (validarAgregarEditar())
    {
        $("#p"+filaEditada).html($('#prueba :selected').text()+'<input id="ip'+filaEditada+'" type="hidden" value="'+$("#prueba").val()+'"/>');
        $("#r"+filaEditada).html($('#resultado :selected').text()+'<input id="ir'+filaEditada+'" type="hidden" value="'+$("#resultado").val()+'"/>');
        $("#f"+filaEditada).html($('#fecha').val());
        $("#c"+filaEditada).html($('#comentario').val());

        $("#btnEditar").hide();
        $("#btnAgregar").show();
        filaEditada = -1;
        reset();
        $("#btnEditar").hide();
        $("#btnAgregar").show();
    }
}

function reset()
{
    $("#prueba").val(0);
    $("#resultado").html('<option value="0">Seleccione...</option>');
    $("#comentario").val('');
    $("#fecha").val("");
}

function borrarPruebaCargada()
{
    filaEditada = -1;
    reset();
    $("#btnEditar").hide();
    $("#btnAgregar").show();
}

function obtenerFilas()
{
    var m = '';
    for(i=0; i<conteo; i++)
    {
        if(i==0)
            m += $("#idMuestra").val() + 'x#*'+$("#ip"+pruebas[i]).val() + 'x#*'+ $("#ir"+pruebas[i]).val()
            + 'x#*'+ $("#f"+pruebas[i]).html()+ 'x#*'+ $("#c"+pruebas[i]).html();
        else
            m += '*p#@'+ $("#idMuestra").val() + 'x#*'+$("#ip"+pruebas[i]).val() + 'x#*'+ $("#ir"+pruebas[i]).val()
            + 'x#*'+ $("#f"+pruebas[i]).html()+ 'x#*'+ $("#c"+pruebas[i]).html();
    }
    return m;
}

function validarGuardarCambios()
{
    if($("#estado").val()== 0 || $("#motivo").val()==0)
    {
        $("#errores").show();
        $("#agregarError").html('Imposible guardar cambios: <br/>- Por favor seleccione el estado de la derivaci&oacute;n y/o el motivo del estado.');

        $("#mensajes").hide();
        $("#exitoGuardar").html(' ');
    }
    else
    {
        $("#errores").hide();
        $("#agregarError").html('');

        var retVal = confirm('\xbfGuardar los cambios realizados?');
        if(retVal)
        {
            $("#filas").val(obtenerFilas());
            $("#f").val(0);
            $("#tablaPruebasError").val($("#pruebas").html());
            $("#formPruebas").submit();
        }
    }
}

function validarConclusion()
{
    // Validar combos seleccionados
    var Message = '';

    if($("#estado").val()== 0)
        Message+='- Por favor seleccione el estado de la muestra';

    if($("#motivo").val()== 0)
        Message+='<br/>- Por favor seleccione el motivo del estado de la muestra';

    if($("#resultadoFinal").val()== 0)
        Message+='<br/>- Por favor seleccione un Resultado Final.';

//    if($("#tipo1").val()== $("#tipo2").val() && $("#subtipo1").val()== $("#subtipo2").val() && $("#tipo1").val()!=0 && $("#subtipo1").val()!=0)
//    {
//        if($("#resultadoFinal").val()== finalPositivo)
//        {
//            if(!($("#tipo1").val()== noAplicaTipo && $("#subtipo1").val()==noAplicaSubtipo
//               && $("#tipo2").val()== noAplicaTipo && $("#subtipo2").val()==noAplicaSubtipo))
//                Message+='<br/>- Por favor seleccione Resultados Espec&iacute;ficos diferentes.';
//        }
//        else
//        if($("#resultadoFinal").val()!= finalPositivo)
//        {
//            if($("#resultadoFinal").val()!=conclusiones[indiceNegativo] && $("#resultadoFinal").val()!=conclusiones[indiceIndeterminado]
//                && $("#resultadoFinal").val()!=conclusiones[indiceNoprocesada] && $("#resultadoFinal").val()!=conclusiones[indiceMalamuestra]
//                && $("#resultadoFinal").val()!=conclusiones[indiceSensible] && $("#resultadoFinal").val()!=conclusiones[indiceMDR]
//                && $("#resultadoFinal").val()!=conclusiones[indiceXDR])
//            Message+='<br/>- Por favor seleccione Resultados Espec&iacute;ficos diferentes.';
//        }
//    }

    if($("#tipo1").val()== 0 || $("#subtipo1").val()== 0)
        Message+='<br/>- Por favor seleccione ambas opciones del Resultado Espec&iacute;fico No.1';

    if($("#tipo2").val()== 0 || $("#subtipo2").val()== 0)
        Message+='<br/>- Por favor seleccione ambas opciones del Resultado Espec&iacute;fico No.2';

    if(Message!='')
    {
        $("#errores").show();
        $("#agregarError").html('Imposible concluir an&aacute;lisis:' +Message);

        $("#mensajes").hide();
        $("#exitoGuardar").html(' ');
        return false;
    }
    else
    {
        $("#errores").hide();
        $("#agregarError").html('');
        return true;
    }
}

function validarGuardarCambiosFin()
{
    if(validarConclusion())
    {

        if($("#resultadoFinal").val()!=conclusiones[indiceNoprocesada] && $("#resultadoFinal").val()!=conclusiones[indiceMalamuestra])
        {
            if(conteo!=0)
            {
                var retVal = confirm('\xbfEst\xe1 seguro(a) que desea finalizar el an\xe1lisis de la muestra?');
                if(retVal)
                {
                    $("#finalizado").val(recibidoDer);
                    $("#f").val(1);
                    $("#filas").val(obtenerFilas());

                    $("#tipo1").attr('disabled', false);
                    $("#subtipo1").attr('disabled', false);
                    $("#tipo2").attr('disabled', false);
                    $("#subtipo2").attr('disabled', false);
                    $("#tablaPruebasError").val($("#pruebas").html());
                    $("#formPruebas").submit();
                }
            }
            else
            {
                $("#errores").show();
                $("#agregarError").html('Por favor asignar al menos una prueba a la muestra');
            }
        }
        else
        {
            if(confirm('\xbfEst\xe1 seguro(a) que desea finalizar el an\xe1lisis de la muestra?'))
            {
                $("#finalizado").val(recibidoDer);
                $("#f").val(1);
                $("#filas").val(obtenerFilas());
                $("#tipo1").attr('disabled', false);
                $("#subtipo1").attr('disabled', false);
                $("#tipo2").attr('disabled', false);
                $("#subtipo2").attr('disabled', false);
                $("#tablaPruebasError").val($("#pruebas").html());
                $("#formPruebas").submit();
            }
        }
    }
}