var pagina = 1;
var partes;
var globalIdPro = 0;
var globalIdReg = 0;
var globalIdDis = 0;
var globalIdCor = 0;

// LOAD
$(document).ready(function() {

 });

function borrarFiltro(){
  
    $("#drpCaso").val(0);
    $("#drpCondicion").val(0);
    
    $("#anio_ini").val("");
    $("#anio_fin").val("");
}


function validarReporte()
{
    var Message = "";
    var Filtro = "";
           
    var Condicion = "";
    if ($("#drpCondicion").val() == 1)
        Filtro+= " and cond_condicion_paciente = 1";
    
    else if ($("#drpCondicion").val() == 2){
        Filtro+= " and cond_condicion_paciente = 2";
        Condicion = "MORTALIDAD en ";
    }
    if ($("#drpCaso").val() == 1){
        Filtro+= " and cond_vih = 1";
        Condicion += "VIH";
    }
    else if ($("#drpCaso").val() == 2){
        Filtro+= " and cond_sida = 1";
        Condicion += "SIDA";
    }
    
    var anioIni = $("#anio_ini").val() != "" ? parseInt($("#anio_ini").val()): "";
    var anioFin = $("#anio_fin").val() != "" ? parseInt($("#anio_fin").val()): new Date().getFullYear();   
    if (anioIni != ""){
        if(anioIni <= anioFin)
            Filtro += " and (anio between "+anioIni+" and "+anioFin+")";
        else
            Message+="<br/> - El a&ntilde;o de inicio debe ser menor que el a&ntilde;o de fin";
        
    }    

    if(Message!='')
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:' + Message);
    }
    else
    {
        $("#errores").hide();
        $("#error").html('');
        generarReporte(Filtro, Condicion, $("#drpPertenece").val());
        //$("#formBuscar").submit();
    }        
}

// Envía parámetros de reporte
function generarReporte(filtro, condicion, pertenece){
    $("#errores").hide();
    $("#error").html(' ');
    window.open(urlprefix+'reportes/vih/ReporteRegiones.php?p='+pertenece+'&f='+filtro+'&c='+condicion
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

function trim(myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}