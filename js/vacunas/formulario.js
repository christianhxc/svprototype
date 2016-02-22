var globalCondicionesRelacionados = new Array();
var globalDosisRelacionados = new Array();
var globalIdEsqDetalle = 0;

$(function() {
    $( "#fechaVigencia" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+(new Date().getFullYear()+1) ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(document).ready(function() {


    $("#divDetalle").hide();  
    $("#divEsquemasRel").hide();
    $("#botonMultidosis").css( "display", "none" );
    if ($("#idEsquemaForm" ).val() != ""){
        $("#divDetalle").show();
        llenarCondiciones();
        $("#btnDetActualizar").hide();
        traerDetallesRelacionados();
    }
    $("#drpDetTipoEdadIni").val(0);
    $("#drpDetTipoEdadFin").val(0);
});

function llenarCondiciones(){
    if ($("#globalCondicionesRelacionados" ).val() != ""){
        var condiciones = $("#globalCondicionesRelacionados" ).val().split("###");
        for(var i=0; i<condiciones.length;i++){
            var condicion = condiciones[i].split("#-#");
            llenarCondicion(condicion);
        }
        crearTablaCondicion();
    }
}

function llenarCondicion(condicion){
    var idCondicion = condicion[0];
    var nombreCondicion = condicion[1];
    if (idCondicion !=""){
        var tmpReg = globalCondicionesRelacionados.length;
        idCondicion = (tmpReg==0) ? idCondicion : "###"+idCondicion;
        globalCondicionesRelacionados[tmpReg] = new Array(idCondicion,nombreCondicion);
    }
}

function validarRangos(){
    var noAplicaRangos = $("#vacNoRangoEdad" ).is(':checked');
    if (noAplicaRangos){
        $("#botonMultidosis").css( "display", "none" );
        $("#tablaRangos").css( "display", "none" );
    }
    else {
        $("#drpdosis" ).val() == 2 ? $("#botonMultidosis").css( "display", "" ) : $("#botonMultidosis").css( "display", "none" );
        $("#tablaRangos").css( "display", "" );
    }
}


function relacionarCondiciones(){
    var idCondicion = $("#drpCondicion").val();
    var nombreCondicion = $("#drpCondicion").find(":selected").text();
    
    if (idCondicion != 0){
        
        var tmpReg = globalCondicionesRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (idCondicion == globalCondicionesRelacionados[i][0] || "###"+idCondicion == globalCondicionesRelacionados[i][0])
                flag = false;
        }
        if (flag){
            idCondicion = (tmpReg==0) ? idCondicion : "###"+idCondicion;
            globalCondicionesRelacionados[tmpReg] = new Array(idCondicion,nombreCondicion);
            $("#drpCondicion").val(0);
            crearTablaCondicion();
        }
        else{
            alert ("Ya existe un registro para esta relacion");
            $("#drpCondicion").val(0);
        }
    }
    else
        alert("Debe seleccionar una de las condiciones");
}

function crearTablaCondicion(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%" align="center">'+
    '<tr>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Condicion</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '</tr>';
    for(var i=0; i<globalCondicionesRelacionados.length;i++){
        if(__isset(globalCondicionesRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalCondicionesRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarCondiciones('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divTablaCondicion").html(tabla);
}

function eliminarCondiciones(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon la condicion")){
        globalCondicionesRelacionados.splice(pos, 1);
        crearTablaCondicion();
    }
}

function relacionarDosis(){
    var numDosis = $("#numDosis").val();
    var repite = $("#drpRepite").val();
    var correo = $("#drpCorreo").val();
    var IdTipoDeno = 0;
    var tipoDeno = 0;
    var nombreTipoDeno = "";
//    if (tipoDenominador !== 0){
//        var splitTipoDeno = tipoDenominador.split("-");
//        IdTipoDeno = splitTipoDeno[0];
//        tipoDeno = splitTipoDeno[1];
//        nombreTipoDeno = $("#drpDenominador").find(":selected").text();
//    }
    var tipoDosis = $("#drpTipoDosis").val();
    var nombreTipoDosis = $("#drpTipoDosis").find(":selected").text();
    var tiempoIni = $("#detEdadIni").val();
    var tipoTiempoIni = $("#drpDetTipoEdadIni").val();
    var nombreTipoTiempoIni = $("#drpDetTipoEdadIni").find(":selected").text();
    var tiempoFin = $("#detEdadFin").val();
    var tipoTiempoFin = $("#drpDetTipoEdadFin").val();
    var nombreTipoTiempoFin = $("#drpDetTipoEdadFin").find(":selected").text();
    var margenIni = $("#detMargenIni").val();
    var tipoMargenIni = $("#drpTipoMargenIni").val();
    var nombreTipoMargenIni = $("#drpTipoMargenIni").find(":selected").text();
    var margenFin = $("#detMargenFin").val();
    var tipoMargenFin = $("#drpTipoMargenFin").val();
    var nombreTipoMargenFin = $("#drpTipoMargenFin").find(":selected").text();
    
    if (numDosis!== "" && tipoDosis !== 0 && tiempoIni !== "" && tipoTiempoIni !== 0 && margenIni !== "" && tipoMargenIni !== 0){
        
        var tmpReg = globalDosisRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((tipoDosis == globalDosisRelacionados[i][0] || "###"+tipoDosis == globalDosisRelacionados[i][0]) 
                && tiempoIni == globalDosisRelacionados[i][2] && tipoTiempoIni == globalDosisRelacionados[i][3]
                && margenIni == globalDosisRelacionados[i][8] && tipoMargenIni == globalDosisRelacionados[i][9])
                flag = false;
        }
        //if (flag){
            tipoDosis = (tmpReg==0) ? tipoDosis : "###"+tipoDosis;
            if (tipoTiempoFin == 0){
                tipoTiempoFin = "";
                nombreTipoTiempoFin = "";
            }
            if (tipoMargenFin == 0){
                tipoMargenFin = "";
                nombreTipoMargenFin = "";
            }
            globalDosisRelacionados[tmpReg] = new Array(tipoDosis,nombreTipoDosis,tiempoIni,tipoTiempoIni,nombreTipoTiempoIni,
                                                    tiempoFin,tipoTiempoFin,nombreTipoTiempoFin,margenIni,tipoMargenIni,
                                                    nombreTipoMargenIni,margenFin,tipoMargenFin,nombreTipoMargenFin,
                                                    numDosis, repite, correo);
            
            $("#drpTipoDosis").val(0);
            $("#detEdadIni").val("");
            $("#drpDetTipoEdadIni").val(0);
            $("#detEdadFin").val("");
            $("#drpDetTipoEdadFin").val(0);
            $("#detMargenIni").val("");
            $("#drpTipoMargenIni").val(0);
            $("#detMargenFin").val("");
            $("#drpTipoMargenFin").val(0);
            $("#numDosis").val("");
            $("#drpDenominador").val(0);
            $("#drpRepite").val(0);
            $("#drpCorreo").val(0);
            crearTablaDosis();
//        }
//        else{
//            alert ("Ya existe un registro para esta relacion");
//            $("#drpTipoDosis").val(0);
//            $("#detEdadIni").val("");
//            $("#drpDetTipoEdadIni").val(0);
//            $("#detEdadFin").val("");
//            $("#drpDetTipoEdadFin").val(0);
//            $("#detMargenIni").val("");
//            $("#drpTipoMargenIni").val(0);
//            $("#detMargenFin").val("");
//            $("#drpTipoMargenFin").val(0);
//            $("#numDosis").val("");
//            $("#drpDenominador").val(0);
//        }
    }
    else
        alert("Debe ingresar los datos marcados con el asterisco (*)");
}

function crearTablaDosis(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="70%" align="center">'+
    '<tr>'+
    '<th rowspan="2" colspan="2" style="text-align:center" class="dxgvHeader_PlasticBlue">Dosis</th>'+
    '<th colspan="4" style="text-align:center" class="dxgvHeader_PlasticBlue">Rangos de edad</th>'+
    '<th colspan="4" style="text-align:center" class="dxgvHeader_PlasticBlue">Margen de vacunaci&oacute;n</th>'+
    '<th rowspan="2" style="text-align:center" class="dxgvHeader_PlasticBlue">Enviar<br/>Correo</th>'+
    '<th rowspan="2" style="text-align:center" class="dxgvHeader_PlasticBlue">Repite</th>'+
    '<th rowspan="2" style="text-align:center" class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '</tr>'+
    '<tr>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Inicial</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Tipo</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Final</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Tipo</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Inferior</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Tipo</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Superiror</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Tipo</th>'+
    '</tr>';

    for(var i=0; i<globalDosisRelacionados.length;i++){
        if(__isset(globalDosisRelacionados[i])){
            var repite = globalDosisRelacionados[i][15] === "1" ? "Si" : "No";
            var correo = globalDosisRelacionados[i][16] === "1" ? "Si" : "No";
            
            tabla += '<tr>'+
            '<td class="fila" width="10px">'+globalDosisRelacionados[i][14]+'</th>'+
            '<td class="fila" width="80px">'+globalDosisRelacionados[i][1]+'</th>'+
            '<td class="fila" width="30px">'+globalDosisRelacionados[i][2]+'</th>'+
            '<td class="fila" width="80px">'+globalDosisRelacionados[i][4]+'</th>'+
            '<td class="fila" width="30px">'+globalDosisRelacionados[i][5]+'</th>'+
            '<td class="fila" width="80px">'+globalDosisRelacionados[i][7]+'</th>'+
            '<td class="fila" width="30px">'+globalDosisRelacionados[i][8]+'</th>'+
            '<td class="fila" width="80px">'+globalDosisRelacionados[i][10]+'</th>'+
            '<td class="fila" width="30px">'+globalDosisRelacionados[i][11]+'</th>'+
            '<td class="fila" width="80px">'+globalDosisRelacionados[i][13]+'</th>'+
            '<td class="fila" width="10px">'+correo+'</th>'+
            '<td class="fila" width="10px">'+repite+'</th>'+
            '<td class="fila" width="40px" align="center">\n\
                    <a href="javascript:editarDosis('+i+')"><img src="'+urlprefix+'/img/edit.png" title="Editar" border="0"/></a>\n\
                    <a href="javascript:eliminarDosis('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a>\n\
                </th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divDosis").html(tabla);
}

function eliminarDosis(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon esta Dosis")){
        globalDosisRelacionados.splice(pos, 1);
        crearTablaDosis();
    }
}

function editarDosis(pos){
    if (globalDosisRelacionados[pos][0].length > 1)
        globalDosisRelacionados[pos][0] = globalDosisRelacionados[pos][0].substring(3, 4);
    $("#numDosis").val(globalDosisRelacionados[pos][14]);
    $("#drpCorreo").val(globalDosisRelacionados[pos][16]);
    $("#drpRepite").val(globalDosisRelacionados[pos][15]);
    $("#drpTipoDosis").val(globalDosisRelacionados[pos][0]);
    $("#detEdadIni").val(globalDosisRelacionados[pos][2]);
    $("#drpDetTipoEdadIni").val(globalDosisRelacionados[pos][3]);
    $("#detEdadFin").val(globalDosisRelacionados[pos][5]);
    $("#drpDetTipoEdadFin").val(globalDosisRelacionados[pos][6]);
    $("#detMargenIni").val(globalDosisRelacionados[pos][8]);
    $("#drpTipoMargenIni").val(globalDosisRelacionados[pos][9]);
    $("#detMargenFin").val(globalDosisRelacionados[pos][11]);
    $("#drpTipoMargenFin").val(globalDosisRelacionados[pos][12]);    
    globalDosisRelacionados.splice(pos, 1);
    crearTablaDosis();
    
}

function validarVacunas(){
    var Message = '';
    var Errores = '';
    var ErrorEnc='&nbsp; &nbsp;&nbsp; &nbsp;Encabezado:';
    //Encabezado
    if(jQuery.trim($("#vacNombre").val())==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del esquema."; 
    if(jQuery.trim($("#vacCodigo").val())==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el c&oacute;digo del esquema.";
    if($("#drpsexo").val()==="0")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar sexo.";
    var status = 0;
    if ($("#statusSi").is(":checked"))
        status = 1;
    else if ($("#statusNo").is(":checked"))
        status = 1;
    if(status===0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el estado del esquema.";
    if (Errores==="")
        ErrorEnc="";
    else
        ErrorEnc = ErrorEnc+Errores + "<br/>";
    Message = ErrorEnc;
    if(Message!=="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#dSummaryErrors").css('display','none');
        var param = '';
        var i=0;
        for(i=0; i<globalCondicionesRelacionados.length;i++){
            if(__isset(globalCondicionesRelacionados[i])){
                param+=globalCondicionesRelacionados[i][0];//Sintomas y fecha
            }
        }
        $('#globalCondicionesRelacionados').val(param);
        var nuevo = '';
        if($('#action').val()==='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Esquema, \xbfdesea continuar?';
        }
        else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Esquema, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

function grabarDetalle(){
     var idVacunaForm = $("#idEsquemaForm").val();
     funGrabarDetalle(idVacunaForm, "si");
 }
 
 function actDetalle(){
     var idVacunaForm = $("#idEsquemaForm").val();
     funGrabarDetalle(idVacunaForm, "no");
 }
 
 function procesarError(codigo){
    if (codigo === 602)
        alert("La p\xe1gina no responde, intentelo de nuevo mas tarde");
    else if (codigo === 404)
        alert("No se encontr\xf3 la p\xe1gina solicitada");
    else
        alert("hubo un error, codigo: "+codigo);
}

function funGrabarDetalle(idEsquema, nuevo){
    $('#drpVacuna').attr('readonly', false);
    $('#drpVacuna').attr('disabled', '');
    var vacId = $("#drpVacuna").val();
    if (vacId !== "" && vacId !== "0"){
        var dosis = '';
        var i=0;
        
//        for(i=0; i<globalDosisRelacionados.length; i++){
//            if(__isset(globalDosisRelacionados[i])){
//                dosis+=globalDosisRelacionados[i][0]+"#-#"+globalDosisRelacionados[i][2]+"#-#"+
//                       globalDosisRelacionados[i][3]+"#-#"+globalDosisRelacionados[i][5]+"#-#"+
//                       globalDosisRelacionados[i][6]+"#-#"+globalDosisRelacionados[i][8]+"#-#"+
//                       globalDosisRelacionados[i][9]+"#-#"+globalDosisRelacionados[i][11]+"#-#"+
//                       globalDosisRelacionados[i][12]+"#-#"+globalDosisRelacionados[i][14]+"#-#"+
//                       globalDosisRelacionados[i][15]+"#-#"+globalDosisRelacionados[i][16];//Sintomas y fecha
//            }
//        }
        var res = 1;
        if (globalDosisRelacionados.length<1)
            res = confirm("No tienen ninguna dosis relacionada quiere guardar de todas formas?");
        if (res){
            var valores = "idEsquema="+idEsquema+"&vacId="+vacId+"&dosisRelacionadas="+globalDosisRelacionados;
            if (nuevo==="si"){
                pedirAJAX(urlprefix +"vacunas/DetalleGrabar.php", traerDetallesRelacionados, {timeout: 20,
                                                                                            metodo: metodoHTTP.POST,
                                                                                            parametrosPOST: valores,
                                                                                            cartelCargando: "divCargando",
                                                                                            cache: false,
                                                                                            onerror: procesarError});
            }
            else{
                valores += "&idEsqDetalle="+globalIdEsqDetalle;
                pedirAJAX(urlprefix +"vacunas/DetalleActualizar.php", traerDetallesRelacionados, {timeout: 20,
                                                                                                metodo: metodoHTTP.POST,
                                                                                                parametrosPOST: valores,
                                                                                                cartelCargando: "divCargando",
                                                                                                cache: false,
                                                                                                onerror: procesarError});
            }
        }
    }
    else
        alert("Seleccione una vacuna");
}

function traerDetallesRelacionados(){
    var idVacunaForm = $("#idEsquemaForm").val();
    if (idVacunaForm !== ""){
        variablesBlanco();
        var valores = "idVacunaForm="+idVacunaForm;
        pedirAJAX(urlprefix +"vacunas/DetallesRelacionados.php", recibirTraerDetallesRelacionados, {timeout: 20,
                                                                        metodo: metodoHTTP.POST,
                                                                        parametrosPOST: valores,
                                                                        cartelCargando: "divCargando",
                                                                        cache: false,
                                                                        onerror: procesarError});
    }
}

function recibirTraerDetallesRelacionados(texto){
    $("#divEsquemasRel").html(texto);
    $("#divEsquemasRel").show();
}
 
function variablesBlanco(){
    $("#divDosis").html("");
    $("#btnDetActualizar").hide();
    $("#btnDetGrabar").show();
    globalDosisRelacionados = new Array();
    $("#drpVacuna").val(0);
}

function actualizarVacuna(idVacunaForm, vacId ){
    $("#btnDetActualizar").show();
    $("#btnDetGrabar").hide();
    var valores = "idVacunaForm="+idVacunaForm+"&vacId="+vacId;
    pedirAJAX(urlprefix +"vacunas/DatosVacunasRelacionadas.php", recibirDatosVacunasRelacionadas, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cartelCargando: "divCargando",
                                                                    cache: false,
                                                                    onerror: procesarError});
}

function recibirDatosVacunasRelacionadas(texto){
    var array = new Array();
    var arrayDosis = new Array();
    array = texto.split("%%%");
    $("#drpVacuna").val(array[0]);
    $('#drpVacuna').attr('readonly', true);
    $('#drpVacuna').attr('disabled', 'disabled');
    globalIdEsqDetalle = array[1];
    globalDosisRelacionados = new Array();
    for(i=2; i<array.length; i++){
        arrayDosis = array[i].split("#-#");
        arrayDosis[0] = (i-2==0) ? arrayDosis[0] : "###"+arrayDosis[0];
        globalDosisRelacionados[i-2] = new Array(arrayDosis[0],arrayDosis[1],arrayDosis[2],arrayDosis[3],arrayDosis[4],
                                                 arrayDosis[5],arrayDosis[6],arrayDosis[7],arrayDosis[8],arrayDosis[9],
                                                 arrayDosis[10],arrayDosis[11],arrayDosis[12],arrayDosis[13],
                                                 arrayDosis[14],arrayDosis[15],arrayDosis[16]);
    }
    crearTablaDosis();
}

function eliminarVacuna(esquemaId, vacId){
    var res = confirm("Estas seguro que quieres eliminar el registro");
    if (res){
        var valores = "esquemaId="+esquemaId+"&vacId="+vacId;
        pedirAJAX(urlprefix +"vacunas/VacunaEliminar.php", traerDetallesRelacionados, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cartelCargando: "divCargando",
                                                                    cache: false,
                                                                    onerror: procesarError});
    }
}