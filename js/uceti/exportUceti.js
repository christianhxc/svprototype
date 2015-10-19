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

    setSistema();
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
    
    $( "#nombre_un2" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_id.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#nombre_un2").val($("<div>").html(li.selectValue).text());
            $("#id_un2").val(li.extra[0]);
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

function setSistema(){
    var nivel = $("#drpSistema").val();
    switch(nivel){
        case "1":
            $("#divSisvig").show();
            $("#divRevelac").hide();
            break;
        case "2":
            $("#divSisvig").hide();
            $("#divRevelac").show();
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
    
    $("#semana_ini2").val("");
    $("#semana_fin2").val("");
    $("#anio_ini2").val("");
    $("#anio_fin2").val("");
    destruirTabla(tablaUN);
}


function validarReporte()
{
    var sistema = $("#drpSistema").val();
    var Filtro = "";
    var Message = "";
    if(sistema == 1){
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
        //    if ($("#drpCondicion").val() == 1)
        //        Filtro+= " and cond_condicion_paciente = 1";
        //    
        //    else if ($("#drpCondicion").val() == 2){
        //        Filtro+= " and cond_condicion_paciente = 2";
        //        Condicion = "MORTALIDAD en ";
        //    }
        //    if ($("#drpCaso").val() == 1){
        //        Filtro+= " and cond_vih = 1";
        //        Condicion += "VIH";
        //    }
        //    else if ($("#drpCaso").val() == 2){
        //        Filtro+= " and cond_sida = 1";
        //        Condicion += "SIDA";
        //    }
    
        if ($("#id_un").val() != ""){
            idUn = $("#id_un").val();
            if (idUn > 0){
                //                Filtro+= (nivelUn > 0) ? " and id_provincia="+globalIdPro+" and id_region="+globalIdReg+" and id_distrito="+globalIdDis+" and id_corregimiento="+globalIdCor+" and id_un="+idUn : "" ;
                Filtro+= " and id_un="+idUn   ;
                Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
            }
            else
                Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
        }    
    
        var anioIni = $("#anio_ini").val() != "" ? parseInt($("#anio_ini").val()): "";
        var anioFin = $("#anio_fin").val() != "" ? parseInt($("#anio_fin").val()): new Date().getFullYear();   
        if (anioIni != ""){
            if(anioIni < anioFin)
                Filtro += " and (anio between "+anioIni+" and "+anioFin+") ";
            else
                Message+="<br/> - El a&ntilde;o de inicio debe ser menor que el a&ntilde;o de fin";
        
        }
    
        var semanaIni = $("#semana_ini").val() != "" ? parseInt($("#semana_ini").val()): "";
        var semanaFin = $("#semana_fin").val() != "" ? parseInt($("#semana_fin").val()): "53"; 
        if (semanaIni != ""){
            if(semanaIni < semanaFin)
                Filtro += " and (semana_epi between "+semanaIni+" and "+semanaFin+") ";
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
    }else{
        var anioIni2 = $("#anio_ini2").val() != "" ? parseInt($("#anio_ini2").val()): "";
        var anioFin2 = $("#anio_fin2").val() != "" ? parseInt($("#anio_fin2").val()): new Date().getFullYear();   
        if (anioIni2 != ""){
            if(anioIni2 < anioFin2)
                Filtro += " and (anio between "+anioIni2+" and "+anioFin2+") ";
            else
                Message+="<br/> - El a&ntilde;o de inicio debe ser menor que el a&ntilde;o de fin";
        
        }
    
        var semanaIni2 = $("#semana_ini2").val() != "" ? parseInt($("#semana_ini2").val()): "";
        var semanaFin2 = $("#semana_fin2").val() != "" ? parseInt($("#semana_fin2").val()): "53"; 
        if (semanaIni2 != ""){
            if(semanaIni2 < semanaFin2)
                Filtro += " and (semana_epi between "+semanaIni2+" and "+semanaFin2+") ";
            else
                Message+="<br/> - La semana de inicio debe ser menor que la semana de fin";
        }
        
        if(globalUNRelacionados.length != 0){
            Filtro += " and id_un in(";
            for(i=0; i<globalUNRelacionados.length;i++){
                if(__isset(globalUNRelacionados[i])){
                    idUN = globalUNRelacionados[i][0];
                    if(idUN.indexOf("###") != -1)
                        idUN = idUN.substring(3,idUN.length);
                    if( i < globalUNRelacionados.length-1)
                        Filtro += "'"+idUN+"',";
                    else
                        Filtro += "'"+idUN+"') ";
                }
            }
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
            generarReporteRevelac(Filtro);
        //$("#formBuscar").submit();
        }
    }
}

function generarReporteRevelac(filtro){
    $("#errores").hide();
    $("#error").html(' ');
//    alert (filtro);
    
    window.open(urlprefix+'reportes/uceti/exportable_excel_revelac.php?f='+filtro
        , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

// Envía parámetros de reporte
function generarReporte(filtro, lugar, condicion){
    $("#errores").hide();
    $("#error").html(' ');
    //    alert (filtro + "\n -" + lugar + " \n -" + condicion );
    
    window.open(urlprefix+'reportes/uceti/exportable_excel.php?f='+filtro+'&l='+lugar+'&c='+condicion
        , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

function trim(myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

function relacionarTabla(tabla){
    var tmpReg = 0;
    var flag = true;
    var i=0;
    if(tabla == tablaUN){
        var idUN = $("#id_un2").val();
        var nombreUN = $("#nombre_un2").val();
    
        if (idUN !="" && idUN != 0){
            $("#nombre_un2").val("");
            $("#id_un2").val(0);
            tmpReg = globalUNRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idUN == globalUNRelacionados[i][0] || "###"+idUN == globalUNRelacionados[i][0]))
                    flag = false;
            }
            if (flag){
                idUN = (tmpReg==0) ? idUN : "###"+idUN;
                globalUNRelacionados[tmpReg] = new Array(idUN,nombreUN);
                crearTabla(tablaUN);
            }
            else
                alert ("Ya existe un filtro con este nombre de unidad notificadora");
        }
        else
            alert("Debe seleccionar un filtro de unidad notificadora");
    }
}

function crearTabla(tabla){
    var i=0;
    var html = '';
    if(tabla==tablaUN){
        if(globalUNRelacionados.length == 0)
            $("#"+tablaUN).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Unidad Notificadora</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalUNRelacionados.length;i++){
                if(__isset(globalUNRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="180px">'+globalUNRelacionados[i][1]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaUN,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaUN).html(html);
        }
    }
}

function borrarRelacionTabla(tabla, pos){
    if(tabla == tablaUN){
        if (confirm("Esta seguro de eliminar el filtro "+globalUNRelacionados[pos][1])){
            globalUNRelacionados.splice(pos, 1);
            crearTabla(tablaUN);
        }
    }
}

function destruirTabla(tabla){
    if(tabla == tablaUN){
        globalUNRelacionados = [];
        crearTabla(tablaUN);
    }
}
