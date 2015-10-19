var idSeleccionados = new Array();
var globalOrden ="";
var globalPagina = 1;
var globalFlagOrden = "";

$(document).ready(function() {
    $("#divTablaContactos").hide();
    $("#divInfoRel").hide();
    $("#divErrorRel").hide();
});

function traerTabla(){
    $("#divInfoRel").hide();
    $("#divErrorRel").hide();
    idSeleccionados = new Array();
    var Message = "";
    globalPagina = 1;
    if (jQuery.trim($("#drpSelGrupo").val()) == 0){
        Message += "- "+faltaGrupoAcc+"\n";
    }
    if(Message==""){
        var valores = "idGrupo="+$("#drpSelGrupo").val();
        pedirAJAX(urlprefix+"js/dynamic/mat/listadoRelacionCheck.php", recibirTraerTabla, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cache: false});
    }
    else
        alert(Message);

}

function __ordenando(texto){
//    $("#dSummaryInfo").attr("style", "display:none");
//    $("#dSummaryErrors").attr("style", "display:none");
//    idSeleccionados = new Array();
//    if (texto!=""&&texto!="Seleccione"){
//        if (globalFlagOrden=="asc")
//            globalFlagOrden = "desc";
//        else
//            globalFlagOrden = "asc";
//        globalOrden = texto+" "+globalFlagOrden;
//        var valores = "secTipo="+$("#perTipo").val()+"&orden="+globalOrden+"&pagina="+globalPagina+"&accTipo="+$("#perAccion").val()+"&gruId="+$("#usuGrupo").val();
//        pedirAJAX(urlprefix+"js/dynamic/permiso/listadoSeccionesCheck.php", recibirTraerTabla, {timeout: 20,
//                                                                    metodo: metodoHTTP.POST,
//                                                                    parametrosPOST: valores,
//                                                                    cache: false});
//    }
}

function refrescarResultados(pag){
    $("#divInfoRel").hide();
    $("#divErrorRel").hide();
    idSeleccionados = new Array();
    if (pag>='1'){
        globalPagina = pag;
        var valores = "pagina="+pag+"&idGrupo="+$("#drpSelGrupo").val();
        pedirAJAX(urlprefix+"js/dynamic/permiso/mat/listadoRelacionCheck.php", recibirTraerTabla, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cache: false});
    }
}

function recibirTraerTabla(texto){
    $("#divTablaContactos").show();
    $("#divTablaContactos").html(texto);
}

function checkboxs(id){
    if (__isset(idSeleccionados[id])){
        if (idSeleccionados[id]!=0)
            idSeleccionados[id] = 0;
        else
            idSeleccionados[id] = 1;
    }
    else{
        idSeleccionados[id] = 1;
    }
}

function checkboxsUpdate(id){
    if (__isset(idSeleccionados[id])){
        if (idSeleccionados[id]!=0)
            idSeleccionados[id] = 0;
        else
            idSeleccionados[id] = 2;
    }
    else{
        idSeleccionados[id] = 2;
    }
}

function guardarRelacion(){
    var valores = "gruId="+$("#drpSelGrupo").val()+"&idSeleccionados="+idSeleccionados;
    pedirAJAX(urlprefix+"js/dynamic/mat/relacionarContactos.php", recibirGuardarContactos, {timeout: 20,
                                                                metodo: metodoHTTP.POST,
                                                                parametrosPOST: valores,
                                                                cache: false});
}

function recibirGuardarContactos(texto){
    if (texto==0&&texto==""){
        $("#divErrorRel").show();
        $("#divInfoRel").hide();
        $("#labelInfoRel").html('');
        $("#labelErrorRel").html("No se pudo guardar la relacion del contacto y grupo");
    }
    else{
        $("#divInfoRel").show();
        $("#divErrorRel").hide();
        $("#labelErrorRel").html('');
        $("#labelInfoRel").html("Se guardo correctamente la relacion del contato y grupo");
    }
}

function cancelarRelacion(){
    $("#divTablaContactos").hide();
    $("#divTablaContactos").html('');
    $("#drpSelGrupo").val(0);
}