var flag_muestra=false;

$(document).ready(function() {
//alert("test");
$("#prueba_muestra").change(function()
    {
           if ($(this).val() != "")
               $("#resultadoFinal").attr("disabled",false)
            else
               $("#resultadoFinal").attr("disabled",true)
           
    });
 
 
 
 $("#tipo_muestra").change(function()
    {
           if ($(this).val() != ""){  
               $("#prueba_muestra").attr("disabled",false)
               $("#lab_proceso").attr("disabled",false)
           }
            else{           
               $("#prueba_muestra").attr("disabled",true)
               $("#lab_proceso").attr("disabled",true)
            }
           
           
    });
 
 
 
$("#resultadoFinal").change(function()
    {
    
    found = false;
    if ($(this).val == 0) {found= true}
    

        $.getJSON( urlprefix + 'js/dynamic/uceti/tipos.php',{
            e: $("#idEvento").val(),
            r: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag_muestra)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#tipo1").html(options);
            $("#tipo2").html(options);
            $("#tipo3").html(options);
            $("#subtipo1").html('<option value="0">Seleccione...</option>');
            $("#subtipo2").html('<option value="0">Seleccione...</option>');
            $("#subtipo3").html('<option value="0">Seleccione...</option>');
        })


    $("#tipo1").attr('disabled', found);
        $("#subtipo1").attr('disabled', found);

        // Deshabilita resultado específico 2
        $("#tipo2").attr('disabled', true);
        $("#subtipo2").attr('disabled', true);
        
        // Deshabilita resultado específico 3
        $("#tipo3").attr('disabled', true);
        $("#subtipo3").attr('disabled', true);
    });
    
     $("#tipo1").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/uceti/subtipos.php',{
            e: $("#idEvento").val(),
            r: $('#resultadoFinal').val(),
            tp: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag_muestra)
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
    });

    $("#tipo2").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/uceti/subtipos.php',{
            e: $("#idEvento").val(),
            r: $('#resultadoFinal').val(),
            tp: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag_muestra)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#subtipo2").html(options);
        })
        // Deshabilita resultado específico 2
        $("#tipo3").attr('disabled', true);
        $("#subtipo3").attr('disabled', true);
    });

    $("#subtipo2").change(function()
    {
        if($(this).val()!=0)
        {
            // Deshabilita resultado específico 2
            $("#tipo3").attr('disabled', false);
            $("#subtipo3").attr('disabled', false);
        }
        else
        {
            // Deshabilita resultado específico 2
            $("#tipo3").attr('disabled', true);
            $("#subtipo3").attr('disabled', true);
        }
    });
    
    $("#tipo3").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/uceti/subtipos.php',{
            e: $("#idEvento").val(),
            r: $('#resultadoFinal').val(),
            tp: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';

            for (var i = 0; i < j.length; i++){
                if(flag_muestra)
                    options += '<option value="' + j[i].optionValue + '" '+ ($("#ir"+fila).val()== j[i].optionValue? 'selected="selected"':'')+'>' + j[i].optionDisplay + '</option>';
                else
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#subtipo3").html(options);
        })
    });
});


function ing_manual_muestra(){

var select = $('#tipo_muestra');
$('option', select).remove();
select.append(new Option("Seleccione...", ""));

    if (globalMuestrasUceti.length >= 1) {
        for(var i=0; i<globalMuestrasUceti.length;i++){
            if(__isset(globalMuestrasUceti[i])){
                var option = new Option(globalMuestrasUceti[i][1], globalMuestrasUceti[i][1]);
                select.append($(option));
            }
         } 
         $("#muestra_manual").css( "display", "" );
         $("#prueba_muestra").attr('disabled', true);
         $("#lab_proceso").attr('disabled', true);
         $("#resultadoFinal").attr('disabled', true);
         
    } else {
        alert ("Necesita ingresar una muestra de laboratorio ");
    }
    
}

function ResetMuestra(){
    $("#muestra_manual").css( "display", "none" );
    $("#tipo_muestra").val("");
    $("#unidad_notif_muestra").val("");
    $("#inicio_sintomas_muestra").val("");
    $("#fecha_toma_muestra").val("");
    $("#fecha_recepcion_muestra").val("");
    $("#prueba_muestra").val("");
    $("#lab_proceso").val("");
    $("#resultadoFinal").val("");
    
    $("#tipo1").val("0");
    $("#subtipo1").val("0");
    $("#tipo1").attr('disabled', true);
    $("#subtipo1").attr('disabled', true);

    // Deshabilita resultado específico 2
    $("#tipo2").val("0");
    $("#subtipo2").val("0");
    $("#tipo2").attr('disabled', true);
    $("#subtipo2").attr('disabled', true);

    // Deshabilita resultado específico 3
    $("#tipo3").val("0");
    $("#subtipo3").val("0");
    $("#tipo3").attr('disabled', true);
    $("#subtipo3").attr('disabled', true);
}

function GrabarMuestra(){
 var msg_errors = ""
    if ($("#prueba_muestra").val() == "") 
        msg_errors = '- Tipo de prueba\n';

    if ($("#lab_proceso").val() == "") 
        msg_errors = '- Laboratorio que proceso la muestra\n';

    if ($("#resultadoFinal").val() == "") 
        msg_errors = '- Resultado final\n';   
    
    if ($("#resultadoFinal").val() == "POSITIVO" && $("#tipo1").val() == "0") 
        msg_errors = '- Resultado específico\n';
    
    if (msg_errors == "") {
        var muestraSplit = new Array();
        var muestrasGuardadas = $("#globalMuestras").val();
        var datos_muestra = new Array();
        var idMuestra;
        var Rsp_idMuestra;
        var tipoSilab = 2;
        
        Rsp_idMuestra = $.ajax({
            type:'POST', 
            async: false,
            url: urlprefix + 'js/dynamic/uceti/idmuestra.php'
        });

        
        $("#unidad_notif_muestra").val($("#notificacion_unidad").val());
        $("#inicio_sintomas_muestra").val($("#fecha_inicio_sintomas").val());
        datos_muestra=jQuery.grep(globalMuestrasUceti, function (muestra) { return muestra[1]==$("#tipo_muestra").val() });
        $("#fecha_toma_muestra").val(datos_muestra[0][2]);
        $("#fecha_recepcion_muestra").val(datos_muestra[0][4]);
        
        var form_muestra = $('#form_muestra').find("select, input, textarea").serializeArray();
        idMuestra = Rsp_idMuestra.response;
        form_muestra.push({
                name: "data[muestra][id_muestra]",
                value: idMuestra
            }); 
        $.ajax({
            type:'POST', 
            url: urlprefix + 'js/dynamic/uceti/muestra_manual.php', 
            data:form_muestra, 
            success: function(response) {
//                alert(response);
                
                var tmpReg = globalMuestras.length;
                var flag = false;
                for (var i=0; i<tmpReg; i++){
                    if(globalMuestras[i][0] == idMuestra && globalMuestras[i][2] == tipoSilab)
                        flag = true;
                }
                if(muestrasGuardadas.search(idMuestra) != -1)
                    flag = true;
                
            if(!flag){
                muestraSplit = response.split("###");
                if(muestraSplit[0]=="no"){
                    alert("Esta Muestra aun no esta procesada en el Laboratorio por favor intentelo mas tarde");
                    $("#dialog-form-silab").dialog('close');
                }
                else{
                
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth()+1; //January is 0!
                    var yyyy = today.getFullYear();
                    if(dd<10){
                        dd='0'+dd
                    }
                    if(mm<10){
                        mm='0'+mm
                    }
                    var d = new Date();
                    var curr_hour = d.getHours();
                    var curr_min = d.getMinutes();

                    today = yyyy+'-'+mm+'-'+dd;
                    $("#resultadosBusquedaSilab").html('');
                    $("#dialog-form-silab").dialog('close');
                    globalMuestras[tmpReg] = new Array(idMuestra,muestraSplit[0],tipoSilab);
                    globalMuestrasSilab[tmpReg] = muestraSplit[1];
                    globalPruebasSilab[tmpReg] = muestraSplit[2];
                    estadoSilab = '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de silab - Actualizada el '+today;
                    $("#estadoSilab").html(estadoSilab);
                    crearMuestras();
                    ResetMuestra();
                }
            }else
                {
                    alert ("Ya existe un registro con la misma muestra");
                }
            }});
    }else{
        alert ("Ingrese los campos: \n" + msg_errors);
    }
    
}

function crearMuestras()
{
    var data = "";
    var tmpReg = globalMuestras.length;
    for (var i=0; i<tmpReg; i++){
        data+= "<span style='color:#628529;font-weight : bold;'>Muestra "+(i+1) + "</span> " + globalMuestras[i][1];
    }
    $("#muestraResultadoSilabTmp").html(data);
    if(tmpReg == 0 && $("#globalMuestras").val() == ""){
        estadoSilab = '<img width=16 height=16 src="../img/iconos/pendiente.png"> Pendiente de muestra';
        $("#estadoSilab").html(estadoSilab);
    }
}