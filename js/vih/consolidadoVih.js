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
    $("#drpCaso").val(0);
    $("#drpCondicion").val(0);
    
    $("#drpPro").val(0);
    $("#drpReg").val(0);
    $("#drpDis").val(0);
    $("#drpCor").val(0);
    
    $("#divInstalacion").hide();
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
    
    $("#semana_ini").val("");
    $("#semana_fin").val("");
    $("#anio_ini").val("");
    $("#anio_fin").val("");
}


function validarReporte()
{
    var Message = "";
    var Filtro = "";
    var Lugar = "";
    var idPro = 0;
    var idReg = 0;
    var idDis = 0;
    var idCor = 0;
    var idUn = 0;
    
    var nivelUn = $("#drpNivelUn").val();
    if (nivelUn > 0 ){
        var nivel =  nivelUn;
        if (nivel == 6){
            idUn = $("#id_un").val();
            if (idUn > 0){
                Filtro+= (nivelUn > 0) ? " and id_provincia="+globalIdPro+" and id_region="+globalIdReg+" and id_distrito="+globalIdDis+" and id_corregimiento="+globalIdCor+" and id_un="+idUn : "" ;
                Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
            }
            else
                Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
        }
        else {
            if (nivel > 1){
                idPro = $("#drpPro").val();
                if (idPro > 0){
                    Filtro+= " and id_provincia="+idPro;
                    Lugar= "Provincia: "+trim($("#drpPro").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione la Provincia";
            }
            if (nivel > 2){
                idReg = $("#drpReg").val();
                if (idReg > 0){
                    Filtro+= " and id_region="+idReg;
                    Lugar += " - Region: "+trim($("#drpReg").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione la Regi&oacute;n";
            }
            if (nivel > 3){
                idDis = $("#drpDis").val();
                if (idDis > 0){
                    Filtro+= " and id_distrito="+idDis;
                    Lugar += " - Distrito: "+trim($("#drpDis").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione el Distrito";
            }
            if (nivel > 4){
                idCor = $("#drpCor").val();
                if (idCor > 0){
                    Filtro+= " and id_corregimiento="+idCor ;
                    Lugar += " - Corregimiento: "+trim($("#drpCor").find(":selected").text());
                }
                else
                    Message+="<br/> - Por favor seleccione el Corregimiento";
            }
        }
    }
    else
        Message+='<br/> - Por favor seleccione un nivel geogr&aacute;fico.';
    
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
    
    var semanaIni = $("#semana_ini").val() != "" ? parseInt($("#semana_ini").val()): "";
    var semanaFin = $("#semana_fin").val() != "" ? parseInt($("#semana_fin").val()): "53"; 
    if (semanaIni != ""){
        if(semanaIni <= semanaFin)
            Filtro += " and (semana_epi between "+semanaIni+" and "+semanaFin+")";
        else
            Message+="<br/> - La semana de inicio debe ser menor que la semana de fin";
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
    window.open(urlprefix+'reportes/vih/ReporteConsolidado.php?f='+filtro+'&l='+lugar+'&c='+condicion
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

function trim(myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}