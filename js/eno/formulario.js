
$(function() {
    $( "#enoFechaIni" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown",
        beforeShowDay: enableSUNDAYS,
        firstDay: 1,
        onSelect: function(selected,evnt) {
            calculaSemana(selected);
       }
    });
});

function enableSUNDAYS(date) {
    var day = date.getDay();
    return [(day == 0), ''];
}

$(document).ready(function() {
    var idForm = $("#idEnoForm").val();
    if (idForm === ""){
        $("#divDetalle").hide();
        $("#divEventosGuardados").hide();        
    }
    else
        traerDetallesRelacionados(idForm);
    $("#btnEnoDetActualizar").hide();
    
    $( "#evento" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento").val(li.selectValue);
            $("#eventoNombre").val(li.selectValue);
            $("#eventoId").val(li.extra[0]);
        },
        autoFill:false
    });
    
    $( "#un" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_all.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#unNombre").val(li.selectValue);
            $("#unId").val(li.extra[0]);
            $("#drpPro").val(li.extra[1]);
            setRegionCascada();
            $("#idPro").val(li.extra[1]);
            $("#idReg").val(li.extra[2]);
            $("#idDis").val(li.extra[3]);
            $("#idCor").val(li.extra[4]);
        },
        autoFill:false
    });
});

function sumCasosH(){
    var suma = 0;
    for (var i=1;i<13;i++){
        if ($("#homRango"+i).val()!=="")
            suma = (parseInt(suma)+parseInt($("#homRango"+i).val()));
    }
    $("#homRango13").val(suma)
}

function sumCasosM(){
    var suma = 0;
    for (var i=1;i<13;i++){
        if ($("#mujRango"+i).val()!=="")
            suma = (parseInt(suma)+parseInt($("#mujRango"+i).val()));
    }
    $("#mujRango13").val(suma)
}

function procesarError(codigo){
    if (codigo === 602)
        alert("La p\xe1gina no responde, intentelo de nuevo mas tarde");
    else if (codigo === 404)
        alert("No se encontr\xf3 la p\xe1gina solicitada");
    else
        alert("hubo un error, codigo: "+codigo);
}

//function calculaSemana(fechaCal){
//    
////    
////    var datos = $("#enoFechaIni").val().split("-");
////    if(datos.count!=0)
////        $("#enoFechaIni").val(datos[2]+"-"+datos[1]+"-"+datos[0]);
//    
//    if($("#enoFechaIni").val()!=""){
//        var fechaEpi = $("#enoFechaIni").val().split("/");
//        fechaEpi= fechaEpi[2]+"/"+fechaEpi[1]+"/"+fechaEpi[0];
//        recibirSemanaEpi(calculate(fechaEpi));
//        var fechaEpi = $("#enoFechaIni").val().split("/");
//        $("#enoFechaIni").val(fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
//    }
//    
//    var fecha = $("#enoFechaIni").val().split("/");
//    fecha = fechaEpi[2]+"-"+fechaEpi[1]+"-"+fechaEpi[0];
//    var fecha1 = parseISO8601(fecha);
//    var fecha2 = new Date(fecha1.getTime() + (6 * 24 * 3600 * 1000));
//    var mesCompleto = (Math.abs(fecha2.getMonth()+1)).toString();
//    var diaCompleto = fecha2.getDate().toString();
//    if(mesCompleto.length==1)
//        mesCompleto = "0" + mesCompleto;
//    if(diaCompleto.length==1)
//        diaCompleto = "0" + diaCompleto;
//    $("#enoFechaFin").val(diaCompleto+"/"+ mesCompleto +"/"+fecha2.getFullYear());
//    
//}

function calculaSemana(fechaCal){
    var datos = $("#enoFechaIni").val().split("/");
    var fecha = datos[2]+"-"+datos[1]+"-"+datos[0];
    //alert (fecha);
    var fecha1 = parseISO8601(fecha);
    var fecha2 = new Date(fecha1.getTime() + (6 * 24 * 3600 * 1000));
    var mesCompleto = (Math.abs(fecha2.getMonth()+1)).toString();
    var diaCompleto = fecha2.getDate().toString();
    if(mesCompleto.length==1)
        mesCompleto = "0" + mesCompleto;
    if(diaCompleto.length==1)
        diaCompleto = "0" + diaCompleto;
    $("#enoFechaFin").val(diaCompleto+"/"+ mesCompleto +"/"+fecha2.getFullYear());
    
    var datos = $("#enoFechaIni").val().split("/");
    if(datos.count!=0)
        $("#enoFechaIni").val(datos[0]+"-"+datos[1]+"-"+datos[2]);
    if($("#enoFechaIni").val()!=""){
        var fechaEpi = $("#enoFechaIni").val().split("-");
        var time = fechaEpi[1]+"/"+fechaEpi[0]+"/"+fechaEpi[2];
        $("#enoFechaIni").val( fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
        //alert(calcularSemanaEpi(time));
        var valores = "time="+time;
        pedirAJAX(urlprefix +"libs/semana_epi.php", recibirSemanaEpi, {timeout: 20,
                                                                metodo: metodoHTTP.POST,
                                                                parametrosPOST: valores,
                                                                cartelCargando: "divCargando",
                                                                cache: false,
                                                                onerror: procesarError});
    }
}

function recibirSemanaEpi(val){
    var datos = val.split("###");
    $("#enoSemana").val(datos[0]);
    $("#anioEpi").val(datos[1]);
}

// Parsear fecha a formato adecuado: SEPT 5 00:00:00 2010 CST
function parseISO8601(dateStringInRange){
    var isoExp = /^\s*(\d{4})-(\d\d)-(\d\d)\s*$/,
        date = new Date(NaN), month,
        parts = isoExp.exec(dateStringInRange);

    if(parts) {
      month = +parts[2];
      date.setFullYear(parts[1], month - 1, parts[3]);
      if(month != date.getMonth() + 1) {
        date.setTime(NaN);
      }
    }
    return date;
}

function setRegionCascada(){
    setRegionPersona($("#drpPro").val(),-1);
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

        $("#drpReg").html(options);
        if ($("#idReg").val()!=""){
            $("#drpReg").val($("#idReg").val());
            setDistritoCascada();
        }
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpPro").val(),$("#drpReg").val(),-1);
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

        $("#drpDis").html(options);
        if ($("#idDis").val()!=""){
            $("#drpDis").val($("#idDis").val());
            setCorregimientoCascada();
        }
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDis").val(),-1);
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

        $("#drpCor").html(options);
        if ($("#idCor").val()!=""){
            $("#drpCor").val($("#idCor").val());
        }
    })
}

function validarEno(){
    var Message = '';
    var Errores = '';
    var ErrorEnc='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Encabezado:';
    //Encabezado
    if(jQuery.trim($("#unId").val())==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la instalacion de salud."; 
    if(jQuery.trim($("#drpSer").val())===0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar un servicio."; 
    if(jQuery.trim($("#enoFechaIni").val())==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de inicio de la semana EPI.";
    (Errores==="")? ErrorEnc="": ErrorEnc = ErrorEnc+Errores + "<br/>";
    Message = Errores;
    if(Message!=="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#dSummaryErrors").css('display','none');
        $('#enoSemana').attr('readonly', false);
        $('#enoSemana').attr('disabled', '');
        $('#anioEpi').attr('readonly', false);
        $('#anioEpi').attr('disabled', '');
        $('#enoFechaFin').attr('readonly', false);
        $('#enoFechaFin').attr('disabled', '');
        var nuevo = '';
        if($('#action').val()==='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de ENO, \xbfdesea continuar?';
        }
        else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de ENO, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

 function grabarDetalle(){
     var idEnoForm = $("#idEnoForm").val();
     funGrabarDetalle(idEnoForm, "si");
 }
 
 function actDetalle(){
     var idEnoForm = $("#idEnoForm").val();
     funGrabarDetalle(idEnoForm, "no");
 }

function funGrabarDetalle(enoId, nuevo){
    var eveId = $("#eventoId").val();
    if (eveId !== ""){
        for (var i=1; i< 14; i++ ){
            if ($("#homRango"+i).val()=== "")
                $("#homRango"+i).val("0")
            if ($("#mujRango"+i).val()=== "")
                $("#mujRango"+i).val("0")
        }
        var casosHombre = $("#homRango1").val()+"##"+$("#homRango2").val()+"##"+$("#homRango3").val()+"##"+$("#homRango4").val()+"##"+$("#homRango5").val()+"##"+$("#homRango6").val()+"##"+
            $("#homRango7").val()+"##"+$("#homRango8").val()+"##"+$("#homRango9").val()+"##"+$("#homRango10").val()+"##"+$("#homRango11").val()+"##"+$("#homRango12").val();
        var casosMujer = $("#mujRango1").val()+"##"+$("#mujRango2").val()+"##"+$("#mujRango3").val()+"##"+$("#mujRango4").val()+"##"+$("#mujRango5").val()+"##"+$("#mujRango6").val()+"##"+
            $("#mujRango7").val()+"##"+$("#mujRango8").val()+"##"+$("#mujRango9").val()+"##"+$("#mujRango10").val()+"##"+$("#mujRango11").val()+"##"+$("#mujRango12").val();
        
        var valores = "enoId="+enoId+"&eveId="+eveId+"&casHombre="+casosHombre+"&casMujer="+casosMujer;
        if (nuevo==="si"){
            pedirAJAX(urlprefix +"eno/DetalleGrabar.php", traerDetallesRelacionados, {timeout: 20,
                                                                                        metodo: metodoHTTP.POST,
                                                                                        parametrosPOST: valores,
                                                                                        cartelCargando: "divCargando",
                                                                                        cache: false,
                                                                                        onerror: procesarError});
        }
        else{
            pedirAJAX(urlprefix +"eno/DetalleActualizar.php", traerDetallesRelacionados, {timeout: 20,
                                                                                            metodo: metodoHTTP.POST,
                                                                                            parametrosPOST: valores,
                                                                                            cartelCargando: "divCargando",
                                                                                            cache: false,
                                                                                            onerror: procesarError});
        }
    }
    else
        alert("Seleccione un evento");
}

function traerDetallesRelacionados(enoId){
    if (enoId !== -1){
        variablesBlanco();
        var valores = "enoId="+enoId;
        pedirAJAX(urlprefix +"eno/DetallesRelacionados.php", recibirTraerDetallesRelacionados, {timeout: 20,
                                                                        metodo: metodoHTTP.POST,
                                                                        parametrosPOST: valores,
                                                                        cartelCargando: "divCargando",
                                                                        cache: false,
                                                                        onerror: procesarError});
    }
}

function recibirTraerDetallesRelacionados(texto){
    $("#dataGridInforme").html(texto);
    $("#dataGridInforme").show();
}
 
function variablesBlanco(){
    for (var i=1; i< 14; i++ ){
        $("#homRango"+i).val("")
        $("#mujRango"+i).val("")
     }
    $("#evento").val("");
    $("#eventoNombre").val("");
    $("#eventoId").val("");
    $("#btnEnoDetActualizar").hide();
    $("#btnEnoDetGrabar").show();
}

function actualizarDetalleEno(encId, eveId ){
    $("#btnEnoDetActualizar").show();
    $("#btnEnoDetGrabar").hide();
    var valores = "enoId="+encId+"&eveId="+eveId;
    pedirAJAX(urlprefix +"eno/DatosDetallesRelacionados.php", recibirActDetallesRelacionados, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cartelCargando: "divCargando",
                                                                    cache: false,
                                                                    onerror: procesarError});
}

function recibirActDetallesRelacionados(texto){
    var array = new Array();
    var arrayEvento = new Array();
    array = texto.split("%%%");
    arrayEvento = array[0].split("-#-");
    $("#evento").val(arrayEvento[0]);
    $("#eventoNombre").val(arrayEvento[0]);
    $("#eventoId").val(arrayEvento[1]);
    var array2 = new Array();
    array2 = array[2].split("###");
    $("#mujRango1").val(array2[0]);$("#mujRango2").val(array2[1]);$("#mujRango3").val(array2[2]);$("#mujRango4").val(array2[3]);$("#mujRango5").val(array2[4]);
    $("#mujRango6").val(array2[5]);$("#mujRango7").val(array2[6]);$("#mujRango8").val(array2[7]);$("#mujRango9").val(array2[8]);$("#mujRango10").val(array2[9]);
    $("#mujRango11").val(array2[10]);$("#mujRango12").val(array2[11]);$("#mujRango13").val(array2[12]);
    array2 = array[4].split("###");
    $("#homRango1").val(array2[0]);$("#homRango2").val(array2[1]);$("#homRango3").val(array2[2]);$("#homRango4").val(array2[3]);$("#homRango5").val(array2[4]);
    $("#homRango6").val(array2[5]);$("#homRango7").val(array2[6]);$("#homRango8").val(array2[7]);$("#homRango9").val(array2[8]);$("#homRango10").val(array2[9]);
    $("#homRango11").val(array2[10]);$("#homRango12").val(array2[11]);$("#homRango13").val(array2[12]);
}

function eliminarDetalleENO(encId, eveId){
    var res = confirm("Estas seguro que quieres eliminar el registro");
    if (res){
        var valores = "enoId="+encId+"&eveId="+eveId;
        pedirAJAX(urlprefix +"eno/DetalleEliminar.php", traerDetallesRelacionados, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cartelCargando: "divCargando",
                                                                    cache: false,
                                                                    onerror: procesarError});
    }
}
