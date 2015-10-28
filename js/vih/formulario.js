var globalFactorRiesgoRelacionados = new Array();
var globalEnfOportunistaRelacionados = new Array();
var globalMuestras = new Array();

var globalMuestrasSilab = new Array();
var globalPruebasSilab = new Array();


$(function() {
    $( "#fecha_nacimiento" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        yearRange: "1900:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_parto" ).datepicker({
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fechaVih" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        yearRange: "1980:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fechaSida" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        yearRange: "1980:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fechaDefuncion" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fechaNotificacion" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fechaTarv" ).datepicker({
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
    $( "#fechaTarvInicio" ).datepicker({
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
    $( "#fechaCd4" ).datepicker({
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
    $( "#recuento1Cd4" ).datepicker({
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
    $( "#recuento2Cd4" ).datepicker({
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
    $( "#cargaViral" ).datepicker({
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

function llenarFactoresRiesgo(){
    var factoresRiesgo = $( "#globalFactorRiesgoRelacionados" ).val().split("###");
    for(var i=0; i<factoresRiesgo.length;i++){
        var factorRiesgo = factoresRiesgo[i].split("#-#");
        llenarFactorRiesgo(factorRiesgo);
    }
    crearTablaFactorRiesgo();
}

function llenarFactorRiesgo(factoresRiesgo){
    var idGrupoFactorRiesgo = factoresRiesgo[0];
    var nombreGrupoFactorRiesgo = factoresRiesgo[1];
    var idFactorRiesgo = factoresRiesgo[2];
    var nombreFactorRiesgo = factoresRiesgo[3];
    //var nombreEnfermedad = enfermedad[0];
    if (idGrupoFactorRiesgo !="" && idGrupoFactorRiesgo != 0 && idFactorRiesgo !=0){
        
        var tmpReg = globalFactorRiesgoRelacionados.length;
        idGrupoFactorRiesgo = (tmpReg==0) ? idGrupoFactorRiesgo : "###"+idGrupoFactorRiesgo;
        globalFactorRiesgoRelacionados[tmpReg] = new Array(idGrupoFactorRiesgo,nombreGrupoFactorRiesgo,idFactorRiesgo, nombreFactorRiesgo);
    }
}

function llenarEnfOportunistas(){
    var enfOportunistas = $( "#globalEnfOportunistaRelacionados" ).val().split("###");
    for(var i=0; i<enfOportunistas.length;i++){
        var enfOportunista = enfOportunistas[i].split("-");
        llenarEnfOportunista(enfOportunista);
    }
    crearTablaEnfOportunista();
}

function llenarEnfOportunista(enfOportunista){
    var enfOportunistaId = enfOportunista[0];
    var enfOportunistaNombre = enfOportunista[1];
    if (enfOportunistaId !="" && enfOportunistaId != 0){
        var tmpReg = globalEnfOportunistaRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (enfOportunistaId == globalEnfOportunistaRelacionados[i][0] || "###"+enfOportunistaId == globalEnfOportunistaRelacionados[i][0])
                flag = false;
        }
        if (flag){
            enfOportunistaId = (tmpReg==0) ? enfOportunistaId : "###"+enfOportunistaId;
            globalEnfOportunistaRelacionados[tmpReg] = new Array(enfOportunistaId,enfOportunistaNombre);
        }
    }
}

function iniciaDatosComportamiento(){
    var action = $("#action").val();
    if (action == "M"){
        if($("#drpItsUltimo").val()==1){
             $( "#labelUlcerativa" ).css( "display", "" );
             $( "#drpItsUlcerativa" ).css( "display", "" );
        }
        else{
            $( "#labelUlcerativa" ).css( "display", "none" );
            $( "#drpItsUlcerativa" ).css( "display", "none" );
        }
        if($("#drpDonante").val()==1){
            $( "#divDonante" ).show();
            $( "#divDonante1" ).show();
        }
        else{
            $( "#divDonante" ).hide();
            $( "#divDonante1" ).hide();
        }
        if($("#drpEmbarazada").val()==1)
            $( "#divFechaParto" ).show();
        else
            $( "#divFechaParto" ).hide();
    }
    else if(action == "N"){
        $( "#labelUlcerativa" ).css( "display", "none" );
        $( "#drpItsUlcerativa" ).css( "display", "none" );
        $( "#divDonante" ).hide();
        $( "#divDonante1" ).hide();
        $( "#divFechaParto" ).hide();
    }
}

function iniciarDatosCondicionPaciente(){
    var action = $("#action").val();
    if (action == "M"){
        if($("#drpCondicion").val()==2){
            $( "#divFechaDefuncion" ).show();
            $( "#divLugarDefuncion" ).show();
            $( "#labelSobrevidaSida" ).css( "display", "" );
            $( "#sobrevidaSida" ).css( "display", "" );
        }
        else{
            $( "#divFechaDefuncion" ).hide();
            $( "#divLugarDefuncion" ).hide();
            $( "#labelSobrevidaSida" ).css( "display", "none" );
            $( "#sobrevidaSida" ).css( "display", "none" );
        }
    }
    else{
        $( "#divFechaDefuncion" ).hide();
        $( "#divLugarDefuncion" ).hide();
        $( "#labelSobrevidaSida" ).css( "display", "none" );
        $( "#sobrevidaSida" ).css( "display", "none" );
    }
}

$(document).ready(function() {
    
    individuo($("#drpTipoId").val(), $("#no_identificador").val());
    sexoEmbarazo();
    llenarFactoresRiesgo();
    llenarEnfOportunistas();
    iniciaDatosComportamiento();
    iniciarDatosCondicionPaciente();
    // Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
    
    
    
    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                if(ui.index==4)
                    $('#next').html("Inicio");
                else
                    $('#next').html("Siguiente");
            }
        });
    });
    
    $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 750,
        width: 1000,
        modal: true,
        position: 'center',
        buttons: {
            Salir: function() {
                borrarTabla();
                $( this ).dialog( "close" );
            }
        }
    });

    $( "#dialog-form" ).bind("dialogclose",function(){
        borrarTabla()
    });
        
    borrarTabla();
    
        
    $( "#ocupacion" ).autocomplete(urlprefix + "js/dynamic/ocupaciones.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#ocupacion").val($("<div>").html(li.selectValue).text());
            $("#ocupacionId").val(li.extra[0]);
        },
        autoFill:false
    });
    
    $( "#enfOportunista" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#enfOportunista").val(li.selectValue);
            $("#enfOportunistaNombre").val(li.selectValue);
            $("#enfOportunistaId").val(li.extra[0]);
        },
        autoFill:false
    });
    
    $( "#notificacion_unidad" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#notificacion_unidad").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un").val(li.extra[0]);
            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    
    $( "#lugarDefuncion" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#lugarDefuncion").val($("<div>").html(li.selectValue).text());
            $("#id_un_defuncion").val(li.extra[0]);
        },
        autoFill:false
    });
    
    
});

function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
}

// Moverse al siguiente TAB de datos de la muestra
function siguienteTab()
{
    if(getSelectedTabIndex()==0)
    {
        $("#tabs").tabs('select', 1)
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==1)
    {
        $("#tabs").tabs('select', 2);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==2)
    {
        $("#tabs").tabs('select', 3);
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==3)
    {
        $("#tabs").tabs('select', 4);
        $('#next').html("Inicio");
    }
    else 
    {
        $("#tabs").tabs('select', 0);
        $('#next').html("Siguiente");
    }
}

function getSelectedTabIndex() {
    return $("#tabs").tabs('option', 'selected');
}

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function buscarPersona()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/busquedaPersona.php',
        data: "id="+jQuery.trim($("#id").val()) + "&his="+jQuery.trim($("#his").val())
        + "&n="+jQuery.trim($("#n").val()) 
        + "&p="+jQuery.trim($("#p").val())
        + "&ed="+jQuery.trim($("#ed").val()) + "&ed2="+jQuery.trim($("#ed2").val())
        + "&ted="+($("#drpPopTipo").val()==0?"":$("#drpPopTipo").val()) + "&sx="+($("#drpsexoP").val()==0?"":$("#drpsexoP").val())
        + "&tip="+($("#drpPopHTipo").val()==0?"":$("#drpPopHTipo").val()) + "&pagina="+pagina,
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        validarPopup();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
}

function validarPopupP()
{
    pagina = 1;
    validarPopup();
}


function validarPopup()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarPersona();
    else
    {
        var ed1 = parseInt($("#ed").val());
        var ed2 = parseInt($("#ed2").val());

        if(ed1 > ed2)
        {
            $("#pErrors").show();
            $("#pDetalle").html("La edad desde no debe ser mayor que la edad hasta.")
        }
        else
            buscarPersona();
    }
}

function individuo(tipoId,idP)
{
    //alert(idP);
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosPersona.php',
        data: "tipo_id="+tipoId+"&id="+ idP,
        success: function(data)
        {
            var partes = data.toString().split('#');
           
            if(data.toString().length>0)
            {
                $("#drpTipoId").val(replace(partes[0]));
                $("#tipoId").val(replace(partes[0]));
                $("#no_identificador").val(replace(partes[1]));
               
                $("#primer_nombre").val(replace(partes[2]));
                $("#segundo_nombre").val(replace(partes[3]));
                $("#primer_apellido").val(replace(partes[4]));
                $("#segundo_apellido").val(replace(partes[5]));
               
                $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
               
                $("#nombre_responsable").val(partes[10]);
                
                $("#lugar_poblado").val(partes[15]);
                $("#telefono").val(partes[19]);
                
                $("#ocupacion").val(partes[17]);
                $("#ocupacionId").val(partes[20]);
                
                $("#drpGenero").val(partes[24]);
                $("#drpEtnia").val(partes[23]);
                $("#drpEstadoCivil").val(partes[21]);
                $("#drpEscolaridad").val(partes[22]);

                idProvincia = partes[11];
                idRegion = partes[12];
                idDistrito = partes[13];
                idCorregimiento = partes[14];

                $("#idPro").val(idProvincia);
                $("#idReg").val(idRegion);
                $("#idDis").val(idDistrito);
                $("#idCor").val(idCorregimiento);

                $("#drpProIndividuo").val(idProvincia);
                setRegionPersona(idProvincia, idRegion);
                setDistritoPersona(idProvincia, idRegion, idDistrito);
                setCorregimientoPersona(idDistrito, idCorregimiento);
               
                $("#resultadosBusqueda").html('');
                $("#dialog-form").dialog('close');
                found = true;
                calcularEdad();
                sexoEmbarazo();
            }
            else
                found = false;
        }
    });    
}

function borrarIndividuo()
{
    //Falta arreglar las provincias y demas borrarle los datos
    found = false;
    $("#id_individuo").val(-1);
    // borra todos los datos de la pestaña de individuo

    // Datos personales
    $("#aseguradoSi").attr('checked',false);
    $("#aseguradoNo").attr('checked',false);
    $("#aseguradoDesc").attr('checked',false);
    $("#drpTipoId").val(0);
    $("#no_identificador").val("");

    $("#primer_nombre").val("");
    $("#segundo_nombre").val("");
    $("#primer_apellido").val("");
    $("#segundo_apellido").val("");

    $("#drptipo_edad").val(0);
    $("#edad").val("");
    $("#fecha_nacimiento").val("");
    $("#drpsexo").val(0);
       
    $("#nombre_responsable").val("");

    $("#drpProIndividuo").val(0);
    $("#idPro").val(0);
    $("#drpRegIndividuo").val(0);
    $("#idReg").val(0);
    $("#drpDisIndividuo").val(0);
    $("#idDis").val(0);
    $("#drpCorIndividuo").val(0);
    $("#idCor").val(0);
    $("#lugar_poblado").val("");
    $("#ocupacion").val("");
    $("#ocupacionId").val("");
    $("#telefono").val("");
    $("#drpEstadoCivil").val(0);
    $("#drpEscolaridad").val(0);

//$("#no_direccion_individuo").attr('checked',false);
//clickNoDirIndividuo();
}

function clickNoDirIndividuo()
{   
    if ($("#no_direccion_individuo").is(":checked"))
    {
        $("#direccion_individuo").val("");
        $("#direccion_individuo").attr('disabled', true);
    }
    else
        $("#direccion_individuo").attr('disabled', false);  
}

function setRegionCascada(){
    setRegionPersona($("#drpProIndividuo").val(),-1);
}

function setRegionCascadaDiagnostico(){
    setRegionPersona($("#drpProIndividuoDiagnostico").val(),-1,'drpRegIndividuoDiagnostico');
}

function setRegionPersona(idProvincia, idRegion, idRegistro)
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

        if (!idRegistro)
            $("#drpRegIndividuo").html(options);
        else
            $("#"+idRegistro).html(options);
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpProIndividuo").val(),$("#drpRegIndividuo").val(),-1);
}

function setDistritoCascadaDiagnostico(){
    setDistritoPersona($("#drpProIndividuoDiagnostico").val(),$("#drpRegIndividuoDiagnostico").val(),-1,'drpDisIndividuoDiagnostico');
}

function setDistritoPersona(idProvincia, idRegion, idDistrito, idDistritoDestino)
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

        if (!idDistritoDestino)
            $("#drpDisIndividuo").html(options);
        else
            $("#"+idDistritoDestino).html(options);
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDisIndividuo").val(),-1);
}

function setCorregimientoCascadaDiagnostico(){
    setCorregimientoPersona($("#drpDisIndividuo").val(),-1,'drpCorIndividuoDiagnostico');
}

function setCorregimientoPersona(idDistrito, idCorregimiento, idCorregimientoDestino)
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

        if (!idCorregimientoDestino)
            $("#drpCorIndividuo").html(options);
        else
            $("#"+idCorregimientoDestino).html(options);
    })
}

function otraUnidad(){
    noDisponible = $('#unidad_disponible').is(':checked');
    if(noDisponible){
        $('#notificacion_unidad').attr('readonly', true);
        $('#notificacion_unidad').attr('disabled', 'disabled');
        $('#otraUnidad').attr('style', 'display:""');
        $('#otraUnidadLabel').attr('style', 'display:""');
    }
    else{
        $('#notificacion_unidad').attr('readonly', false);
        $('#notificacion_unidad').attr('disabled', '');
        $('#otraUnidad').attr('style', 'display:none');
        $('#otraUnidadLabel').attr('style', 'display:none');
    }
}

function dirNoDisponible(){
    dirDisponible = $('#no_direccion_individuo').is(':checked');
    if(dirDisponible){
        $('#direccion_individuo').attr('readonly', true);
        $('#direccion_individuo').attr('disabled', 'disabled');
    }
    else{
        $('#direccion_individuo').attr('readonly', false);
        $('#direccion_individuo').attr('disabled', '');
    }
}

function setFactorRiesgoCascada(){
    var idGrupoFactorRiesgo = $("#drpFactorRiesgo").val();
    $.getJSON(urlprefix + 'js/dynamic/factorRiesgo.php',{
        idGrupoFactorRiesgo: idGrupoFactorRiesgo,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        if(j.length>0){
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
        }
        else
            options = '<option value="-1">No disponible</option>';

        $("#drpSubFactorRiesgo").html(options);
    })
}

//Laboratorio

function estaHospitalizado(){
    hospitalizadoSi = $('#hospitalizadoSi').is(':checked');
    if(hospitalizadoSi){
        $( "#drpHospitalizado" ).css( "display", "" );
        $( "#fechaHospitOblig" ).css( "display", "" );        
    }
    else{ 
        $( "#drpHospitalizado" ).css( "display", "none" );
        $( "#fechaHospitOblig" ).css( "display", "none" );
    }
}

function sexoEmbarazo(){
    sexoEmb = $('#drpsexo').val();
    if(sexoEmb == 'F')
        $( "#divEmbarazo" ).show();
    else
        $( "#divEmbarazo" ).hide();
    
}

function estaEmbarazada(){
    embarazo = $('#drpEmbarazada').val();
    if(embarazo == '1')
        $( "#divFechaParto" ).show();
    else
        $( "#divFechaParto" ).hide();
}

//Relacionar Enfermedades
function relacionarFactorRiesgo(){
    var idGrupoFactorRiesgo = $("#drpFactorRiesgo").val();
    var nombreGrupoFactorRiesgo = $("#drpFactorRiesgo").find(":selected").text();
    var idFactorRiesgo = $("#drpSubFactorRiesgo").val();
    var nombreFactorRiesgo= $("#drpSubFactorRiesgo").find(":selected").text();
    
    if (idGrupoFactorRiesgo !="" && idGrupoFactorRiesgo != 0 && idFactorRiesgo !=0){
        
        var tmpReg = globalFactorRiesgoRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idGrupoFactorRiesgo == globalFactorRiesgoRelacionados[i][0] || "###"+idGrupoFactorRiesgo == globalFactorRiesgoRelacionados[i][0])
                && (idFactorRiesgo == globalFactorRiesgoRelacionados[i][2] || "###"+idFactorRiesgo == globalFactorRiesgoRelacionados[i][2]))
                flag = false;
        }
        if (flag){
            idGrupoFactorRiesgo = (tmpReg==0) ? idGrupoFactorRiesgo : "###"+idGrupoFactorRiesgo;
            globalFactorRiesgoRelacionados[tmpReg] = new Array(idGrupoFactorRiesgo,nombreGrupoFactorRiesgo,idFactorRiesgo, nombreFactorRiesgo);
            crearTablaFactorRiesgo();
        }
        else
            alert ("Ya existe un registro para esta relacion");
    }
    else
        alert("Debe seleccionar una grupo de factor de riesgo y su especificacion");
}

function crearTablaFactorRiesgo(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Factores de riesgo</th>'+
    '<th class="dxgvHeader_PlasticBlue">Especificacion</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalFactorRiesgoRelacionados.length;i++){
        if(__isset(globalFactorRiesgoRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="180px">'+globalFactorRiesgoRelacionados[i][1]+'</th>'+
            '<td class="fila" width="180px">'+globalFactorRiesgoRelacionados[i][3]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelFactorRiesgo('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divRelFactorRiesgo").html(tabla);
}

function eliminarRelFactorRiesgo(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con el factor de riesgo "+globalFactorRiesgoRelacionados[pos][1]+" - "+globalFactorRiesgoRelacionados[pos][3])){
        globalFactorRiesgoRelacionados.splice(pos, 1);
        crearTablaFactorRiesgo();
    }
}

function relacionarEnfOportunista(){
    var enfOportunistaId = $("#enfOportunistaId").val();
    var enfOportunistaNombre = $("#enfOportunistaNombre").val();
    
    if (enfOportunistaId !="" && enfOportunistaId != 0){
        
        var tmpReg = globalEnfOportunistaRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (enfOportunistaId == globalEnfOportunistaRelacionados[i][0] || "###"+enfOportunistaId == globalEnfOportunistaRelacionados[i][0])
                flag = false;
        }
        if (flag){
            enfOportunistaId = (tmpReg==0) ? enfOportunistaId : "###"+enfOportunistaId;
            globalEnfOportunistaRelacionados[tmpReg] = new Array(enfOportunistaId,enfOportunistaNombre);
            $("#enfOportunista").val("");
            $("#enfOportunistaNombre").val("");
            $("#enfOportunistaId").val("");
            crearTablaEnfOportunista();
        }
        else{
            alert ("Ya existe un registro para esta relacion");
            $("#enfOportunista").val("");
            $("#enfOportunistaNombre").val("");
            $("#enfOportunistaId").val("");
        }
    }
    else
        alert("Debe ingresar una enfermedad oportunista");
}

function crearTablaEnfOportunista(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Enfermedad oportunista</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalEnfOportunistaRelacionados.length;i++){
        if(__isset(globalEnfOportunistaRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="360px">'+globalEnfOportunistaRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarEnfOportunista('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divEnfermedadOportunista").html(tabla);
}

function eliminarEnfOportunista(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con la enfermedad oportunista "+globalEnfOportunistaRelacionados[pos][1])){
        globalEnfOportunistaRelacionados.splice(pos, 1);
        crearTablaEnfOportunista();
    }
}

function validarVih(){
    var Message = '';
    var ErroresI = '';
    var ErroresC = '';
    var ErroresN = '';
    var ErrorIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
    var ErrorCondicion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Condici&oacute;n del paciente:';
    var ErrorNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Notificaci&oacute;n:';
    
    //Individuo
    aseguradoSi = $('#aseguradoSi').is(':checked');
    aseguradoNo = $('#aseguradoNo').is(':checked');
    aseguradoDesc = $('#aseguradoDesc').is(':checked');
    if(!(aseguradoSi || aseguradoNo || aseguradoDesc) ){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona est&aacute; asegurado."; 
    }
    if($("#drpTipoId").val()==0){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de identificador."; 
    }
    if(jQuery.trim($("#no_identificador").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
    if(jQuery.trim($("#primer_nombre").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer nombre.";
    if(jQuery.trim($("#primer_apellido").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";
    if(jQuery.trim($("#fecha_nacimiento").val())==""){
        if(jQuery.trim($("#edad").val())!="")
            calcularFechaNacimiento();
        else
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de nacimiento.";
    }
    else{
        if(!isDate($("#fecha_nacimiento").val().toString()))
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no tiene el formato adecuado.";
    }
    if(jQuery.trim($("#edad").val())==""){
        if(jQuery.trim($("#fecha_nacimiento").val())!="")
            calcularEdad();
        else
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la edad de la persona.";
    }
    if($("#drpsexo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el sexo de la persona.";
    if($("#drpProIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia de la persona.";
    if($("#drpRegIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n de la persona.";
    if($("#drpDisIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de la persona.";
    if($("#drpCorIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";
    if($("#drpEstadoCivil").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el estado civil de la persona.";
    if($("#drpEscolaridad").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la escolaridad de la persona.";
    if($("#drpGenero").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el genero de la persona.";
    if($("#drpEtnia").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la etnia de la persona.";
    if($("#drpItsUltimo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar ITS en el ultimo anio.";
//    if($("#drpCondonRel").val()==0)
//        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si se uso condon en la ultima relacion sexual.";
    if($("#drpTrabajoSexual").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si se es trabajador(a) sexual.";
    if($("#drpDonante").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si se es donante.";
    if($("#drpsexo").val()=='F'){
        if($("#drpEmbarazada").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si esta en embarazo.";
    }        
    varSida = $('#check_sida').is(':checked');
    varVih = $('#check_vih').is(':checked');
    if(!(varSida || varVih) ){
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el caso es de VIH o de SIDA."; 
    }
    if($("#drpCondicion").val()==0)
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la condicion actual del paciente.";
    
    varOtroDefuncion = $('#check_otroLugarDefuncion').is(':checked');  
    if($("#lugarDefuncion").val()!=""){
        if($("#id_un_defuncion").val()==""){
            if(varOtroDefuncion==false)
                ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Ha ingresado un lugar de defunci&oacute;n que no esta en la base de datos y no seleccion&oacute; la opcion 'Otro'.";
        }
    }
    
    //Notificacion
    if(!$('#unidad_disponible').is(':checked')){
        if(jQuery.trim($("#notificacion_unidad").val())=="")
            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la instalaci&oacute;n de salud.";
    }
    if(jQuery.trim($("#fechaNotificacion").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de notificacion.";
    
    (ErroresI=="")? ErrorIndividuo="": ErrorIndividuo = ErrorIndividuo+ErroresI + "<br/>";
    (ErroresC=="")? ErrorCondicion="": ErrorCondicion = ErrorCondicion+ErroresC + "<br/>";
    (ErroresN=="")? ErrorNotificacion="": ErrorNotificacion = ErrorNotificacion+ErroresN + "<br/>";
    Message = ErrorIndividuo + ErrorCondicion + ErrorNotificacion;
    
    //Message= "";
    if(Message!="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        if (confirm("Esta apunto de guardar el formulario con numero de identidad: "+$("#no_identificador").val()+
            "\n\nEsta seguro? recuerde que este dato no se puede modificar despues")){
            $("#dSummaryErrors").css('display','none');
            $('#nombreRegistra').attr('readonly', false);
            $('#nombreRegistra').attr('disabled', '');
            $('#drpTipoId').attr('readonly', false);
            $('#drpTipoId').attr('disabled', '');
            $('#no_identificador').attr('readonly', false);
            $('#no_identificador').attr('disabled', '');
            $('#fecFormVih').attr('readonly', false);
            $('#fecFormVih').attr('disabled', '');
            $('#semanaEpi').attr('readonly', false);
            $('#semanaEpi').attr('disabled', '');
            $('#sobrevida').attr('readonly', false);
            $('#sobrevida').attr('disabled', '');
            $('#sobrevidaSida').attr('readonly', false);
            $('#sobrevidaSida').attr('disabled', '');
            var param = '';
            var i=0;
            for(i=0; i<globalFactorRiesgoRelacionados.length;i++){
                if(__isset(globalFactorRiesgoRelacionados[i])){
                    param+=globalFactorRiesgoRelacionados[i][0]+"#-#"+globalFactorRiesgoRelacionados[i][2];//Factor de Riesgo
                }
            }
            $('#globalFactorRiesgoRelacionados').val(param);

            var param2 = '';

            for(i=0; i<globalEnfOportunistaRelacionados.length;i++){
                if(__isset(globalEnfOportunistaRelacionados[i])){
                    param2+=globalEnfOportunistaRelacionados[i][0];//enfermedad Oportunista
                }
            }
            $('#globalEnfOportunistaRelacionados').val(param2);

            var muestras = '';

            for(i=0; i<globalMuestras.length;i++){
                if(__isset(globalMuestras[i])){
                    if (i==0)
                        muestras+=globalMuestras[i][0]+"#-#"+globalMuestras[i][1];//enfermedad Oportunista
                    else 
                        muestras+="###"+globalMuestras[i][0]+"#-#"+globalMuestras[i][1];//enfermedad Oportunista
                }
            }

            $("#globalMuestras").val($("#globalMuestras").val()+globalMuestrasSilab);
            $("#globalPruebas").val($("#globalPruebas").val()+globalPruebasSilab);

            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de VIH/SIDA, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de VIH/SIDA, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#dSummaryErrors").css('display','none');
                $('#frmContenido').submit();
            }
        }
    }
}

function ulcerativa(){
    ulcera = $('#drpItsUltimo').val();
    if(ulcera == '1'){
        $( "#labelUlcerativa" ).css( "display", "" );
        $( "#drpItsUlcerativa" ).css( "display", "" );
    }
    else{
        $( "#labelUlcerativa" ).css( "display", "none" );
        $( "#drpItsUlcerativa" ).css( "display", "none" );
    }
}

function donante(){
    var dona = $('#drpDonante').val();
    if(dona == '1'){
        $( "#divDonante" ).show();
        $( "#divDonante1" ).show();
    }
    else{
        $( "#divDonante" ).hide();
        $( "#divDonante1" ).hide();
    }
}

function estaMuerto(){
    var condicion = $('#drpCondicion').val();
    if(condicion == '2'){
        $( "#divFechaDefuncion" ).show();
        $( "#divLugarDefuncion" ).show();
        $( "#labelSobrevidaSida" ).css( "display", "" );
        $( "#sobrevidaSida" ).css( "display", "" );
    }
    else{
        $( "#divFechaDefuncion" ).hide();
        $( "#divLugarDefuncion" ).hide();
        $( "#labelSobrevidaSida" ).css( "display", "none" );
        $( "#sobrevidaSida" ).css( "display", "none" );
    }
}

function calcularSobrevida(){
    var fecha1 = $("#fechaVih").val();
    var fecha2 = $("#fechaSida").val();
    if (fecha1 != "" && fecha2 != ""){
        if(validarFechas(fecha1,fecha2))
            $("#sobrevida").val(calcularTiempoDosFechas(fecha1,fecha2));
        else{
            alert("La fecha de diagnostico de VIH no puede ser mayor que la de SIDA");
            $("#fechaVih").val("");
        }
    }
    if ($("#fechaDefuncion").val()!=""){
        calcularSobrevidaSida();
    }
    calculaSemanaEpi();
    if (fecha1 != "")
        $("#edadVih").val(calcularEdadVih(fecha1));
    if (fecha2 != "")
        $("#edadSida").val(calcularEdadVih(fecha2));
    
}

function calcularSobrevidaSida(){
    var fecha1 = $("#fechaSida").val();
    var fecha2 = $("#fechaDefuncion").val();
    if (fecha1 != "" && fecha2 != ""){
        if(validarFechas(fecha1,fecha2))
            $("#sobrevidaSida").val(calcularTiempoDosFechas(fecha1,fecha2));
        else{
            alert("La fecha de defuncion, no puede ser menor que la de diagnostico de SIDA");
            $("#fechaDefuncion").val("");
        }
    }
}

function calculaSemanaEpi(){
    var fechaSida = $("#fechaSida").val();
    var fechaVih = $("#fechaVih").val();
    var fechaNotifica = "";
    var flag = false;
    if (fechaSida == ""){
        if(fechaVih!=""){
            if(isDate(fechaVih)){
                fechaNotifica = fechaVih;
                flag = true;
            }
        }
    }
    else{
        if (isDate(fechaSida)){
            fechaNotifica = fechaSida;
            flag = true;
        }
    }
    if(flag){
        var unidad = fechaNotifica.split("/");
        var dia = unidad[0];
        var mes = unidad[1];
        var anio = unidad[2];
        var fsintomas = new Date(anio,mes - 1,dia);
        semanaEpi = fsintomas.getWeek(0);
        $("#semanaEpi").val(semanaEpi);
        $("#notificacionAnio").val(anio);
    }
    else{
        $("#semanaEpi").val('0');
    }
    
}

function calcularEdadVih(fechaActual)
{
    var edad=0;

    if(jQuery.trim($("#fecha_nacimiento").val())!="")
    {
        if(isDate($("#fecha_nacimiento").val()))
        {
            if(compararFechas($("#fecha_nacimiento").val()))
            {
                var fechaNacimiento = $("#fecha_nacimiento").val().toString().split("/");
                var diaNac = fechaNacimiento[0];
                var mesNac = fechaNacimiento[1];
                var anioNac = fechaNacimiento[2];
                
                var fecActual = fechaActual.split("/");
                var diaAct = fecActual[0];
                var mesAct = fecActual[1];
                var anioAct = fecActual[2];

                // Calcula años
                edad = anioAct - anioNac;

                if(edad!=0)
                {
                    if (mesNac > mesAct )
                        edad--;
                    else if(mesNac == mesAct)
                    {
                        if(diaAct < diaNac)
                            edad--;
                    }
                }
                
                return edad;
            }
        }
    }
}