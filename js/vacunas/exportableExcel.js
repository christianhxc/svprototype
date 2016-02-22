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
    $("#drpNivelPer").val(0);
    $("#drpSexo").val(0);
    $("#drpGenero").val(0);
    $("#drpEtnia").val(0);
    
    $("#drpPro").val(0);
    $("#drpReg").val(0);
    $("#drpDis").val(0);
    $("#drpCor").val(0);
    
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
}


function validarReporte()
{
    var Message = "";
    var Filtro = "";
    var idPro = 0;
    var idReg = 0;
    var idDis = 0;
    var idCor = 0;
    var fechaIni = $("#fecha_ini").val();
    var fechaFin = $("#fecha_fin").val();
    var nivel = $("#drpNivelPer").val();
    var sexo = $("#drpSexo").val();
    if (nivel > 0){
        if (nivel > 1){
            idPro = $("#drpPro").val();
            if (idPro > 0)
                Filtro+= " and pro.id_provincia="+idPro ;
            else
                Message+="<br/> - Por favor seleccione la Provincia";
        }
        if (nivel > 2){
            idReg = $("#drpReg").val();
            if (idReg > 0)
                Filtro+= " and reg.id_region="+idReg ;
            else
                Message+="<br/> - Por favor seleccione la Regi&oacute;n";
        }
        if (nivel > 3){
            idDis = $("#drpDis").val();
            if (idDis > 0)
                Filtro+= " and dis.id_distrito="+idDis ;
            else
                Message+="<br/> - Por favor seleccione el Distrito";
        }
        if (nivel > 4){
            idCor = $("#drpCor").val();
            if (idCor > 0)
                Filtro+= " and cor.id_corregimiento="+idCor ;
            else
                Message+="<br/> - Por favor seleccione el Corregimiento";
        }
    }
    else
        Message+='<br/> - Por favor seleccione un nivel geogr&aacute;fico.';
    
    if (fechaIni=="" || fechaFin=="")
        Message+="<br/> - Debe ingresar las fechas iniciales y finales para tener un rango de fechas";
    if ( validarFechas(fechaFin,fechaIni))
        Message+="<br/> - La fecha inicial debe ser menor a la fecha final";
    if (sexo==0)
        Message+='<br/> - Por favor seleccione un sexo.';
    
    if ($("#drpGenero").val() > 0)
        Filtro+= " and gen.id_genero = "+$("#drpGenero").val();
    if ($("#drpEtnia").val() > 0)
        Filtro+= " and etnia.id_etnia = "+$("#drpEtnia").val();

    if(Message!="")
    {
        $("#errores").show();
        $("#error").html('Imposible generar reporte:' + Message);
    }
    else
    {
        $("#errores").hide();
        $("#error").html('');
        generarReporte(Filtro, fechaIni, fechaFin, sexo);
        //$("#formBuscar").submit();
    }        
}

// Envía parámetros de reporte
function generarReporte(filtro, fechaIni, fechaFin, sexo){
    $("#errores").hide();
    $("#error").html(' ');
    window.open(urlprefix+'reportes/vicits/PuenteReportesExcelVicits.php?f='+filtro+'&fi='+fechaIni+'&ff='+fechaFin+'&s='+sexo
    , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}