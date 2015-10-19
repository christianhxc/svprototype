var globalEnfermedadesRelacionados = new Array();
var globalVacunasRelacionados = new Array();
var globalMuestras = new Array();

var globalMuestrasUceti = new Array();
var globalMuestrasSilab = new Array();
var globalPruebasSilab = new Array();

//Relacionar Muestras uceti
function relacionarMuestraUceti(){
    var fecha_toma = $("#fecha_tipoMuestra_toma").val();
    var fecha_envio = $("#fecha_tipoMuestra_envio").val();
    var fecha_lab = $("#fecha_tipoMuestra_lab").val();
    var idTipoMuestra = $("#drpTipoMuestras").val();
    var nombreTipoMuestra = $("#drpTipoMuestras :selected").text();
    var muestra_manual = "";
    
    if (fecha_toma !="" && fecha_envio !="" && fecha_lab !="" && idTipoMuestra != 0){
        
        if(!isDate(fecha_toma.toString())){
            alert("La fecha de toma de muestra no tiene el formato adecuado.");
            return;
        }
        else{
            if(comparacionFechas(fecha_toma.toString(),fechaActualString())){
                alert("La fecha de toma de muestra no puede ser futuro.");
                return;
            }
        }
        if(!isDate(fecha_envio.toString())){
            alert("La fecha de env\xedo de muestra no tiene el formato adecuado.");
            return;
        }
        else{
            if(comparacionFechas(fecha_envio.toString(),fechaActualString())){
                alert("La fecha de env\xedo de muestra no puede ser futuro.");
                return;
            }
        }
        if(!isDate(fecha_lab.toString())){
            alert("La fecha de recibo de laboratorio no tiene el formato adecuado.");
            return;
        }
        else{
            if(comparacionFechas(fecha_lab.toString(),fechaActualString())){
                alert("La fecha de recibo de laboratorio no puede ser futuro.");
                return;
            }
        }
        var tmpReg = globalMuestrasUceti.length;
        
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (idTipoMuestra == globalMuestrasUceti[i][0] || "###"+idTipoMuestra == globalMuestrasUceti[i][0])
                flag = false;
        }
        if (flag){
            idTipoMuestra = (tmpReg==0) ? idTipoMuestra : "###"+idTipoMuestra;
            globalMuestrasUceti[tmpReg] = new Array(idTipoMuestra, nombreTipoMuestra, fecha_toma, fecha_envio, fecha_lab, muestra_manual);
            crearTablaMuestrasUceti();
        }
        else
            alert ("Ya existe un registro para este tipo de muestra");
    }
    else
        alert("Debe seleccionar el tipo de muestra e ingresar las fechas");
}

function crearTablaMuestrasUceti(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
    '<tr>'+
        '<th class="dxgvHeader_PlasticBlue">Tipo de muestra</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">Fecha de toma</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">Fecha de env&iacute;o</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">Fecha de recibo<br/>de laboratorio</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">D&iacute;as de<br/>evoluci&oacute;n</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">Tiempo en que recibe<br/>el laboratorio</th>'+
        '<th class="dxgvHeader_PlasticBlue" style="text-align:center">Eliminar</th>'+
    '</tr>';
    var fecha_inicio_sintomas = $("#fecha_inicio_sintomas").val();
    //    alert(fecha_inicio_sintomas);
    for(var i=0; i<globalMuestrasUceti.length;i++){
        if(__isset(globalMuestrasUceti[i])){
            diasEvolucion = 0;
            diasRecibido = 0;
            
            var fecha_toma = globalMuestrasUceti[i][2];
            var fecha_envio = globalMuestrasUceti[i][3];
            var fecha_lab = globalMuestrasUceti[i][4];
            //            alert("fecha_toma "+fecha_toma+" fecha_envio "+fecha_envio+" fecha_lab "+fecha_lab);
            if(fecha_inicio_sintomas != ""){
                if(isDate(fecha_inicio_sintomas.toString())){
                    diasEvolucion = diferenciaFechas(fecha_inicio_sintomas, fecha_toma);
                }
                diasRecibido = diferenciaFechas(fecha_envio, fecha_lab);
            }
            tabla += '<tr>'+
                '<td class="fila" width="250px" >'+globalMuestrasUceti[i][1]+'</td>'+
                '<td class="fila" width="60px" style="text-align:center">'+globalMuestrasUceti[i][2]+'</td>'+
                '<td class="fila" width="60px" style="text-align:center">'+globalMuestrasUceti[i][3]+'</td>'+
                '<td class="fila" width="60px" style="text-align:center">'+globalMuestrasUceti[i][4]+'</td>'+
                '<td class="fila" width="40px" style="text-align:center">'+diasEvolucion+'</td>'+
                '<td class="fila" width="40px" style="text-align:center">'+diasRecibido+'</td>'+
                '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelMuestraUceti('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></td>'+
            '</tr>';
        }
    }
    tabla += "</table>";
    if(globalMuestrasUceti.length > 0)
        $("#tablaTiposMuestras").html(tabla);
    else
        $("#tablaTiposMuestras").html('');
}

function eliminarRelMuestraUceti(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon el tipo de muestra "+globalMuestrasUceti[pos][1])){
        globalMuestrasUceti.splice(pos, 1);
        crearTablaMuestrasUceti()();
    }
}

function crearTablaEnfermedades(){
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/enfermedadCronica.php',
        success: function(data)
        {
            $("#divEnfermedadCronica").html(data);
            enfermedades = $( "#globalEnfermedadesRelacionados" ).val().split("###");
            for(var i=0; i<enfermedades.length;i++){
                enfermedad = enfermedades[i].split("-");
                llenarEnfermedad(enfermedad);
            }
        }
    });
}

function llenarEnfermedad(enfermedad){
    var idEnfermedad = enfermedad[0];
    var idResEnfermedad = enfermedad[1];
    if(idResEnfermedad=='99')
        idResEnfermedad = '-1';
    var selectBox = "#drpResCronica"+idEnfermedad+" option[value="+idResEnfermedad+"]";
    //alert(selectBox);
    $(selectBox).attr('selected', 'selected');
}

function crearTablaVacunales(){
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/antecedentesVacunales.php',
        success: function(data)
        {
            $("#divAntecedenteVacunal").html(data);            
            vacunas = $( "#globalVacunasRelacionados" ).val().split("###");
            for(var i=0; i<vacunas.length;i++){
                vacuna = vacunas[i].split("-");
                //alert(vacuna);
                llenarVacuna(vacuna);
            }
        }
    });
}

function colocarCalendario(fecha){
    $( fecha ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
}

function llenarVacuna(vacuna){
    var idVacuna = vacuna[0];
    colocarCalendario("#fechaVac"+idVacuna);
    var dosis = vacuna[1];
    var idDesc = vacuna[2];
    var fecha = vacuna[3];
    //alert(fecha);
    if(idDesc=='99')
        idDesc = '-1';
    var selectBox = "#drpDesVacunal"+idVacuna+" option[value="+idDesc+"]";
    $(selectBox).attr('selected', 'selected');
    
    var dosisInput = "#numDosis"+idVacuna;
    $( dosisInput ).val(dosis);
    
    var fechaInput = "#fechaVac"+idVacuna;
    $( fechaInput ).val(fecha);
}

function llenarTipoMuestras(){
    if( $( "#globalMuestrasUceti" ).val() == "" )
        return;
    tipoMuestras = $( "#globalMuestrasUceti" ).val().split("###");
    for(var i=0; i<tipoMuestras.length;i++){
        tipoMuestra = tipoMuestras[i].split("-");
        var idTipoMuestra = tipoMuestra[0];
        var nombreTipoMuestra = tipoMuestra[1];
        var fecha_toma = tipoMuestra[2];
        var fecha_envio = tipoMuestra[3];
        var fecha_lab = tipoMuestra[4];
        var tmpReg = i;
        idTipoMuestra = (tmpReg==0) ? idTipoMuestra : "###"+idTipoMuestra;
        globalMuestrasUceti[tmpReg] = new Array(idTipoMuestra, nombreTipoMuestra, fecha_toma, fecha_envio, fecha_lab);
    
    }
    crearTablaMuestrasUceti();
}

$(document).ready(function() {
    
    if($("#updateMuestra").val() == '1')   
        $('#tabs').tabs({
            selected: 5
        });
        
    antecedenteVacunal();
    enfermedadCronica();
    trimestreOblig();
    sexoEmbarazo();
    tipoContactoOblig();
    estaHospitalizado();
    riesgoCual();
    viajeDonde();
    otraUnidad();
    otroHallazgo();
    otroRX();
    antibioticos();
    antivirales();
    neumonia();
    crearTablaVacunales();
    crearTablaEnfermedades();
    llenarTipoMuestras();
    Tooltip_field();
    individuo($("#drpTipoId").val(), $("#no_identificador").val());
    // Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                if(ui.index==5)
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
    //    $( "#notificacion_unidad" ).autocomplete({
    //      source: function( request, response ) {
    //        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
    //        response( $.grep( names, function( value ) {
    //          value = value.label || value.value || value;
    //          return matcher.test( value ) || matcher.test( normalize( value ) );
    //        }) );
    //      }
    //    });
    
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
    
    $( "#datos_clinicos_evento" ).autocomplete(urlprefix + "js/dynamic/eventosUceti.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#datos_clinicos_evento").val(li.selectValue);
            $("#EventoNombre").val(li.selectValue);
            $("#eventoId").val(li.extra[0]);
        },
        autoFill:false
    });
    
    $( "#antibioticosCual" ).autocomplete(urlprefix + "js/dynamic/antibioticos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#antibioticosCual").val(li.selectValue);
        },
        autoFill:false
    });
    $( "#antiviralesCual" ).autocomplete(urlprefix + "js/dynamic/antivirales.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#antiviralesCual").val(li.selectValue);
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
        $('#next').html("Siguiente");
    }
    else if(getSelectedTabIndex()==4)
    {
        $("#tabs").tabs('select', 5);
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
        url: urlprefix + 'js/dynamic/busquedaPersonaUceti.php',
        data: "id="+jQuery.trim($("#id").val()) + "&his="+jQuery.trim($("#his").val())
        + "&n="+jQuery.trim($("#n").val()) 
        + "&p="+jQuery.trim($("#p").val())
        + "&ed="+jQuery.trim($("#ed").val()) + "&ed2="+jQuery.trim($("#ed2").val())
        + "&ted="+($("#drpPopTipo").val()==0?"":$("#drpPopTipo").val()) + "&sx="+($("#drpsexoP").val()==0?"":$("#drpsexoP").val())
        + "&sistema="+$("#drpSistema").val()
        + "&pagina="+pagina,
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
        url: urlprefix + 'js/dynamic/datosPersonaUceti.php',
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
                
                $("#direccion_individuo").val(partes[15]);
                $("#otra_direccion").val(partes[18]);
                $("#telefono").val(partes[19]);
                
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
                
                //               munis(dep, mun);
                //               zonas(dep, mun, lp);
                
                //                if (partes[16]!=" - "){
                //                    $("#direccion_individuo").val(partes[15]);
                //                    $('#no_direccion_individuo').attr('checked', false);
                //                }
                //                else 
                //                    $('#no_direccion_individuo').attr('checked', true);
                
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

function individuoSilab(tipoId,idP, silab)
{
    //    alert("Aqui");
    //alert(idP);
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosPersonaSilab.php',
        data: "tipo_id="+tipoId+"&id="+ idP+"&silab="+ silab,
        success: function(data)
        {
//            alert(data);
            var partes = data.toString().split('#');
            
            if(data.toString().length>0)
            {
                if(tipoId == 1){//Cedula
                    $("#drpTipoId").val(1);
                    $("#tipoId").val(1);
                }else if(tipoId == 2){//Pasaporte
                    $("#drpTipoId").val(4);
                    $("#tipoId").val(4);
                }else if(tipoId == 3){//Expediente
                    $("#drpTipoId").val(2);
                    $("#tipoId").val(2);
                }
                else if(tipoId == 4){//Codigo
//                    $("#drpTipoId").val(2);
//                    $("#tipoId").val(2);
                }
                //                $("#drpTipoId").val(replace(partes[0]));
                //                $("#tipoId").val(replace(partes[0]));
                $("#no_identificador").val(replace(partes[1]));
                
                $("#primer_nombre").val(replace(partes[2]));
                $("#segundo_nombre").val(replace(partes[3]));
                $("#primer_apellido").val(replace(partes[4]));
                $("#segundo_apellido").val(replace(partes[5]));
                
                $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
                
                //                $("#nombre_responsable").val(partes[10]);
                
                $("#direccion_individuo").val(partes[15]);
                //                $("#otra_direccion").val(partes[18]);
                $("#telefono").val(partes[19]);
                
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

function borrarPaciente()
{
    //Falta arreglar las provincias y demas borrarle los datos
    found = false;
    $("#id_individuo").val(-1);
    // borra todos los datos de la pestaña de individuo
    
    $("#hospitalizadoSi").attr('checked',false);
    $("#hospitalizadoNo").attr('checked',false);
    $("#drpTipoPaciente").val(0);
    $("#drpHospitalizado").val(0);
    $("#drpHospitalizado").css( "display", "none" );
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
    $("#direccion_individuo").val("");
    $("#otra_direccion").val("");
    $("#telefono").val("");

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
        
        $("#drpRegIndividuo").html(options);
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpProIndividuo").val(),$("#drpRegIndividuo").val(),-1);
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
        
        $("#drpDisIndividuo").html(options);
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDisIndividuo").val(),-1);
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
        
        $("#drpCorIndividuo").html(options);
    })
}

function otraUnidad(){
    noDisponible = $('#unidad_disponible').is(':checked');
    if(noDisponible){
        $('#notificacion_unidad').attr('readonly', true);
        $('#notificacion_unidad').attr('disabled', 'disabled');
    }
    else{
        $('#notificacion_unidad').attr('readonly', false);
        $('#notificacion_unidad').attr('disabled', '');
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
//Laboratorio
$(function() {
    $( "#fecha_tipoMuestra_toma" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tipoMuestra_envio" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tipoMuestra_lab" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

//formulario normal
$(function() {
    $( "#fecha_vac_fluB" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_nacimiento" ).datepicker({
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
    $( "#antibioticosFecha" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#antiviralesFecha" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_anio_previo" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_ult_dosis" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_flu" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_neumo7" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_neumo10" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_neumo13" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_neumo23" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_vac_menin" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_inicio_sintomas" ).datepicker({
        dateFormat: 'dd/mm/yy',
        showWeek: true,
        firstDay: 1,
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_hospitalizacion" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_notificacion" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_egreso" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_defuncion" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_fiebre" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tos" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_garganta" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_rinorrea" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_respiratoria" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_hallazgo_otro" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
function trimestreOblig(){
    embarazo = $('#drpEmbarazo').val();
    if(embarazo == '1'){
        $( "#trimestreOblig" ).css( "display", "" );
        $( "#drpDivTrimestre" ).css( "display", "" );
    }
    else{
        $( "#trimestreOblig" ).css( "display", "none" );
        $( "#drpDivTrimestre" ).css( "display", "none" );
    }
}

function antecedenteVacunal(){
    antecedenteSi = $('#tarVacunaSi').is(':checked');
    antecedenteNo = $('#tarVacunaNo').is(':checked');
    if(antecedenteNo){
        $( "#divAntecedenteVacunal" ).css( "display", "none" );
        $( "#lblVacuna1" ).css( "display", "none" );
        $( "#lblVacuna2" ).css( "display", "none" );
        $( "#lblVacuna3" ).css( "display", "none" );
        $( "#lblVacuna4" ).css( "display", "none" );
        $( "#vacEsquemaSi" ).css( "display", "none" );
        $( "#vacEsquemaNo" ).css( "display", "none" );
        $( "#fechaPreultDosis" ).css( "display", "none" );
    }
    else if(antecedenteSi){
        $( "#divAntecedenteVacunal" ).css( "display", "" );
        $( "#lblVacuna1" ).css( "display", "" );
        $( "#lblVacuna2" ).css( "display", "" );
        $( "#lblVacuna3" ).css( "display", "" );
        $( "#lblVacuna4" ).css( "display", "" );
        $( "#vacEsquemaSi" ).css( "display", "" );
        $( "#vacEsquemaNo" ).css( "display", "" );
        $( "#fechaPreultDosis" ).css( "display", "" );
    }
}

function enfermedadCronica(){
    cronica = $('#drpCronica').val();
    if(cronica == '1'){
        $( "#divEnfermedadCronica" ).css( "display", "" );
    }
    else{
        $( "#divEnfermedadCronica" ).css( "display", "none" );
    }
}

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

function tipoContactoOblig(){
    contacto = $('#drpContacto').val();
    if(contacto == '1'){
        $( "#tipoContactoOblig" ).css( "display", "" );
        $( "#lblContacto" ).css( "display", "" );
        $( "#drpContactoTipo" ).css( "display", "" );
    }
    else{
        $( "#tipoContactoOblig" ).css( "display", "none" );
        $( "#lblContacto" ).css( "display", "none" );
        $( "#drpContactoTipo" ).css( "display", "none" );
    }
}

function riesgoCual(){
    riesgo = $('#drpRiesgo').val();
    if(riesgo == '1')
        $( "#riesgoCual" ).css( "display", "" );
    else
        $( "#riesgoCual" ).css( "display", "none" );
}

function viajeDonde(){
    viaje = $('#drpViaje').val();
    if(viaje == '1')
        $( "#viajeDonde" ).css( "display", "" );
    else
        $( "#viajeDonde" ).css( "display", "none" );
}

function eventoEstudio(evento){
    if(evento!=1)
        $("#datosClinicosCheckGripal").attr('checked',false);
    if(evento!=2 && evento!=7)
        $("#datosClinicosCheckCentinela").attr('checked',false);
    if(evento!=3 && evento!=7)
        $("#datosClinicosCheckInusitado").attr('checked',false);
    if(evento!=4 && evento!=7)
        $("#datosClinicosCheckImprevisto").attr('checked',false);
    if(evento!=5 && evento!=7)
        $("#datosClinicosCheckExcesivo").attr('checked',false);
    if(evento!=6 && evento!=7)
        $("#datosClinicosCheckConglomerado").attr('checked',false);
    if(evento==1)
        $("#datosClinicosCheckNeumonia").attr('checked',false);
    neumonia();
}

function neumonia(){
    neumoniaRx = $('#datosClinicosCheckNeumonia').is(':checked');
    if(neumoniaRx){
        $( "#condensacionOblig" ).css( "display", "" );
        $( "#derrameOblig" ).css( "display", "" );
        $( "#broncoOblig" ).css( "display", "" );
        $( "#infiltradoOblig" ).css( "display", "" );
        $( "#otroRXOblig" ).css( "display", "" );
    }
    else{
        $( "#condensacionOblig" ).css( "display", "none" );
        $( "#derrameOblig" ).css( "display", "none" );
        $( "#broncoOblig" ).css( "display", "none" );
        $( "#infiltradoOblig" ).css( "display", "none" );
        $( "#otroRXOblig" ).css( "display", "none" );
    }
}

function statusFechaInfluenza(status){
    if(status == 1)
        $("#fecha_ult_dosis").val("00/00/0000");
    else if(status == 2)
        $("#fecha_ult_dosis").val("99/99/9999");
}

function statusFechaInfluenzaAnioPrevio(status){
    if(status == 1)
        $("#fecha_anio_previo").val("00/00/0000");
    else if(status == 2)
        $("#fecha_anio_previo").val("99/99/9999");
}

function sexoEmbarazo(){
    sexoEmb = $('#drpsexo').val();
//    if(sexoEmb != 'F' 
//        || jQuery.trim($("#edad").val())== "" 
//        || $("#drptipo_edad").val()!=3){
//        $( "#labelTrimestre" ).css( "display", "none" );
//        $( "#labelEmbarazo" ).css( "display", "none" );
//        $( "#drpTrimestre" ).css( "display", "none" );
//        $( "#drpEmbarazo" ).css( "display", "none" );
//    }
//    else{
//        if( jQuery.trim($("#edad").val()) >= 10 
//            && jQuery.trim($("#edad").val()) <= 50){
//            $( "#labelTrimestre" ).css( "display", "" );
//            $( "#labelEmbarazo" ).css( "display", "" );
//            $( "#drpTrimestre" ).css( "display", "" );
//            $( "#drpEmbarazo" ).css( "display", "" );
//        }else{
//            $( "#labelTrimestre" ).css( "display", "none" );
//            $( "#labelEmbarazo" ).css( "display", "none" );
//            $( "#drpTrimestre" ).css( "display", "none" );
//            $( "#drpEmbarazo" ).css( "display", "none" );
//        }
//    }

    if(sexoEmb == 'F'  &&   jQuery.trim($("#edad").val()) != ""
        &&  (jQuery.trim($("#edad").val()) >= 10 && jQuery.trim($("#edad").val()) <= 50) 
            && $("#drptipo_edad").val() ==3 ){
            $( "#labelTrimestre" ).css( "display", "" );
            $( "#labelEmbarazo" ).css( "display", "" );
            $( "#drpTrimestre" ).css( "display", "" );
            $( "#drpEmbarazo" ).css( "display", "" );
    }
    else{
            $( "#labelTrimestre" ).css( "display", "none" );
            $( "#labelEmbarazo" ).css( "display", "none" );
            $( "#drpTrimestre" ).css( "display", "none" );
            $( "#drpEmbarazo" ).css( "display", "none" );

        }

}

function calcularEdadUceti(){
    calcularEdad();
    sexoEmbarazo();
}

function otroRX(){
    otrorx = $('#drpResultadoOtro').val();
    if(otrorx == '1'){
        $( "#lblOtroNombre" ).css( "display", "" );
        $( "#resultadoOtroNombre" ).css( "display", "" );
    }
    else{
        $( "#lblOtroNombre" ).css( "display", "none" );
        $( "#resultadoOtroNombre" ).css( "display", "none" );
    }
}

function antibioticos(){
    antibiotico = $('#drpAntiUltimaSemana').val();
    if(antibiotico == '1'){
        $( "#lblCualAntibiotico" ).css( "display", "" );
        $( "#antibioticosCual" ).css( "display", "" );
        $( "#lblFechaAntibiotico" ).css( "display", "" );
        $( "#displayFechaAntibioticos" ).css( "display", "" );
    }
    else{
        $( "#lblCualAntibiotico" ).css( "display", "none" );
        $( "#antibioticosCual" ).css( "display", "none" );
        $( "#lblFechaAntibiotico" ).css( "display", "none" );
        $( "#displayFechaAntibioticos" ).css( "display", "none" );
    }
}

function antivirales(){
    antiviral = $('#drpAntivirales').val();
    if(antiviral == '1'){
        $( "#lblCualAntiviral" ).css( "display", "" );
        $( "#antiviralesCual" ).css( "display", "" );
        $( "#lblFechaAntiviral" ).css( "display", "" );
        $( "#displayFechaAntivirales" ).css( "display", "" );
    }
    else{
        $( "#lblCualAntiviral" ).css( "display", "none" );
        $( "#antiviralesCual" ).css( "display", "none" );
        $( "#lblFechaAntiviral" ).css( "display", "none" );
        $( "#displayFechaAntivirales" ).css( "display", "none" );
    }
}

function otroHallazgo(){
    otroH= $('#drpHallazgoOtro').val();
    if(otroH == '1'){
        $( "#lblOtroHallazgo" ).css( "display", "" );
        $( "#hallazgoOtroNombre" ).css( "display", "" );
    }
    else{
        $( "#lblOtroHallazgo" ).css( "display", "none" );
        $( "#hallazgoOtroNombre" ).css( "display", "none" );
    }
}

function validarUceti(){
    var Message = '';
    var ErroresN = '';
    var ErroresI = '';
    var ErroresA = '';
    var ErroresF = '';
    var ErroresD = '';
    var ErroresNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Notificaci&oacute;n:';
    var ErroresIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos del paciente:';
    var ErroresAntecedentes ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Antecedentes:';
    var ErroresFactores ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Factor de riesgo:';
    var ErroresDatos ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Cl&iacute;nicos Epidemiol&oacute;gicos:';
    
    //Notificacion
    noDisponible = $('#unidad_disponible').is(':checked');
    if(!noDisponible){
        if(jQuery.trim($("#notificacion_unidad").val())=="")
            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la instalaci&oacute;n de salud.";
    }
    
    //Individuo
    if($("#drpTipoPaciente").val()==0){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de paciente."; 
    }
    
    hospitalizadoSi = $('#hospitalizadoSi').is(':checked');
    hospitalizadoNo = $('#hospitalizadoNo').is(':checked');
    if(!(hospitalizadoSi || hospitalizadoNo) ){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el paciente est&aacute; hospitalizado."; 
    }
    if(hospitalizadoSi){
        if($("#drpHospitalizado" ).val()==0){
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de hospitalizaci&oacute;n."; 
        }
    }
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
    else{
        if($("#drpTipoId").val()== 1 || $("#drpTipoId").val()== 5){
            if(!validarCedula(jQuery.trim($("#no_identificador").val())))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La c\xe9dula paname\xf1a no tiene el formato esperado, debe tener por los menos dos guiones '-' y deben ser n&uacute;meros, ej: 10-123-4567 o 8-123-4567 ";
        }
    }
    
    if(jQuery.trim($("#primer_nombre").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer nombre.";
    if(jQuery.trim($("#primer_apellido").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";
    
    if(jQuery.trim($("#fecha_nacimiento").val())=="")
    {
        if(jQuery.trim($("#edad").val())!="")
            calcularFechaNacimiento();
        else{
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de nacimiento.";
        }
    }
    else
    {
        if(!isDate($("#fecha_nacimiento").val().toString()))
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_nacimiento").val().toString(),fechaActualString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser una fecha futura.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_ult_dosis").val().toString())){
                //alert($("#fecha_ult_dosis").val().toString());
                if( $("#fecha_ult_dosis").val().toString() != "00/00/0000")
                    ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha &uacute;ltima dosis.";
            }
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_defuncion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de defuncion.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_egreso").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de egreso.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_notificacion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para notificaci&oacute;n.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_hospitalizacion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de hospitalizaci&oacute;n.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_inicio_sintomas").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para inicio de s&iacute;ntomas.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#antiviralesFecha").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de antivirales.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#antibioticosFecha").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para antibi&oacute;ticos.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_fiebre").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de fiebre.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_tos").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de tos.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_garganta").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para dolor de garganta.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_rinorrea").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de rinorrea.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_respiratoria").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para dificultad respiratoria.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_hallazgo_otro").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para otro hallazgo.";
        }
    }
    
    if(jQuery.trim($("#edad").val())=="")
    {
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
    
    //    dirDisponible = $('#no_direccion_individuo').is(':checked');
    //    if(!dirDisponible){
    //        if(jQuery.trim($("#direccion_individuo").val())=="")
    //            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la direcci&oacute;n de la persona.";
    //    }
    
    //Antecedentes
    tarVacunaSi = $('#tarVacunaSi').is(':checked');
    tarVacunaNo = $('#tarVacunaNo').is(':checked');
    if(!(tarVacunaSi || tarVacunaNo) ){
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona porta tarjeta de vacuna."; 
    }
    vacEsquemaSi = $('#vacEsquemaSi').is(':checked');
    vacEsquemaNo = $('#vacEsquemaNo').is(':checked');
    if(tarVacunaSi){
        if(!(vacEsquemaSi || vacEsquemaNo) ){
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si corresponde a vacuna seg&uacute;n esquema."; 
        }
        
        if(jQuery.trim($("#fecha_ult_dosis").val())=="")
        {
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para la pen&uacute;ltima dosis de Anti Influenza.";
        }
        else
        {
            if(!(jQuery.trim($("#fecha_ult_dosis").val())=="99/99/9999" || //Arreglar con 99/99/9999
                jQuery.trim($("#fecha_ult_dosis").val())=="00/00/0000")){
                if(!isDate($("#fecha_ult_dosis").val().toString()))
                    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha pen&uacute;ltima dosis no tiene el formato adecuado.";
                else{
                    if(comparacionFechas($("#fecha_ult_dosis").val().toString(),fechaActualString()))
                        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha pen&uacute;ltima dosis no puede ser una fecha futura.";
                }
            }
        }
        
        if(jQuery.trim($("#fecha_anio_previo").val())=="")
        {
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para la dosis de Anti Influenza del a&ntilde;o previo.";
        }
        else
        {
            if(!(jQuery.trim($("#fecha_anio_previo").val())=="99/99/9999" || //Arreglar con 99/99/9999
                jQuery.trim($("#fecha_anio_previo").val())=="00/00/0000")){
                if(!isDate($("#fecha_anio_previo").val().toString()))
                    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del a&ntilde;o previo no tiene el formato adecuado.";
                else{
                    if(comparacionFechas($("#fecha_anio_previo").val().toString(),fechaActualString()))
                        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha del a&ntilde;o previo puede ser una fecha futura.";
                }
            }
        }
    }
    sexoEmb = $('#drpsexo').val();
    if(sexoEmb == 'F'){
        if($("#drpEmbarazo").val()==-1 
            && jQuery.trim($("#edad").val()) >= 10 
            && jQuery.trim($("#edad").val()) <= 50)
            ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona esta embarazada.";
        if($("#drpEmbarazo").val()==1){
            if($("#drpTrimestre").val()==-1)
                ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el trimestre de embarazo.";
        }
    }
    
    if($("#drpCronica").val()==-1)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tiene una enfermedad cr&oacute;nica.";
    hayEnfermedad = false;
    if($("#drpCronica").val()==1){
        enfermedades = $( "#globalEnfermedadesRelacionados" ).val().split("###");
        for(var i=0; i<enfermedades.length;i++){
            enfermedad = enfermedades[i].split("-");
            var idEnfermedad = enfermedad[0];
            var nombreEnfermedad = enfermedad[2];
            if(nombreEnfermedad == "1")
                nombreEnfermedad = enfermedad[3];
            
            var selectBox = "#drpResCronica"+idEnfermedad;
            if($(selectBox).val()==-1)
                ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tiene "+nombreEnfermedad; 
            if($(selectBox).val()==1)
                hayEnfermedad = true;
        }
        if(!hayEnfermedad){
            ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la opci&oacute;n 'Si' en alguna enfermedad cr&oacute;nica."; 
        }
    }
    
    if(vacEsquemaSi){
    //        if(jQuery.trim($("#num_dosis_flu").val())=="")
    //        {
    //            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse el N. dosis para Anti Influenza.";
    //        }
    //        else{
    //            if($("#num_dosis_flu").val()>0){
    //                if(jQuery.trim($("#fecha_vac_flu").val())=="")
    //                {
    //                    ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para Anti Influenza.";
    //                }
    //                else
    //                {
    //                    if(!isDate($("#fecha_vac_flu").val().toString()))
    //                        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para Anti Influenza no tiene el formato adecuado.";
    //                }
    //            }
    //        }
    }
    
    if($("#drpRiesgo").val()==-1)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el riesgo profesional.";
    
    if($("#drpRiesgo").val()==1){
        if($("#drpRiesgoCual").val()==-1)
            ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar cual es el riesgo profesional.";
    }
    
    if($("#drpViaje").val()==-1)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la historia de viaje 15 d&iacute;as antes.";
    
    if($("#drpViaje").val()==1){
        if($("#antecedentes_viaje_donde").val()== "")
            ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe llenar a donde viajo el paciente.";
    }
    
    if($("#drpContacto").val()==-1)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si conoce el contacto de caso confirmado.";
    
    if($("#drpContacto").val()==1){
        if($("#drpContactoTipo").val()==-1)
            ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de contacto.";
    }
    
    if($("#drpAislamiento").val()==-1)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si conoce la indicaci&oacute;n de aislamiento.";
    
    //Datos clinicos
    if(jQuery.trim($("#datos_clinicos_evento").val())=="")
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el diagn&oacute;stico cl&iacute;nico.";
    
    datosClinicosCheckGripal = $('#datosClinicosCheckGripal').is(':checked');
    datosClinicosCheckCentinela = $('#datosClinicosCheckCentinela').is(':checked');
    datosClinicosCheckInusitado = $('#datosClinicosCheckInusitado').is(':checked');
    datosClinicosCheckImprevisto = $('#datosClinicosCheckImprevisto').is(':checked');
    datosClinicosCheckExcesivo = $('#datosClinicosCheckExcesivo').is(':checked');
    datosClinicosCheckConglomerado = $('#datosClinicosCheckConglomerado').is(':checked');
    datosClinicosCheckNeumonia = $('#datosClinicosCheckNeumonia').is(':checked');
    
    if(!(datosClinicosCheckGripal || datosClinicosCheckCentinela || datosClinicosCheckInusitado || datosClinicosCheckImprevisto
        || datosClinicosCheckExcesivo || datosClinicosCheckConglomerado || datosClinicosCheckNeumonia) ){
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de evento en estudio."; 
    }
    else{
        if(datosClinicosCheckGripal){
            //            if($("#drpTipoPaciente" ).val()==2){
            //                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- El paciente tiene sindrome gripal y tiene como tipo de paciente hospitalizado."; 
            //            }
            if(hospitalizadoSi){
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- El paciente tiene sindrome gripal y esta hospitalizado por IRAG.";
            }
            if(jQuery.trim($("#fecha_hospitalizacion").val())!="")
            {
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- El paciente tiene sindrome gripal y tiene una fecha de hospitalizaci&oacute;n.";
            }
        }
    }
    
    if(jQuery.trim($("#fecha_inicio_sintomas").val())=="")
    {
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para inicio de s&iacute;ntomas.";
    }
    else
    {
        if(!isDate($("#fecha_inicio_sintomas").val().toString()))
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para inicio de s&iacute;ntomas no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_inicio_sintomas").val().toString(),fechaActualString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para inicio de s&iacute;ntomas no puede ser una fecha futura.";
        }
    }
    calcularSemanaEpi();
    if(hospitalizadoSi){
        if(jQuery.trim($("#fecha_hospitalizacion").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para hospitalizaci&oacute;n.";
        }
        else
        {
            if(!isDate($("#fecha_hospitalizacion").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para hospitalizaci&oacute;n no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_hospitalizacion").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para hospitalizaci&oacute;n no puede ser una fecha futura.";
            }
        }
    }else{
        if(jQuery.trim($("#fecha_hospitalizacion").val())!=""){
            if(comparacionFechas($("#fecha_hospitalizacion").val().toString(),fechaActualString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para hospitalizaci&oacute;n no puede ser una fecha futura.";
        }
    }
    
    if(jQuery.trim($("#fecha_notificacion").val())=="")
    {
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para notificaci&oacute;n.";
    }
    else
    {
        if(!isDate($("#fecha_notificacion").val().toString()))
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para notificaci&oacute;n no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_notificacion").val().toString(),fechaActualString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para notificaci&oacute;n no puede ser una fecha futura.";
        }
    }
    
    if(jQuery.trim($("#fecha_egreso").val())!="")
    {
        if(!isDate($("#fecha_egreso").val().toString()))
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de egreso no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_egreso").val().toString(),fechaActualString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de egreso no puede ser una fecha futura.";
        }
    }
    
    if(jQuery.trim($("#fecha_defuncion").val())!="")
    {
        if(!isDate($("#fecha_defuncion").val().toString()))
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de defuncion no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_defuncion").val().toString(),fechaActualString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de defuncion no puede ser una fecha futura.";
        }
    }
    
    if($("#drpAntiUltimaSemana").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar uso de antibi&oacute;ticos &uacute;ltima semana.";
    
    if($("#drpAntiUltimaSemana").val()==1){
        if($("#antibioticosCual").val()=="")
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar cual antibi&oacute;tico se uso.";
        if(jQuery.trim($("#antibioticosFecha").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para antibi&oacute;ticos.";
        }
        else
        {
            if(!isDate($("#antibioticosFecha").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para antibi&oacute;ticos no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#antibioticosFecha").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para antibi&oacute;ticos no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpAntivirales").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar uso de antivirales.";
    
    if($("#drpAntivirales").val()==1){
        if($("#antiviralesCual").val()=="")
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar cual antiviral se uso.";
        if(jQuery.trim($("#antiviralesFecha").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para antivirales.";
        }
        else
        {
            if(!isDate($("#antiviralesFecha").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para antivirales no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#antiviralesFecha").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para antivirales no puede ser una fecha futura.";
            }
        }
    }
    if($("#drpFiebre").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo fiebre.";
    
    if($("#drpFiebre").val()==1){
        if(jQuery.trim($("#fecha_fiebre").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para la fiebre.";
        }
        else
        {
            if(!isDate($("#fecha_fiebre").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para la fiebre no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_fiebre").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para la fiebre no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpTos").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo tos.";
    
    if($("#drpTos").val()==1){
        if(jQuery.trim($("#fecha_tos").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para la tos.";
        }
        else
        {
            if(!isDate($("#fecha_tos").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para la tos no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_tos").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para la tos no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpGarganta").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo dolor de garganta.";
    
    if($("#drpGarganta").val()==1){
        if(jQuery.trim($("#fecha_garganta").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para dolor de garganta.";
        }
        else
        {
            if(!isDate($("#fecha_garganta").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para dolor de garganta no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_garganta").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para dolor de garganta no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpRinorrea").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo rinorrea.";
    
    if($("#drpRinorrea").val()==1){
        if(jQuery.trim($("#fecha_rinorrea").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para rinorrea.";
        }
        else
        {
            if(!isDate($("#fecha_rinorrea").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para rinorrea no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_rinorrea").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para rinorrea no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpRespiratoria").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo dificultad respiratoria.";
    
    if($("#drpRespiratoria").val()==1){
        if(jQuery.trim($("#fecha_respiratoria").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para dificultad respiratoria.";
        }
        else
        {
            if(!isDate($("#fecha_respiratoria").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para dificultad respiratoria no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_respiratoria").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para dificultad respiratoria no puede ser una fecha futura.";
            }
        }
    }
    
    if($("#drpHallazgoOtro").val()==-1)
        ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona tuvo otro hallazgo.";
    
    if($("#drpHallazgoOtro").val()==1){
        if(jQuery.trim($("#fecha_hallazgo_otro").val())=="")
        {
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha para otro hallazgo.";
        }
        else
        {
            if(!isDate($("#fecha_hallazgo_otro").val().toString()))
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para otro hallazgo no tiene el formato adecuado.";
            else{
                if(comparacionFechas($("#fecha_hallazgo_otro").val().toString(),fechaActualString()))
                    ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha para otro hallazgo no puede ser una fecha futura.";
            }
        }
        if($("#hallazgoOtroNombre").val()==""){
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar cual es el otro hallazgo.";
        }
    }
    
    if(datosClinicosCheckNeumonia){
        if($("#drpCondensacion").val()==-1)
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la condensaci&oacute;n.";
        if($("#drpPleural").val()==-1)
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la derrame pleural:.";
        if($("#drpBroncograma").val()==-1)
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el broncograma a&eacute;reo.";
        if($("#drpInfiltrado").val()==-1)
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el infiltrado intersticial:.";
        if($("#drpResultadoOtro").val()==-1)
            ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si se tiene otro resultado de radiograf&iacute;a de t&oacute;rax.";
        if($("#drpResultadoOtro").val()==1){
            if($("#resultadoOtroNombre").val()==""){
                ErroresD+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la descripci&oacute;n para otro resultado de radiograf&iacute;a de t&oacute;rax..";
            }
        }  
    }
    
    (ErroresN=="")? ErroresNotificacion="": ErroresNotificacion = ErroresNotificacion+ErroresN + "<br/>";
    (ErroresI=="")? ErroresIndividuo="": ErroresIndividuo = ErroresIndividuo+ErroresI + "<br/>";
    (ErroresA=="")? ErroresAntecedentes="": ErroresAntecedentes = ErroresAntecedentes+ErroresA + "<br/>";
    (ErroresF=="")? ErroresFactores="": ErroresFactores = ErroresFactores+ErroresF + "<br/>";
    (ErroresD=="")? ErroresDatos="": ErroresDatos = ErroresDatos+ErroresD + "<br/>";
    Message = ErroresNotificacion + ErroresIndividuo + ErroresAntecedentes + ErroresFactores + ErroresDatos;
    
    //Message= "";
    if(Message!="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#guardarPrevio").val('1');
        $("#dSummaryErrors").css('display','none');
        $('#nombreRegistra').attr('readonly', false);
        $('#nombreRegistra').attr('disabled', '');
        $('#drpTipoId').attr('readonly', false);
        $('#drpTipoId').attr('disabled', '');
        $('#no_identificador').attr('readonly', false);
        $('#no_identificador').attr('disabled', '');
        
        var param = '';
        
        for(i=0; i<globalMuestrasUceti.length;i++){
            if(__isset(globalMuestrasUceti[i])){
                param+=globalMuestrasUceti[i][0]+"-"
                +globalMuestrasUceti[i][2]+"-" //Fecha toma
                +globalMuestrasUceti[i][3]+"-" //Fecha envio
                +globalMuestrasUceti[i][4];//Fecha recibo Lab
            }
        }
        $('#globalMuestrasUceti').val(param);
        //        alert($('#globalMuestrasUceti').val());
        
        var nuevo = '';
        if($('#action').val()=='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de Influenza, \xbfdesea continuar?';
        }
        else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de Influenza, \xbfdesea continuar?';
        if(confirm(nuevo)){
            //            alert($("#globalMuestras").val());
            $("#globalMuestras").val($("#globalMuestras").val()+globalMuestrasSilab);
            $("#globalPruebas").val($("#globalPruebas").val()+globalPruebasSilab);
            //            alert($("#globalMuestras").val());
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

function guardadoPrevioUceti(updateMuestra){
    
    var Message = '';
    var ErroresI = '';
    var ErroresIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos del paciente requeridos para Guardar previamente:';
    
    //Individuo
    if($("#drpTipoId").val()==0){
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de identificador."; 
    }
    
    if(jQuery.trim($("#no_identificador").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
    else{
        if($("#drpTipoId").val()== 1 || $("#drpTipoId").val()== 5){
            if(!validarCedula(jQuery.trim($("#no_identificador").val())))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La c\xe9dula paname\xf1a no tiene el formato esperado, debe tener por los menos dos guiones '-' y deben ser n&uacute;meros, ej: 10-123-4567 o 8-123-4567 ";
        }
    }
    
    if(jQuery.trim($("#primer_nombre").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer nombre.";
    if(jQuery.trim($("#primer_apellido").val())=="")
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";
    
    if(jQuery.trim($("#fecha_nacimiento").val())=="")
    {
        if(jQuery.trim($("#edad").val())!="")
            calcularFechaNacimiento();
        else{
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de nacimiento.";
        }
    }
    else
    {
        if(!isDate($("#fecha_nacimiento").val().toString()))
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#fecha_nacimiento").val().toString(),fechaActualString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser una fecha futura.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_ult_dosis").val().toString())){
                //alert($("#fecha_ult_dosis").val().toString());
                if( $("#fecha_ult_dosis").val().toString() != "00/00/0000")
                    ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha &uacute;ltima dosis.";
            }
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_defuncion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de defuncion.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_egreso").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de egreso.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_notificacion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para notificaci&oacute;n.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_hospitalizacion").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de hospitalizaci&oacute;n.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_inicio_sintomas").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para inicio de s&iacute;ntomas.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#antiviralesFecha").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de antivirales.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#antibioticosFecha").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para antibi&oacute;ticos.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_fiebre").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de fiebre.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_tos").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de tos.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_garganta").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para dolor de garganta.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_rinorrea").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha de rinorrea.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_respiratoria").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para dificultad respiratoria.";
            else if(comparacionFechas($("#fecha_nacimiento").val().toString(),$("#fecha_hallazgo_otro").val().toString()))
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no puede ser mayor que la fecha para otro hallazgo.";
        }
    }
    
    if(jQuery.trim($("#edad").val())=="")
    {
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
    
    (ErroresI=="")? ErroresIndividuo="": ErroresIndividuo = ErroresIndividuo+ErroresI + "<br/>";
    Message = ErroresIndividuo;
    
    if(Message!="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        calcularSemanaEpi();
        if(updateMuestra==1)
            $("#guardarPrevio").val('2');
        else
            $("#guardarPrevio").val('0');
        $("#dSummaryErrors").css('display','none');
        $('#nombreRegistra').attr('readonly', false);
        $('#nombreRegistra').attr('disabled', '');
        $('#drpTipoId').attr('readonly', false);
        $('#drpTipoId').attr('disabled', '');
        $('#no_identificador').attr('readonly', false);
        $('#no_identificador').attr('disabled', '');
        
        var param = '';
        
        for(i=0; i<globalMuestrasUceti.length;i++){
            if(__isset(globalMuestrasUceti[i])){
                param+=globalMuestrasUceti[i][0]+"-"
                +globalMuestrasUceti[i][2]+"-" //Fecha toma
                +globalMuestrasUceti[i][3]+"-" //Fecha envio
                +globalMuestrasUceti[i][4];//Fecha recibo Lab
            }
        }
        $('#globalMuestrasUceti').val(param);
        
        if(updateMuestra==0){
            nuevo = 'A continuaci\xf3n se guardar\xe1 previamente los datos del Formulario de Influenza, \xbfdesea continuar?';
        
            if(confirm(nuevo)){
                $("#globalMuestras").val($("#globalMuestras").val()+globalMuestrasSilab);
                $("#globalPruebas").val($("#globalPruebas").val()+globalPruebasSilab);
            
                $("#dSummaryErrors").css('display','none');
                $('#frmContenido').submit();
            }
        }
        if(updateMuestra==1){
            $("#globalMuestras").val($("#globalMuestras").val()+globalMuestrasSilab);
            $("#globalPruebas").val($("#globalPruebas").val()+globalPruebasSilab);
            
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

function calcularSemanaEpi(){
    if (jQuery.trim($("#fecha_inicio_sintomas").val())!='')
    {
        if(isDate($("#fecha_inicio_sintomas").val()))
        {
            var unidad = $("#fecha_inicio_sintomas").val().split("/");
            var dia = unidad[0];
            var mes = unidad[1];
            var anio = unidad[2];
            var fsintomas = new Date(anio,mes - 1,dia);
            semanaEpi = fsintomas.getWeek(0);
            
            //$("#semana_epi").html(semanaEpi);
            $("#semana_epi").val(semanaEpi);
            $("#anio").val(anio);
        }
        else{
            $("#semana_epi").val('');
            $("#anio").val('');
        }
    }
}

function Tooltip_field(){
    $('#fecha_ult_dosis').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'La fecha de la pen&uacute;ltima dosis Anti Influenza puede ser tambi&eacute;n:<br/>00/00/0000: No recibida<br/>99/99/9999: se desconoce',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    $('#fecha_anio_previo').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'La fecha de dosis Anti Influenza a&ntilde;o previo puede ser tambi&eacute;n:<br/>00/00/0000: No recibida<br/>99/99/9999: se desconoce',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    $('#datosClinicosCheckGripal').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Recuerde que si selecciona sindrome gripal el paciente no puede estar hospitalizado por IRAG',
        show: 'mouseover',
        hide: 'mouseout'
    });
    
    $('#datosClinicosCheckNeumonia').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Al seleccionar una neumon&iacute;a bacteriana, puede seleccionar opcionalmente una IRAG, solo si el tipo de evento lo aplica.',
        show: 'mouseover',
        hide: 'mouseout'
    });
    
    $('#no_identificador').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Recuerde que el formato para la c&eacute;dula paname&ntilde;a es el siguiente:<br/>xx-xxx-xxxx ej: 10-123-4567<br/> x-xxx-xxxx ej: 8-123-4567',
        show: 'mouseover',
        hide: 'mouseout'
    });
}

