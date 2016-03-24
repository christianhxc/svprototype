
$(document).ready(function() {
    
    $("#id_tb_inicio").val("");

//    <!-- Calculos automaticos -->



    $("#total_consultas_antepasado").change(function(){
        tabla1();
        tabla2();
        tabla3();
    });
    $("#anio").change(function(){
        if ($(this).val() == "")  clearForm();
        if ($(this).val() != "" && $( "#notificacion_unidad" ).val() != ""){
            clearForm();
            activeForm();
        }
    });
    
    $("#ident_1, #ident_2, #ident_3, #ident_4").change(function(){
        tabla_1();
    });
    
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

    $( "#notificacion_unidad" ).change(function(){
        
        if ($("#anio").val() == "") clearForm();
            
        if ($("#anio").val() != ""){
            clearForm();
            activeForm();
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

   clearForm();

});



function clearForm()
{
    $("#total_consultas_antepasado").val("");
    $("#total_consultas_antepasado").attr("disabled",true);
    $("input[id^='ident_']").val("");
    $("input[id^='ident_']").attr("disabled",true);
    $("input[id^='cap_']").val("");
    $("input[id^='cap_']").attr("disabled",true);
    $("input[id^='pos_']").val("");
    $("input[id^='pos_']").attr("disabled",true);
    $("#guardar").hide();
}

function activeForm()
{
    $("#total_consultas_antepasado").attr("disabled",false);
    $("input[id^='ident_']").attr("disabled",false);
    $("input[id^='cap_']").attr("disabled",false);
    $("input[id^='pos_']").attr("disabled",false);
    $("#guardar").show();
}

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
    setDistritoPersona(-1,-1);
    $("#drpDisIndividuo").val("");
    setCorregimientoPersona(-1,-1);
    $("#drpCorIndividuo").val("");
    
    $("#notificacion_unidad").val("");
    $("#notificacion_unidad").attr("disabled",true);
    clearForm();
    
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
    setCorregimientoPersona(-1,-1);
    $("#drpCorIndividuo").val("");
    
    $("#notificacion_unidad").val("");
    $("#notificacion_unidad").attr("disabled",true);
    
    clearForm();
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
    
    $("#notificacion_unidad").val("");
    $("#notificacion_unidad").attr("disabled",true);
    
    clearForm();
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

function sintomaticos_respiratorios(action)
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
    
    $("#act").val(action);
    
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/tb/paginiciotb.php',
        data: $("#formPagInicio").serialize(),
        dataType: "json",
        success: function(data)
        {
            if($("#act").val() == "C"){
               
               if (data.id_tb_inicio == null)  
               {
                   alert("No hay registros de este establecimiento, el siguiente paso es ingresarlos y guardarlos");
                    activeForm();
                   return;
               }
               activeForm();
               $("#id_tb_inicio").val(data.id_tb_inicio);
               $("#total_consultas_antepasado").val(data.total_consultas_medicas);
               
               $("#bk_1").val(data.CNBKP_1);
               $("#bk_2").val(data.CNBKP_2);
               $("#bk_3").val(data.CNBKP_3);
               $("#bk_4").val(data.CNBKP_4);
               
               $("#exam_1").val(data.SRC_1);
               $("#exam_2").val(data.SRC_2);
               $("#exam_3").val(data.SRC_3);
               $("#exam_4").val(data.SRC_4);
               
               $("#ident_1").val(data.SRId_1);
               $("#ident_2").val(data.SRId_2);
               $("#ident_3").val(data.SRId_3);
               $("#ident_4").val(data.SRId_4);
               
            } else if ($("#act").val() == "G"){
                
                if (data > 0) {
                   $("#id_tb_inicio").val(data); 
                   alert("Datos guardados satisfactoriamente.");
                } else{
                    $("#id_tb_inicio").val("");
                    alert("Error al guardar los datos por favor contacte con el administrador.");
                }
                
            }
            
        } 
    });

    if ($("#total_consultas_antepasado").val()!="")
        {
            tabla_1();
            
            tabla_2();
            
            tabla_3();
            // Calculo de los Programados en CASOS NUEVOS BK POSITIVOS:
            
        }
            
            
}

function tabla1()
{
    // Calculo de los Programados en SINTOMÁTICOS RESPIRATORIOS
            porc_sint_resp= $("#total_consultas_antepasado").val()*$("#porc_sint_resp_pro").val()/100;
            trimestres_sint_resp = Math.round(porc_sint_resp/4);
            $("#sint_ident_1").text(trimestres_sint_resp);
            $("#sint_ident_2").text(trimestres_sint_resp);
            $("#sint_ident_3").text(trimestres_sint_resp);
            $("#sint_ident_4").text(trimestres_sint_resp);
            
            porc_sint_resp_ident_1 = Math.round($("#ident_1").val() * 100 / trimestres_sint_resp);
            porc_sint_resp_ident_2 = Math.round($("#ident_2").val() * 100 / trimestres_sint_resp);
            porc_sint_resp_ident_3 = Math.round($("#ident_3").val() * 100 / trimestres_sint_resp);
            porc_sint_resp_ident_4 = Math.round($("#ident_4").val() * 100 / trimestres_sint_resp);
            
            $("#porc_sint_ident_1").text(porc_sint_resp_ident_1);
            $("#porc_sint_ident_2").text(porc_sint_resp_ident_2);
            $("#porc_sint_ident_3").text(porc_sint_resp_ident_3);
            $("#porc_sint_ident_4").text(porc_sint_resp_ident_4);
}

function tabla2()
{
    // Calculo de la tabla 2
            $("#sint_exam_1").text($("#ident_1").val());
            $("#sint_exam_2").text($("#ident_2").val());
            $("#sint_exam_3").text($("#ident_3").val());
            $("#sint_exam_4").text($("#ident_4").val());
            
            porc_sint_resp_exam_1 = 0;
            porc_sint_resp_exam_2 = 0;
            porc_sint_resp_exam_3 = 0;
            porc_sint_resp_exam_4 = 0;
            
            if ($("#sint_exam_1").val() > 0 )
            porc_sint_resp_exam_1 = Math.round($("#exam_1").val() * 100 / $("#sint_exam_1").val());
            if ($("#sint_exam_2").val() > 0 )
            porc_sint_resp_exam_2 = Math.round($("#exam_2").val() * 100 / $("#sint_exam_2").val());
            if ($("#sint_exam_3").val() > 0 )
            porc_sint_resp_exam_3 = Math.round($("#exam_3").val() * 100 / $("#sint_exam_3").val());
            if ($("#sint_exam_4").val() > 0 )
            porc_sint_resp_exam_4 = Math.round($("#exam_4").val() * 100 / $("#sint_exam_4").val());
            
            $("#porc_sint_ident_1").text(porc_sint_resp_exam_1);
            $("#porc_sint_ident_2").text(porc_sint_resp_exam_2);
            $("#porc_sint_ident_3").text(porc_sint_resp_exam_3);
            $("#porc_sint_ident_4").text(porc_sint_resp_exam_4);
}

function tabla3()
{
    // Calculo de la tabla 2
            porc_casos_nuevos_bk_pos = ($("#total_consultas_antepasado").val()*$("#porc_sint_resp_pro").val()/100) * $("#porc_sint_resp_pro_bk_pl").val() / 100;
            trimestre_caso_nuevo_bk = Math.round(porc_casos_nuevos_bk_pos/4);
            $("#caso_nuevo_bk_1").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_2").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_3").text(trimestre_caso_nuevo_bk);
            $("#caso_nuevo_bk_4").text(trimestre_caso_nuevo_bk);
            
            porc_caso_nuevo_bk_1 = Math.round($("#caso_nuevo_bk_1").val() * 100 / trimestre_caso_nuevo_bk);
            porc_caso_nuevo_bk_2 = Math.round($("#caso_nuevo_bk_2").val() * 100 / trimestre_caso_nuevo_bk);
            porc_caso_nuevo_bk_3 = Math.round($("#caso_nuevo_bk_3").val() * 100 / trimestre_caso_nuevo_bk);
            porc_caso_nuevo_bk_4 = Math.round($("#caso_nuevo_bk_4").val() * 100 / trimestre_caso_nuevo_bk);
            $("porc_caso_nuevo_bk_1").val(porc_caso_nuevo_bk_1);
            $("porc_caso_nuevo_bk_2").val(porc_caso_nuevo_bk_2);
            $("porc_caso_nuevo_bk_3").val(porc_caso_nuevo_bk_3);
            $("porc_caso_nuevo_bk_4").val(porc_caso_nuevo_bk_4);
            
}