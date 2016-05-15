var pagina = 1;
var partes;
var globalIdPro = 0;
var globalIdReg = 0;
var globalIdDis = 0;
var globalIdCor = 0;

$(function() {
    $( "#fecha_ini" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fecha_fin" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
// LOAD
$(document).ready(function() {

    $("#divNivelUn").hide();
    $("#divNivelPer").hide();
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
            $("#nombre_un").val(li.selectValue);
            $("#id_un").val(li.extra[0]);
            globalIdPro = parseInt(li.extra[1]);
            globalIdReg = parseInt(li.extra[2]);
            globalIdDis = parseInt(li.extra[3]);
            globalIdCor = parseInt(li.extra[4]);
        },
        autoFill:false
    });
});

function setPertenece(){
    var nivel = $("#drpPertenece").val();
    borrarFiltro();
    $("#drpPertenece").val(nivel);    
    if (nivel == 1){
        $("#divNivelUn").show();
        $("#divNivelPer").hide();
    }
    else if (nivel == 2){
        $("#divNivelUn").hide();
        $("#divNivelPer").show();
    }
    else if (nivel == 3){
        $("#divNivelUn").hide();
        $("#divNivelPer").show();
    }
    else{
        $("#divNivelUn").hide();
        $("#divNivelPer").hide();
    }
}

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

function setNivelPer(){
    var nivel = $("#drpNivelPer").val();
    switch(nivel){
    case "2":
        $("#divProvincia").show();
        $("#divRegion").hide();
        $("#divDistrito").hide();
        $("#divCorregimiento").hide();
    break;
    case "3":
        $("#divProvincia").show();
        $("#divRegion").show();
        $("#divDistrito").hide();
        $("#divCorregimiento").hide();
    break;
    case "4":
        $("#divProvincia").show();
        $("#divRegion").show();
        $("#divDistrito").show();
        $("#divCorregimiento").hide();
    break;
    case "5":
        $("#divProvincia").show();
        $("#divRegion").show();
        $("#divDistrito").show();
        $("#divCorregimiento").show();
    break;
    default:
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


function borrarFiltro()
{
    $("#drpPertenece").val(0);
    $("#drpNivelUn").val(0);
    $("#drpNivelPer").val(0);
    $("#drpCaso").val(0);
    $("#drpSexo").val(0);
    $("#drpGenero").val(0);
    $("#drpEtnia").val(0);
    
    $("#drpPro").val(0);
    $("#drpReg").val(0);
    $("#drpDis").val(0);
    $("#drpCor").val(0);
    
    $("#divNivelUn").hide();
    $("#divNivelPer").hide();
    $("#divInstalacion").hide();
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
}


function validarReporte()
{
    var Message = "";
    var Filtro = "";
    var pertenece = $("#drpPertenece").val();
    var idPro = 0;
    var idReg = 0;
    var idDis = 0;
    var idCor = 0;
    var idUn = 0;
    var fechaIni = $("#fecha_ini").val();
    var fechaFin = $("#fecha_fin").val();
    
    if (pertenece > 0){
        var nivelUn = $("#drpNivelUn").val();
        var nivelPer = $("#drpNivelPer").val();
        var geoSuffix = pertenece == 3 ? '_diag' : '';
        if (nivelUn > 0 || nivelPer > 0){
            var nivel =  (nivelUn > 0) ? nivelUn:nivelPer;
            if (nivel == 6){
                idUn = $("#id_un").val();
                if (idUn > 0)
                    Filtro+= (nivelUn > 0) ? " and pro_un.id_provincia="+globalIdPro+" and reg_un.id_region="+globalIdReg+" and dis_un.id_distrito="+globalIdDis+" and cor_un.id_corregimiento="+globalIdCor+" and un.id_un="+idUn : "" ;
                else
                    Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
            }
            else {
                if (nivel > 1){
                    idPro = $("#drpPro").val();
                    if (idPro > 0)
                        Filtro+= (nivelUn > 0) ? " and pro_un.id_provincia="+idPro : " and pro"+geoSuffix+".id_provincia="+idPro ;
                    else
                        Message+="<br/> - Por favor seleccione la Provincia";
                }
                if (nivel > 2){
                    idReg = $("#drpReg").val();
                    if (idReg > 0)
                        Filtro+= (nivelUn > 0) ? " and reg_un.id_region="+idReg : " and reg"+geoSuffix+".id_region="+idReg ;
                    else
                        Message+="<br/> - Por favor seleccione la Regi&oacute;n";
                }
                if (nivel > 3){
                    idDis = $("#drpDis").val();
                    if (idDis > 0)
                        Filtro+= (nivelUn > 0) ? " and dis_un.id_distrito="+idDis : " and dis"+geoSuffix+".id_distrito="+idDis ;
                    else
                        Message+="<br/> - Por favor seleccione el Distrito";
                }
                if (nivel > 4){
                    idCor = $("#drpCor").val();
                    if (idCor > 0)
                        Filtro+= (nivelUn > 0) ? " and cor_un.id_corregimiento="+idCor : " and cor"+geoSuffix+".id_corregimiento="+idCor ;
                    else
                        Message+="<br/> - Por favor seleccione el Corregimiento";
                }
            }
        }
        else
            Message+='<br/> - Por favor seleccione un nivel geogr&aacute;fico.';
    }
    if (fechaIni=="" || fechaFin=="")
        Message+="<br/> - Debe ingresar las fechas iniciales y finales para tener un rango de fechas";
    if ( validarFechas(fechaFin,fechaIni))
        Message+="<br/> - La fecha inicial debe ser menor a la fecha final";
    
    if ($("#drpCaso").val() == 1)
        Filtro+= " and cond_vih = 1"
    if ($("#drpCaso").val() == 2)
        Filtro+= " and cond_sida = 1"
    if ($("#drpSexo").val() == "M")
        Filtro+= " and per.sexo = 'M'"
    if ($("#drpSexo").val() == "F")
        Filtro+= " and per.sexo = 'F'"
    if ($("#drpGenero").val() > 0)
        Filtro+= " and gen.id_genero = "+$("#drpGenero").val();
    if ($("#drpEtnia").val() > 0)
        Filtro+= " and etnia.id_etnia = "+$("#drpEtnia").val();
    if ($("#drpCondicion").val() == 1)
        Filtro+= " and cond_condicion_paciente = 1";
    if ($("#drpCondicion").val() == 2)
        Filtro+= " and cond_condicion_paciente = 2";

    if(Message!='')
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:' + Message);
    }
    else
    {
        $("#errores").hide();
        $("#error").html('');
        generarReporte(Filtro, fechaIni, fechaFin);
        //$("#formBuscar").submit();
    }        
}

// Envía parámetros de reporte
function generarReporte(filtro, fechaIni, fechaFin){
    $("#errores").hide();
    $("#error").html(' ');
    window.open(urlprefix+'reportes/vih/ReporteExportableVih.php?f='+filtro+'&fi='+fechaIni+'&ff='+fechaFin
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}