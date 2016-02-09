var globalUNRelacionados  = new Array();
var tablaUN = "tablaUN";
var pagina = 1;
var partes;
var globalIdPro = 0;
var globalIdReg = 0;
var globalIdDis = 0;
var globalIdCor = 0;


$(function() {
    $( "#anio_ini" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#anio_fin" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#anio_ini_f1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#anio_fin_f1" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

// LOAD
$(document).ready(function() {

    setSistema();
    $("#divInstalacion").hide();
    $("#divProvincia").hide();
    $("#divRegion").hide();
    $("#divDistrito").hide();
    $("#divCorregimiento").hide();
    $("#anio_listado_tb").hide();
    
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
            globaldrpPro = parseInt(li.extra[1]);
            $("#drpPro").val(parseInt(li.extra[1]));
            globalIdReg = parseInt(li.extra[2]);
            setRegionCascada();
            $("#drpReg").val(parseInt(li.extra[2]));
            globalIdDis = parseInt(li.extra[3]);
            globalIdCor = parseInt(li.extra[4]);
        },
        autoFill:false
    });
    
   $("#drpReporte").change(function(){
       var x = document.getElementById("drpNivelUn");
       if ($(this).val() == "4"){
           $("#divSisvig").hide();
           
       }else if($(this).val() == "3"){
            if(x[x.length-1].value == "6"){
                x.remove(x.length-1)
            }
           $("#divSisvig").show(); 
           $("#fh_fase1").hide();
           $("#anio_listado_tb").show();
       }else
           {
           if(x[x.length-1].value != "6"){
                var elOptNew = document.createElement('option');
                elOptNew.text = 'Unidad notificadora';
                elOptNew.value = '6';  
                try {
                x.add(elOptNew, null); // standards compliant; doesn't work in IE
                }
                catch(ex) {
                x.add(elOptNew); // IE only
                }
            }
            $("#divSisvig").show(); 
            $("#fh_fase1").show();
            $('#anio_listado_tb').hide();
           }
   });
    
//    $( "#nombre_un2" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_id.php",
//    {
//        delay:10,
//        minChars:3,
//        matchSubset:1,
//        matchContains:1,
//        cacheLength:10,
//        onItemSelect:function(li){
//            $("#nombre_un2").val($("<div>").html(li.selectValue).text());
//            $("#id_un2").val(li.extra[0]);
//        },
//        autoFill:false
//    });
});

function setNivelUn(){
    var nivel = $("#drpNivelUn").val();
    switch(nivel){
        case "2":
            $("#divProvincia").show();
            $("#divRegion").hide();
            $("#drpReg").val("");
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").hide();
            $("#id_un").val("");
            $("#nombre_un").val("");
            
            break;
        case "3":
            $("#divProvincia").show();
            $("#divRegion").show();
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").hide();
            $("#id_un").val("");
            $("#nombre_un").val("");
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
            $("#divProvincia").val("");
            $("#divRegion").hide();
            $("#drpReg").val("");
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#divInstalacion").show();
            $("#id_un").val("");
            $("#nombre_un").val("");
        
            break;
        default:
            $("#divInstalacion").hide();
            $("#divProvincia").hide();
            $("#divProvincia").val("");
            $("#divRegion").hide();
            $("#drpReg").val("");
            $("#divDistrito").hide();
            $("#divCorregimiento").hide();
            $("#id_un").val("");
            $("#nombre_un").val("");
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
     $("#anio_listado").val("");
    destruirTabla(tablaUN);
}


function validarReporte()
{
    var sistema = $("#drpSistema").val();
    var Filtro = "";
    var Message = "";
    
    if ($("#drpReporte").val()=="")
        Message+="<br/> - Por favor seleccione el reporte a generar";
    
    if ($("#drpReporte").val()!="4"){
        
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
//                    Filtro+= (nivelUn > 0) ? " and id_provincia="+globalIdPro+" and id_region="+globalIdReg+" and id_distrito="+globalIdDis+" and id_corregimiento="+globalIdCor+" and id_un="+idUn : "" ;
                    Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
                }
                else
                    Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
            }
            else {
                if (nivel > 1){
                    idPro = $("#drpPro").val();
                    if (idPro > 0){
                        Filtro+= "&P_ID_PROVINCIA="+idPro;
                        Lugar= "Provincia: "+trim($("#drpPro").find(":selected").text());
                    }
                    else
                        Message+="<br/> - Por favor seleccione la Provincia";
                }
                if (nivel > 2){
                    idReg = $("#drpReg").val();
                    if (idReg > 0){
                        Filtro+= "&P_ID_REGION="+idReg;
                        Lugar += " - Region: "+trim($("#drpReg").find(":selected").text());
                    }
                    else
                        Message+="<br/> - Por favor seleccione la Regi&oacute;n";
                }
                if (nivel > 3){
                    idDis = $("#drpDis").val();
                    if (idDis > 0){
                        Filtro+= "&P_ID_DISTRITO="+idDis;
                        Lugar += " - Distrito: "+trim($("#drpDis").find(":selected").text());
                    }
                    else
                        Message+="<br/> - Por favor seleccione el Distrito";
                }
                if (nivel > 4){
                    idCor = $("#drpCor").val();
                    if (idCor > 0){
                        Filtro+= "&P_ID_CORREGIMIENTO="+idCor ;
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
    
        if ($("#id_un").val() != ""){
            idUn = $("#id_un").val();
            if (idUn > 0){
                //                Filtro+= (nivelUn > 0) ? " and id_provincia="+globalIdPro+" and id_region="+globalIdReg+" and id_distrito="+globalIdDis+" and id_corregimiento="+globalIdCor+" and id_un="+idUn : "" ;
//            if ($("#drpReporte").val() == "1") 
//                Filtro+= "&P_ID_UN_X_REGION="+idUn;
//
//            if ($("#drpReporte").val() == "2") 
                Filtro+= "&P_ID_UN="+idUn;
                //Filtro+= "&P_ID_UN="+idUn;
                 Lugar = "Unidad notificadora: "+trim($("#nombre_un").val());
            }
            else
                Message+="<br/> - Por favor seleccione la instalaci&oacute;n de salud";
        }    
    
//        var anioIni = $("#anio_ini").val() ;
//        var anioFin = $("#anio_fin").val();   
//        if (anioIni != ""){
//            if(anioIni < anioFin){
//                Filtro += "&P_FECHA_INI_NOTIFICACION="+anioIni;
//                
//                if (anioFin != "") 
//                    Filtro += "&P_FECHA_FIN_NOTIFICACION="+anioFin;
//            }
//            else
//                Message+="<br/> - El a&ntilde;o de inicio debe ser menor que el a&ntilde;o de fin";
//        }
        
    

        var anioIniF1 = $("#anio_ini_f1").val();
        var anioFinF1 = $("#anio_fin_f1").val();   
        
        if (anioIniF1 != ""){
            if(anioIniF1 < anioFinF1){
                Filtro += "&P_FECHA_INI_F1="+anioIniF1;
                if (anioFinF1 != "") 
                    Filtro +="&P_FECHA_FIN_F1="+anioFinF1;
            }
            else
                Message+="<br/> - El a&ntilde;o de inicio debe ser menor que el a&ntilde;o de fin";
        }else if($("#drpReporte").val()=="3"){
            
        }else{
            Message+="<br/> - Debe ingresar la fecha de inicio de la Fase 1";
        }
        
        if ($("#anio_listado").val()!="" && $("#drpReporte").val()=="3" ){

                Filtro += "&P_ANIO="+$("#anio_listado").val();
            }
        else{
            Message+="<br/> - Debe ingresar el año que necesita el reporte";
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
            generarReporte(Filtro, Lugar, Condicion);
        //$("#formBuscar").submit();
        }
    
}

function generarReporteRevelac(filtro){
    $("#errores").hide();
    $("#error").html(' ');
//    alert (filtro);
    
    window.open(urlprefix+'reportes/tb/exportable_excel_revelac.php?f='+filtro
        , 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
}

// Envía parámetros de reporte
function generarReporte(filtro, lugar, condicion){
    $("#errores").hide();
    $("#error").html(' ');
//        alert (filtro + "\n -" + lugar + " \n -" + condicion );

    if ($("#drpReporte").val() == "4"){
    
     stReport = "http://190.34.154.87/sisvig2/reportes/tb/Indicadores_TB_SISVIG.xls"
    
    }else{
   
    stReport = "http://190.34.154.87:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/public/SISVIG/PRD/Tuberculosis/";
    if ($("#drpReporte").val() == "1") 
         stReport += "exportar_tb_excel";
    if ($("#drpReporte").val() == "2") 
         stReport += "consolidado_sexo_edad";
    if ($("#drpReporte").val() == "3") 
         stReport += "LISTADO_PACIENTES_TB_NO_FORM";
    if ($("#drpReporte").val() == "5") 
         stReport += "exportar_tb_excel_encabezado";
     if ($("#drpReporte").val() == "6") 
         stReport += "consolidado_tipo_region";
     if ($("#drpReporte").val() == "7") 
         stReport += "AnualOMS";
     if ($("#drpReporte").val() == "8") 
         stReport += "Cohortes";
     
    stReport += filtro;
    stReport += "&j_username=jasURL&j_password=jasURLMinsa&output=xlsx";
    
    }
//    alert(stReport);
    window.open(stReport, 'Reporte',"toolbar=yes, status=yes, status=yes, scrollbars=yes, resizable=yes, menubar=yes, width=400, height=400");
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
