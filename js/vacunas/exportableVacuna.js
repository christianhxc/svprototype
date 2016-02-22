var globalUNRelacionados  = new Array();
var tablaUN = "tablaUN";
var pagina = 1;
var partes;
var globalIdPro = 0;
var globalIdReg = 0;
var globalIdDis = 0;
var globalIdCor = 0;

// LOAD
$(document).ready(function() {

    $("#divInstalacion").hide();
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
    
    $( "#nombre_un" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_id.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#nombre_un").val($("<div>").html(li.selectValue).text());
            $("#id_un").val(li.extra[0]);
            globalIdPro = parseInt(li.extra[1]);
            globalIdReg = parseInt(li.extra[2]);
            globalIdDis = parseInt(li.extra[3]);
            globalIdCor = parseInt(li.extra[4]);
        },
        autoFill:false
    });
    
    $( "#fecha_ini" ).datepicker({
        changeYear: true,
        showOn: "both",
        yearRange: "1900:"+new Date().getFullYear() ,
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
    
    $( "#fecha_fin" ).datepicker({
        changeYear: true,
        showOn: "both",
        yearRange: "1900:"+new Date().getFullYear() ,
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
    
});

function setNivelUn(){
    var nivel = $("#drpNivelUn").val();
    switch(nivel){
        case "2":
            $("#divProvincia").show();
            $("#divRegion").hide();
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").hide();
            break;
        case "3":
            $("#divProvincia").show();
            $("#divRegion").show();
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").hide();
            break;
        case "4":
            $("#divProvincia").show();
            $("#divRegion").show();
            $("#divDistrito").show();
            $("#divCorregimiento").hide();
            $("#divInstalacion").hide();
            break;
        case "5":
            $("#divProvincia").show();
            $("#divRegion").show();
            $("#divDistrito").show();
            $("#divCorregimiento").show();
            $("#divInstalacion").hide();
            break;
        case "6":
            $("#divProvincia").hide();
            $("#divRegion").hide();
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").show();
        
            break;
        default:
            $("#divInstalacion").hide();
            $("#divProvincia").hide();
            $("#divRegion").hide();
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            break;
    }
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
    })
}


function borrarFiltro(){

    $("#drpNivelUn").val(0);
    
    $("#drpPro").val(0);
    $("#drpReg").val(0);
    $("#drpDis").val(0);
    $("#drpCor").val(0);
    
    $("#divInstalacion").hide();
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
    
    $("#fecha_ini").val("");
    $("#fecha_fin").val("");
    
    destruirTabla(tablaUN);
}


function validarReporte(){
    var Filtro = "";
    var Message = "";
    var Lugar = "";
    var idPro = 0;
    var idReg = 0;
    var idDis = 0;
    var idCor = 0;
    var idUn = 0;
    var tablaRefPro = "T12.";
    var tablaRefReg = "T11.";
    var tablaRefDis = "T10.";
    var tablaRefCor = "T9.";
    if ($("#drpTipoNivel").val() === '2'){
        tablaRefPro = "T7.";
        tablaRefReg = "T6.";
        tablaRefDis = "T5.";
        tablaRefCor = "T4.";
    }
    var nivelUn = $("#drpNivelUn").val();
    if (nivelUn > 0 ){
        Filtro = "&P_CONDICION=";
        var nivel =  nivelUn;
        if (nivel == 6){
            idUn = $("#id_un").val();
            if (idUn > 0){
                Filtro+= (nivelUn > 0) ? " and "+tablaRefPro+"id_provincia="+globalIdPro+" and "+tablaRefReg+"id_region="+globalIdReg+" and "+tablaRefDis+"id_distrito="+globalIdDis+" and "+tablaRefCor+"id_corregimiento="+globalIdCor+" and T8.id_un="+idUn : "" ;
                Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
            }
            else
                Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
        }
        else {
            if (nivel > 1){
                idPro = $("#drpPro").val();
                if (idPro > 0){
                    Filtro+= " and "+tablaRefPro+"id_provincia="+idPro;
                    Lugar= "Provincia: "+trim($("#drpPro").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione la Provincia";
            }
            if (nivel > 2){
                idReg = $("#drpReg").val();
                if (idReg > 0){
                    Filtro+= " and "+tablaRefReg+"id_region="+idReg;
                    Lugar += " - Region: "+trim($("#drpReg").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione la Regi&oacute;n";
            }
            if (nivel > 3){
                idDis = $("#drpDis").val();
                if (idDis > 0){
                    Filtro+= " and "+tablaRefDis+"id_distrito="+idDis;
                    Lugar += " - Distrito: "+trim($("#drpDis").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione el Distrito";
            }
            if (nivel > 4){
                idCor = $("#drpCor").val();
                if (idCor > 0){
                    Filtro+= " and "+tablaRefCor+"id_corregimiento="+idCor ;
                    Lugar += " - Corregimiento: "+trim($("#drpCor").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione el Corregimiento";
            }
        }
    }
    else
        Message+='<br/> - Por favor seleccione un nivel geogr&aacute;fico.';

//    if ($("#id_un").val() != ""){
//        idUn = $("#id_un").val();
//        if (idUn > 0){
//            //                Filtro+= (nivelUn > 0) ? " and id_provincia="+globalIdPro+" and id_region="+globalIdReg+" and id_distrito="+globalIdDis+" and id_corregimiento="+globalIdCor+" and id_un="+idUn : "" ;
//            Filtro+= " and T8.id_un="+idUn   ;
//            Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
//        }
//        else
//            Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
//    }
    
    var Condicion = "";
    var fechaIni = $("#fecha_ini").val();
    var fechaFin = $("#fecha_fin").val();
    if (fechaIni !== "" && fechaFin !== ""){
        if (validarFechas(fechaIni, fechaFin))
            Condicion = "&P_FECHA_INI_F1="+fechaIni+"&P_FECHA_FIN_F1="+fechaFin;
        else
            Message+="<br/> - La Fecha inicial debe ser menor a la Fecha final";

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
        generarReporte(Filtro, Lugar, Condicion);
    //$("#formBuscar").submit();
    }
}

// Envía parámetros de reporte
function generarReporte(filtro, lugar, condicion){
    $("#errores").hide();
    $("#error").html(' ');   
    
    var stReport = "http://190.34.154.87:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/public/SISVIG/PRD/vacunas/";
    if ($("#drpReporte").val() == "1") 
         stReport += "export_excel_registro_diario";
    if ($("#drpReporte").val() == "2") 
         stReport += "export_excel_registro_diario_1";
//    if ($("#drpReporte").val() == "3") 
//         stReport += "LISTADO_PACIENTES_TB_NO_FORM";
     
    stReport += filtro;
    stReport += condicion;
    stReport += "&j_username=jasURL&j_password=jasURLMinsa&output=xlsx";
    window.open(stReport, 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
    //alert("Reporte: "+stReport);
}

function trim(myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

