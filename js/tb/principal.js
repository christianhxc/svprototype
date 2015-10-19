
$(document).ready(function() {
//    borrarTabla();
//    busquedatb();
//    Tooltip_field();
    $("#total_consultas_antepasado").change(function(){
        sintomaticos_respiratorios();
//        buscarqtrSintResp();
    })
    $("#anio").change(function(){
        sintomaticos_respiratorios();
//        buscarqtrSintResp();
    })
    
    $("#drpCorIndividuo").change(function(){
        if ($(this).val() != "0") {
            $( "#notificacion_unidad" ).attr("disabled",false);
            $( "#notificacion_id_un" ).val("");
            $( "#notificacion_unidad" ).val("");
            corr = $("#drpCorIndividuo").val();
        } else
        {
            $( "#notificacion_id_un" ).val("");
            $( "#notificacion_unidad" ).val("");
            $( "#notificacion_unidad" ).attr("disabled",true);
            
        }
        
    });

    
   $( "#notificacion_unidad" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_id.php?corr=" + document.getElementById('drpCorIndividuo').value + "&",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:0, 
        onItemSelect:function(li){
            $("#notificacion_unidad").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un").val(li.extra[0]);
            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
//    $("#notificacion_unidad").keypress(function () {
//        if ($(this).val().length >= 2){
//            $.ajax({
//                type: "POST",
//                url: urlprefix + "js/dynamic/unidadNotificadora_id.php",
//                data: "q=" + $(this).val() + "corr=10",
//                success: function (data)
//                {
//                    alert(JSON.stringify(data));
//                }
//            });
//        }
//    });

});

//function Tooltip_field(){
//    $('#filtro').qtip({
//        position: {
//            my: 'bottom left',  // Position my top left...
//            at: 'top right' // at the bottom right of...            
//        },
//        style: {
//            classes: 'qtip-green',
//            tip: true
//        },
//        content: 'Esta palabra puede ser nombre de la instalaci&oacute;n de salud, regi&oacute;n, diagn&oacute;stico, identificaci&oacute;n del paciente o la semana epidemiol&oacute;gica.',
//        show: 'mouseover',
//        hide: 'mouseout'
//    }); 
//    
//    $('#drpHospital').qtip({
//        position: {
//            my: 'bottom left',  // Position my top left...
//            at: 'top right' // at the bottom right of...            
//        },
//        style: {
//            classes: 'qtip-green',
//            tip: true
//        },
//        content: 'Al seleccionar una opci&oacute;n en Hospital filtrar&aacute; la informaci&oacute;n con la opci&oacute;n escogida',
//        show: 'mouseover',
//        hide: 'mouseout'
//    }); 
//    
//    
//    
//    
//    $('#drpUceti').qtip({
//        position: {
//            my: 'bottom left',  // Position my top left...
//            at: 'top right' // at the bottom right of...            
//        },
//        style: {
//            classes: 'qtip-green',
//            tip: true
//        },
//        content: 'Al seleccionar una opci&oacute;n en Formularios filtrar&aacute; la informaci&oacute;n con las opciones Pendientes, Finalizados o Todos',
//        show: 'mouseover',
//        hide: 'mouseout'
//    }); 
//    $('#drpSilab').qtip({
//        position: {
//            my: 'bottom left',  // Position my top left...
//            at: 'top right' // at the bottom right of...            
//        },
//        style: {
//            classes: 'qtip-green',
//            tip: true
//        },
//        content: 'Al seleccionar una opci&oacute;n en Muestras Lab. Silab filtrar&aacute; la informaci&oacute;n con las opciones Pendientes, Confirmadas o Todas',
//        show: 'mouseover',
//        hide: 'mouseout'
//    }); 
//}

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        validarUceti();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedatb()
{
    pagina = 1;
    validartb();
}


function validartb()
{
    buscartb();
}

function buscarqtrSintResp()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/tb/paginiciotb.php',
        data: "filtro="+jQuery.trim($("#filtro").val()),
        success: function(data)
        {
            alert(JSON.stringify(data));
        }
    });
}

function setRegionCascada(){
    
    setRegionPersona($("#drpProIndividuo").val(),-1);
    $("#drpRegIndividuo").val("");
}

function setRegionPersona(idProvincia, idRegion)
{
    $.getJSON(urlprefix + 'js/dynamic/regiones.php',{
        idProvincia: idProvincia,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idRegion)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpRegIndividuo").html(options);
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpProIndividuo").val(),$("#drpRegIndividuo").val(),-1);
}

function setDistritoPersona(idProvincia, idRegion, idDistrito)
{
    $.getJSON(urlprefix + 'js/dynamic/distritos.php',{
        idProvincia: idProvincia,
        idRegion:idRegion,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idDistrito)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpDisIndividuo").html(options);
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDisIndividuo").val(),-1);
}

function setCorregimientoPersona(idDistrito, idCorregimiento)
{
    $.getJSON(urlprefix + 'js/dynamic/corregimientos.php',{
        idDistrito: idDistrito,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idCorregimiento)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }
        
        $("#drpCorIndividuo").html(options);
    })
}

function sintomaticos_respiratorios()
{
    
    if ($("#anio").val() == "") 
        {alert("Por favor ingrese el anio de evaluacion");
        return;
        }
    
    if ($("#drpProIndividuo").val() == "0") 
        {   
            alert("Por favor ingrese la Provincia");
            return;
        }
    if ($("#drpRegIndividuo").val() == "0") 
        {
            alert("Por favor ingrese la Region");
            return;
        }
    if ($("#drpDisIndividuo").val() == "0") 
        {alert("Por favor ingrese el Distrito");
        return;
        }
    if ($("#drpCorIndividuo").val() == "0") 
        {alert("Por favor ingrese el Corregimiento");
        return;
        }
        
    if ($("#notificacion_unidad").val() == "") 
        {alert("Por favor ingrese los datos de institucion de salud");
        return;
        }
        

    if ($("#total_consultas_antepasado").val()!="")
        {
            // Calculo de los Programados en SINTOMÁTICOS RESPIRATORIOS
            porc_sint_resp= $("#total_consultas_antepasado").val()*$("#porc_sint_resp_pro").val()/100;
            trimestres_sint_resp = Math.round(porc_sint_resp/4);
            $("#sint_prog_1").text(trimestres_sint_resp);
            $("#sint_prog_2").text(trimestres_sint_resp);
            $("#sint_prog_3").text(trimestres_sint_resp);
            $("#sint_prog_4").text(trimestres_sint_resp);
            
            
            // Calculo de los Programados en CASOS NUEVOS BK POSITIVOS:
            
            porc_casos_nuevos_bk_pos = porc_sint_resp * $("#porc_sint_resp_pro_bk_pl").val() / 100;
            trimestre_caso_nuevo_bk = Math.round(porc_casos_nuevos_bk_pos/4);
            $("#caso_nuevo_bk_1").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_2").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_3").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_4").text(trimestre_caso_nuevo_bk);
            
        }
        else
            {
                
            }
}