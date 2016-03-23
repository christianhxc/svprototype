var pagina = 1; 

$(document).ready(function() {
    borrarTabla();
    setTipoId();
    busquedaRegistro();
});

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        busquedaRegistro();
        //validarEsquemas();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaRegistro()
{
    $("#resultadosBusqueda").html("");
    $("#pErrors").hide();
    var filtro = "";
    var error = "";
    var idPro = $("#drpProIndividuo").val();
    var idReg = $("#drpRegIndividuo").val();
    var idDis = $("#drpDisIndividuo").val();
    var idCor = $("#drpCorIndividuo").val();
    var idTipoId = $("#drpTipoId").val();
    var numId = (idTipoId === "1")? jQuery.trim($("#no_identificador1").val())+"-"+jQuery.trim($("#no_identificador2").val())+"-"+jQuery.trim($("#no_identificador3").val()) : jQuery.trim($("#no_identificador").val());
    var nombre1 = jQuery.trim($("#primer_nombre").val());
    var nombre2 = jQuery.trim($("#segundo_nombre").val());
    var apellido1 = jQuery.trim($("#primer_apellido").val());
    var apellido2 = jQuery.trim($("#segundo_apellido").val());
    var tipoEdad = $("#drpTipoEdad").val();
    var edad = jQuery.trim($("#edad").val());
    var sexo = $("#drpSexo").val();
    filtro = (idPro !== "0") ? "&idPro="+idPro : "";
    filtro = (idReg !== "0") ? "&idReg="+idReg : "";
    filtro = (idDis !== "0") ? "&idDis="+idDis : "";
    filtro = (idCor !== "0") ? "&idCor="+idCor : "";
    filtro += (nombre1 !== "") ? "&nombre1="+nombre1 : "";
    filtro += (nombre2 !== "") ? "&nombre2="+nombre2 : "";
    filtro += (apellido1 !== "") ? "&apellido1="+apellido1 : "";
    filtro += (apellido2 !== "") ? "&apellido2="+apellido2 : "";
    filtro += (sexo !== "0") ? "&sexo="+sexo : "";
    if (numId !== ""){
        if (idTipoId === "0")
            error = "- Seleccione el tipo de identificaci&oacute;n" ;
        else 
            filtro += "&tipoId="+idTipoId+"&numId="+numId;
    }
    if (edad !== ""){
        if (tipoEdad === "0")
            error += "<br/> - Seleccione el tipo de Edad" ;
        else 
            filtro += "&tipoEdad="+tipoEdad+"&edad="+edad;
    }
    
    if (error === ""){
        $.ajax({
            type: "POST",
            url: urlprefix + 'js/dynamic/vacunas/busquedaRegistro.php',
            data: filtro+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
            success: function(data)
            {
                $("#resultadosBusqueda").html(data);
            }
        });
    }
    else {
        $("#resultadosBusqueda").html("");
        $("#pErrors").html(error);
        $("#pErrors").show();
    }
}

function borrarRegistro(idForm){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Registro N. '+idForm+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('registroDiario.php?action=D&idForm=' + idForm );
    }
}

function reporteIndividual(idForm){
    var mensaje = 'A continuaci\xf3n se mostrara el Registro Diario de Vacunacion N. '+idForm+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.open('http://190.34.154.83:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/public/SISVIG/DEV/vacunas/vacunas_formuario_registro_diario&P_ID_FORMULARIO='+idForm+'&j_username=jasURL&j_password=jasURLMinsa&output=pdf');
    }
}

function setTipoId(){
    var tipo = $("#drpTipoId").val();
    if (tipo !== "1"){
        $("#divCedula1").show();
        $("#divCedula2").hide();
    }
    else{
        $("#divCedula1").hide();
        $("#divCedula2").show();
    }
}

function setRegionCascada(){
    var tipo = 0;
    var region = tipo == 0 ? $("#drpProIndividuo").val():$("#drpProConta").val();
    setRegionPersona(region,-1, tipo);
}

function setRegionPersona(idProvincia, idRegion, tipo)
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
        tipo == 0 ? $("#drpRegIndividuo").html(options):$("#drpRegConta").html(options);
    })
}

function setDistritoCascada(tipo){
    var region = tipo == 0 ? $("#drpRegIndividuo").val():$("#drpRegConta").val();
    var provincia = tipo == 0 ? $("#drpProIndividuo").val():$("#drpProConta").val();
    setDistritoPersona(provincia, region, -1, tipo);
}

function setDistritoPersona(idProvincia, idRegion, idDistrito, tipo)
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
        tipo == 0 ? $("#drpDisIndividuo").html(options):$("#drpDisConta").html(options);
    })
}

function setCorregimientoCascada(tipo){
    var distrito = tipo == 0 ? $("#drpDisIndividuo").val():$("#drpDisConta").val();
    setCorregimientoPersona(distrito, -1, tipo);
}

function setCorregimientoPersona(idDistrito, idCorregimiento, tipo)
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
        tipo == 0 ? $("#drpCorIndividuo").html(options):$("#drpCorConta").html(options);
    })
}

function borrarFiltro(){
    $("#resultadosBusqueda").html("");
    $("#pErrors").hide();
    $("#pErrors").val("");
    $("#drpProIndividuo").val(0);
    $("#drpRegIndividuo").val(0);
    $("#drpDisIndividuo").val(0);
    $("#drpCorIndividuo").val(0);
    $("#drpTipoId").val(0);
    $("#no_identificador1").val("");
    $("#no_identificador2").val("");
    $("#no_identificador3").val("");
    $("#no_identificador").val("");
    $("#primer_nombre").val("");
    $("#segundo_nombre").val("");
    $("#primer_apellido").val("");
    $("#segundo_apellido").val("");
    $("#drpTipoEdad").val(0);
    $("#edad").val("");
    $("#drpSexo").val(0);
    $("#divCedula1").show();
    $("#divCedula2").hide();
}