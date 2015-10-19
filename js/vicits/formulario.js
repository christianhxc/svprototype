var globalSintomasSignosRelacionados = new Array();
var tablaSintomasSignos = "tablaSintomasSignos";
var globalAntibioticosRelacionados = new Array();
var tablaAntibioticos = "tablaAntibioticos";
var globalDiagnosticosTratamientoRelacionados = new Array();
var tablaDiagnosticoTratamiento = "tablaDiagnosticoTratamiento";

var globalITSRelacionados = new Array();
var globalDrogasRelacionadas = new Array();

$(function() {
    $( "#fecha_nacimiento" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1920:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#fechaVih" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        yearRange: "1980:"+new Date().getFullYear() ,
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(function() {
    $( "#noti_fecha_consulta" ).datepicker({
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

$(document).ready(function() {
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
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
    
    sexo = $('#drpsexo').val();
    if(sexo!='0'){
        if(sexo == 'F'){
            validarTipoTS();
        }else if(sexo == 'M'){
            validarTipoHSH();
        }
        $( "#dialogFormTipoFormulario" ).dialog({
            autoOpen: false,
            height: 200,
            width: 450,
            modal: true,
            position: 'center',
            closeOnEscape: false,
            dialogClass: 'hide-close'
        });
    }else{        
        $( "#dialogFormTipoFormulario" ).dialog({
            autoOpen: true,
            height: 200,
            width: 450,
            modal: true,
            position: 'center',
            closeOnEscape: false,
            dialogClass: 'hide-close'
        });
    }
    
    // Divide en tabs el ingreso de los datos
    
    $("#tabs").tabs({
        selected:0, 
        select:function(event, ui){
            if(ui.index==3)
                $('#next').html("Inicio");
            else
                $('#next').html("Siguiente");
        }
    });
   
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
    
    $( "#noti_unidad_notificadora" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#noti_unidad_notificadora").val($("<div>").html(li.selectValue).text());
            $("#noti_id_un").val(li.extra[0]);
        },
        autoFill:false
    });
    
    var abuso = $("#drpAbusoSexual").val();
    if (abuso != 1){
        $("#AbusoSexual1").css( "display", "none" );
        $("#AbusoSexual2").css( "display", "none" );
        $("#AbusoSexual3").css( "display", "none" );
    }
    var trabajoSexual = $("#drpTrabajoSexual").val();
    if (trabajoSexual != 1){
        $("#divTS1").css( "display", "none" ); 
        $("#divTS4").css( "display", "none" );
        $("#divTS6").css( "display", "none" );
        $("#divTS7").css( "display", "none" ); 
        $("#divTS8").css( "display", "none" );
    }
    var actualTS = $("#drpActualTrabajoSexual").val();
    if (actualTS != 1)
        $("#divTS2").css( "display", "none" ); 
    var tiempoTS = $("#drpTSTiempo").val();
    if (tiempoTS != 4)
        $("#divTS3").css( "display", "none" );
    var tiempoTS = $("#drpTSOtroPais").val();
    if (tiempoTS != 1)
        $("#divTS5").css( "display", "none" );
    
    $("#divCualITS").css( "display", "none" );
    var tieneVIH = $("#drpVIH").val();
    if (tieneVIH != 1){
        $("#divFechaVIH").css( "display", "none" );
        $("#divPreVIH").css( "display", "none" );
        $("#divPosVIH").css( "display", "none" );
    }
    var clinicaTARV = $("#drpTARV").val();
    if (clinicaTARV != 1)
        $("#divClinica").css( "display", "none" );
    var clinicaTARV = $("#drpConsumoAlcohol").val();
    if (clinicaTARV != 1)
        $("#divFrecuenciaAlcohol").css( "display", "none" );
    
    $("#preservativos").css( "display", "none" );
    
    $("#ginecoEmbarazos").attr('disabled', true);
    calendarios();
    sexoCambio();
    entregoPreservativos();
    mostrarAntibiotico();
    inicializarTabla(tablaSintomasSignos);
    inicializarTabla(tablaAntibioticos); 
    inicializarTabla(tablaDiagnosticoTratamiento);
    individuo($("#drpTipoId").val(), $("#no_identificador").val());
    if ($("#globalITSRelacionados" ).val()!="")
        llenarITS();
    if ($("#globalDrogasRelacionadas" ).val()!="")
        llenarDrogas();
    
});

function validarTipoTS(){
    $("#dialogFormTipoFormulario").dialog('close');
    $('#drpsexo').val("F");
    $("#drpsexo").attr('disabled', true);
    ocultarHombre();
    mostrarMujer();
    sexoCambio();
    buscar();
}

function validarTipoHSH(){
    $("#dialogFormTipoFormulario").dialog('close');
    $('#drpsexo').val("M");
    $("#drpsexo").attr('disabled', true);
    mostrarHombre();
    ocultarMujer();
    sexoCambio();
    buscar();
}

function mostrarHombre(){
    $("#soloHombre1").css( "display", "" ); 
}

function ocultarHombre(){
    $("#soloHombre1").css( "display", "none" ); 
    $("#nombre_identitdad").val("");
}

function mostrarMujer(){
    $("#soloMujer1").css( "display", "" ); 
    $("#soloMujer2").css( "display", "" );
    $("#soloMujer3").css( "display", "" );
    $("#soloMujer4").css( "display", "" ); 
    $("#soloMujer5").css( "display", "" );
    var anticonceptivo =  $("#drpAnticonceptivo").val();
    if (anticonceptivo != 1){
        $("#soloMujer3").css( "display", "none" );
        $("#soloMujer4").css( "display", "none" ); 
        $("#soloMujer5").css( "display", "none" );
    }
    $("#soloMujer6").css( "display", "" ); 
    $("#soloMujer7").css( "display", "" );
    $("#soloMujer8").css( "display", "" ); 
    $("#soloMujer9").css( "display", "" ); 
}

function ocultarMujer(){
    $("#soloMujer1").css( "display", "none" ); 
    $("#soloMujer2").css( "display", "none" ); 
    $("#soloMujer3").css( "display", "none" );
    $("#soloMujer4").css( "display", "none" ); 
    $("#soloMujer5").css( "display", "none" ); 
    $("#soloMujer6").css( "display", "none" ); 
    $("#soloMujer7").css( "display", "none" );
    $("#soloMujer8").css( "display", "none" ); 
    $("#soloMujer9").css( "display", "none" ); 
    $("#drpAnticonceptivo").val(0);
    $("#ginecoMenarquia").val("");
    $("#ginecoAbortos").val("");
    $("#ginecoVivos").val("");
    $("#ginecoMuertos").val("");
    $("#ginecoEmbarazos").val("");
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
        + "&ted="+($("#drpPopTipo").val()==0?"":$("#drpPopTipo").val()) + "&sx="+($("#drpsexo").val()==0?"":$("#drpsexo").val())
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

                $("#drpPaisIndividuo").val(partes[25]);
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
                if ($("#action").val()!="R"&&$("#action").val()!="M")
                    datosAntecedentes(tipoId, idP);
//                sexoEmbarazo();
            }
            else
                found = false;
        }
    });    
}

function datosAntecedentes(tipoId, idP){
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/vicits/datosAntecedentes.php',
        data: "tipo_id="+tipoId+"&id="+ idP,
        success: function(data)
        {
            $("#otroAnticonceptivo").val("");
            $("#otroAnticonceptivo").hide();
            if (data !="no"){
                var partes = data.toString().split('#-#-#');
                var dataForm = partes[0].split("#-#");
                
                $("#drpAbusoSexual").val(dataForm[0]);
                if (dataForm[0] == 1){
                    $("#edad_AS").val(dataForm[1]);
                    $("#drpAbusoSexual12").val(dataForm[2]);
                    $("#AbusoSexual1").show();
                    $("#AbusoSexual2").show();
                    $("#AbusoSexual3").show();
                }
                $("#vida_sexual").val(dataForm[3]);
                $("#drpTrabajoSexual").val(dataForm[4]);
                if (dataForm[4] == 1){
                    $("#drpActualTrabajoSexual").val(dataForm[5]);                    
                    $("#divTS1").show();
                    if (dataForm[5] == 1){
                        $("#drpTSTiempo").val(dataForm[6]); 
                        $("#divTS2").show();
                        if (dataForm[6]==4){
                            $("#cuanto_TS").val(dataForm[7]); 
                            $("#divTS3").show();
                        }
                    }
                    $("#drpTSOtroPais").val(dataForm[8]); 
                    $("#divTS4").show();
                    if (dataForm[8] == 1){
                        $("#drpPaisTS").val(dataForm[9]); 
                        $("#divTS5").show();
                    }
                    $("#divTS6").show();
                    $("#divTS7").show();
                    $("#divTS8").show();
                }
                $("#drpRelacionSexual").val(dataForm[10]); 
                $("#drpItsUltimo").val(dataForm[11]); 
                if (dataForm[11] == 1)
                    $("#divCualITS").show();
                $("#nombreLugarTS").val(dataForm[12]); 
                $("#drpTipoLugarTS").val(dataForm[13]); 
                
                $("#drpVIH").val(dataForm[14]); 
                if (dataForm[14] == 1) {
                    var fechaVih = dataForm[15].split("-");
                    $("#fechaVih").val(fechaVih[2]+"/"+fechaVih[1]+"/"+fechaVih[0]);
                    $("#drpPreVIH").val(dataForm[16]); 
                    $("#drpPosVIH").val(dataForm[17]); 
                    $("#divFechaVIH").show();
                    $("#divPreVIH").show();
                    $("#divPosVIH").show();
                }
                $("#drpTARV").val(dataForm[18]); 
                if (dataForm[18] == 1){
                    $("#drpClinicaTarv").val(dataForm[19]); 
                    $("#divClinica").show();
                }
                $("#drpConsumoAlcohol").val(dataForm[20]); 
                if (dataForm[20] == 1){
                    $("#drpFrecuenciaAlcohol").val(dataForm[21]); 
                    $("#divFrecuenciaAlcohol").show();
                }
                
                //******************* SOLO MUJERES **********************
                if ($("#drpsexo").val() == "F"){
                    $("#drpAnticonceptivo").val(dataForm[22]); 
                    if (dataForm[22] == 1){
                        var check = (dataForm[23] == 1) ? true:false;
                        $("#checkDiu").attr('checked',check);
                        check = (dataForm[24] == 1) ? true:false;
                        $("#checkPildora").attr('checked',check);
                        check = (dataForm[25] == 1) ? true:false;
                        $("#checkCondon").attr('checked',check);
                        check = (dataForm[26] == 1) ? true:false;
                        $("#checkInyeccion").attr('checked',check);
                        check = (dataForm[27] == 1) ? true:false;
                        $("#checkEsteril").attr('checked',check);
                        if (dataForm[28] == 1){
                            $("#checkOtro").attr('checked',true);
                            $("#otroAnticonceptivo").val(dataForm[29]); 
                            $("#otroAnticonceptivo").show();
                        }
                        $("#soloMujer3").show();
                        $("#soloMujer4").show();
                        $("#soloMujer5").show();
                    }
                    $("#ginecoMenarquia").val(dataForm[30]); 
                    $("#ginecoAbortos").val(dataForm[31]); 
                    $("#ginecoVivos").val(dataForm[32]); 
                    $("#ginecoMuertos").val(dataForm[33]); 
                    $("#ginecoEmbarazos").val(dataForm[34]); 
                    $("#soloMujer6").show();
                    $("#soloMujer7").show();
                    $("#soloMujer8").show();
                }
                $("#drpSabeLeer").val(dataForm[35]); 
                $("#globalITSRelacionados").val(partes[1]);
                $("#globalDrogasRelacionadas").val(partes[2]);
                if ($("#globalITSRelacionados" ).val()!="")
                    llenarITS();
                if ($("#globalDrogasRelacionadas" ).val()!="")
                    llenarDrogas();
            }
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
    //    $("#drpsexo").val(0);
       
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
}

function setPais(){
    var pais = $("#drpPaisIndividuo").val();
    if (pais==174){
        $("#divPolitica1").css( "display", "" ); 
        $("#divPolitica2").css( "display", "" ); 
        $("#divPolitica3").css( "display", "" ); 
    }
    else{
        $("#divPolitica1").css( "display", "none" ); 
        $("#divPolitica2").css( "display", "none" ); 
        $("#divPolitica3").css( "display", "none" );
    }
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

function abusoSexual(){
    if ($("#drpAbusoSexual").val() == 1){
        $("#AbusoSexual1").css( "display", "" );
        $("#AbusoSexual2").css( "display", "" );
        $("#AbusoSexual3").css( "display", "" );
    }
    else {
        $("#AbusoSexual1").css( "display", "none" );
        $("#AbusoSexual2").css( "display", "none" );
        $("#AbusoSexual3").css( "display", "none" );
    }
}

function ejercioTS(){
    var ejercio = $("#drpTrabajoSexual").val();
    if (ejercio == 1){
        $("#divTS1").css( "display", "" ); 
        $("#divTS2").css( "display", "none" ); 
        $("#divTS3").css( "display", "none" );
        $("#divTS4").css( "display", "" );
        $("#divTS5").css( "display", "none" );
        $("#divTS6").css( "display", "" );
        $("#divTS7").css( "display", "" ); 
        $("#divTS8").css( "display", "" );
    }
    else{
        $("#divTS1").css( "display", "none" ); 
        $("#divTS2").css( "display", "none" ); 
        $("#divTS3").css( "display", "none" );
        $("#divTS4").css( "display", "none" );
        $("#divTS5").css( "display", "none" );
        $("#divTS6").css( "display", "none" );
        $("#divTS7").css( "display", "none" ); 
        $("#divTS8").css( "display", "none" );
        $("#drpActualTrabajoSexual").val(0);
        $("#drpTSOtroPais").val(0);
        $("#nombreLugarTS").val("");
        $("#drpTipoLugarTS").val(0);
    }
}

function actualTS(){
    var actualTS = $("#drpActualTrabajoSexual").val();
    if (actualTS == 1){
        $("#divTS2").css( "display", "" ); 
        $("#divTS3").css( "display", "none" );
    }
    else{
        $("#divTS2").css( "display", "none" ); 
        $("#divTS3").css( "display", "none" );
        $("#drpTSTiempo").val(0);
    }
}

function tiempoTS(){
    var actualTS = $("#drpTSTiempo").val();
    if (actualTS == 4){
        $("#divTS3").css( "display", "" );
    }
    else{ 
        $("#divTS3").css( "display", "none" );
        $("#cuanto_TS").val("");
    }
}

function otroPaisTS(){
    var otroPaisTS = $("#drpTSOtroPais").val();
    if (otroPaisTS == 1){
        $("#divTS5").css( "display", "" );
    }
    else{ 
        $("#divTS5").css( "display", "none" );
        $("#drpPaisTS").val(174);
    }
}

function cualITS(){
    $("#drpItsUltimo").val() == 1 ? $("#divCualITS").css("display", "") : $("#divCualITS").css("display", "none");
}

function clinicaTarv(){
    $("#drpTARV").val() == 1 ? $("#divClinica").css("display", "") : $("#divClinica").css("display", "none");
}

function diagVIH(){
    if ($("#drpVIH").val() == 1 ){
        $("#divFechaVIH").css("display", "");
        $("#divPreVIH").css("display", "");
        $("#divPosVIH").css("display", "");
    }
    else {
        $("#divFechaVIH").css("display", "none");
        $("#divPreVIH").css("display", "none");
        $("#divPosVIH").css("display", "none");
        $("#fechaVih").val("");
        $("#drpPreVIH").val(0);
        $("#drpPosVIH").val(0);
    }
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

function entregoPreservativos(){
    ($("#drpDxPreservativos").val() == 1) ? $("#preservativos").css( "display", "" ) : $("#preservativos").css( "display", "none" );
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

//
//Relacionar ITS
function relacionarITS(){
    var idITS = $("#drpITS").val();
    var nombreITS = $("#drpITS").find(":selected").text();    
    if (idITS !="" && idITS != 0){
        $("#drpITS").val(0);
        var tmpReg = globalITSRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idITS == globalITSRelacionados[i][0] || "###"+idITS == globalITSRelacionados[i][0]))
                flag = false;
        }
        if (flag){
            idITS = (tmpReg==0) ? idITS : "###"+idITS;
            globalITSRelacionados[tmpReg] = new Array(idITS,nombreITS);
            crearTablaITS();
        }
        else
            alert ("Ya existe un registro para esta relacion");
    }
    else
        alert("Debe seleccionar una ITS");
}

function crearTablaITS(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="2" border="0" width="80%" align="center">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Nombre ITS  </th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalITSRelacionados.length;i++){
        if(__isset(globalITSRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="250px">'+globalITSRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelITS('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divRelITS").html(tabla);
}

function eliminarRelITS(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con la ITS "+globalITSRelacionados[pos][1])){
        globalITSRelacionados.splice(pos, 1);
        crearTablaITS();
    }
}

function llenarITS(){
    var itsRelacionadas = $( "#globalITSRelacionados" ).val().split("###");
    for(var i=0; i<itsRelacionadas.length;i++){
        var its = itsRelacionadas[i].split("-");
        llenarITSSola(its);
    }
    crearTablaITS();
}

function llenarITSSola(its){
    var itsId = its[0];
    var itsNombre = its[1];
    if (itsId !="" && itsNombre != 0){
        var tmpReg = globalITSRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (itsId == globalITSRelacionados[i][0] || "###"+itsId == globalITSRelacionados[i][0])
                flag = false;
        }
        if (flag){
            itsId = (tmpReg==0) ? itsId : "###"+itsId;
            globalITSRelacionados[tmpReg] = new Array(itsId,itsNombre);
        }
    }
}

function consumioAlcohol(){
    if ($("#drpConsumoAlcohol").val() == 1 )
        $("#divFrecuenciaAlcohol").css("display", "")
    else {
        $("#divFrecuenciaAlcohol").css("display", "none");
        $("#drpFrecuenciaAlcohol").val(0);
    }
}
function relacionarDrogas(){
    var idDroga = $("#drpDrogas").val();
    var nombreDroga = $("#drpDrogas").find(":selected").text();
    var idTiempo = $("#drpTiempo").val();
    var nombreTiempo = $("#drpTiempo").find(":selected").text();    
    if (idDroga !="" && idTiempo != 0){
        $("#drpDrogas").val(0);
        $("#drpTiempo").val(0);
        var tmpReg = globalDrogasRelacionadas.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idDroga == globalDrogasRelacionadas[i][0] || "###"+idDroga == globalDrogasRelacionadas[i][0]) && (idTiempo == globalDrogasRelacionadas[i][2]))
                flag = false;
        }
        if (flag){
            idDroga = (tmpReg==0) ? idDroga : "###"+idDroga;
            globalDrogasRelacionadas[tmpReg] = new Array(idDroga,nombreDroga,idTiempo,nombreTiempo);
            if (idTiempo==2){
                idDroga = (tmpReg==0) ? "###"+idDroga : idDroga ;
                globalDrogasRelacionadas[tmpReg+1] = new Array(idDroga,nombreDroga,1,"12 meses");
            }
            crearTablaDrogas();
        }
        else
            alert ("Ya existe un registro para esta relacion");
    }
    else
        alert("Debe seleccionar una Droga y tiempo de consumo");
}

function crearTablaDrogas(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="2" border="0" width="80%" align="center">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Nombre Droga </th>'+
    '<th class="dxgvHeader_PlasticBlue">Tiempo </th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalDrogasRelacionadas.length;i++){
        if(__isset(globalDrogasRelacionadas[i])){
            tabla += '<tr>'+
            '<td class="fila" width="240px">'+globalDrogasRelacionadas[i][1]+'</td>'+
            '<td class="fila" width="100px">'+globalDrogasRelacionadas[i][3]+'</td>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelDroga('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></td>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divRelDrogas").html(tabla);
}

function eliminarRelDroga(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con la Droga: "+globalDrogasRelacionadas[pos][1])){
        globalDrogasRelacionadas.splice(pos, 1);
        crearTablaDrogas();
    }
}

function llenarDrogas(){
    var drogasRelacionadas = $( "#globalDrogasRelacionadas" ).val().split("###");
    for(var i=0; i<drogasRelacionadas.length;i++){
        var droga = drogasRelacionadas[i].split("#-#");
        llenarDrogaSSola(droga);
    }
    crearTablaDrogas();
}

function llenarDrogaSSola(droga){
    var idDroga = droga[0];
    var nombreDroga = droga[1];
    var idTiempoConsumo = droga[2];
    var nombreTiempoConsumo = droga[3];
    if (nombreDroga !="" && idDroga != 0 && nombreTiempoConsumo !="" && idTiempoConsumo != 0){
        var tmpReg = globalDrogasRelacionadas.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idDroga == globalDrogasRelacionadas[i][0] || "###"+idDroga == globalDrogasRelacionadas[i][0]) && (idTiempoConsumo == globalDrogasRelacionadas[i][2]))
                flag = false;
        }
        if (flag){
            idDroga = (tmpReg==0) ? idDroga : "###"+idDroga;
            globalDrogasRelacionadas[tmpReg] = new Array(idDroga,nombreDroga,idTiempoConsumo,nombreTiempoConsumo);
        }
    }
}

function usoAnticonceptivo(){
    if ($("#drpAnticonceptivo").val()==1){
        $("#soloMujer3").css( "display", "" );
        $("#soloMujer4").css( "display", "" ); 
        $("#soloMujer5").css( "display", "" );
        if($("#checkOtro").is(':checked'))
            $("#otroAnticonceptivo").hide(); 
    }
    else{
        $("#soloMujer3").css( "display", "none" );
        $("#soloMujer4").css( "display", "none" ); 
        $("#soloMujer5").css( "display", "none" ); 
        $("#checkDiu").attr('checked',false);
        $("#checkPildora").attr('checked',false);
        $("#checkCondon").attr('checked',false);
        $("#checkInyeccion").attr('checked',false);
        $("#checkEsteril").attr('checked',false);
        $("#checkOtro").attr('checked',false);
    }
}

function nombreOtroAnticonceptivo(){
    if($("#checkOtro").is(':checked'))
        $("#otroAnticonceptivo").show();
    else{
        $("#otroAnticonceptivo").hide();
        $("#otroAnticonceptivo").val("");
    }
}

function totalEmbarazo(){
    var abortos = 0;
    var vivos = 0;
    var muertos = 0;
    if ($("#ginecoAbortos").val() != "")
        abortos = parseInt($("#ginecoAbortos").val());
    if ($("#ginecoVivos").val() != "")
        vivos = parseInt($("#ginecoVivos").val());
    if ($("#ginecoMuertos").val() != "")
        muertos = parseInt($("#ginecoMuertos").val());
    var total = abortos + vivos + muertos;
    $("#ginecoEmbarazos").val(total);
}



function validarVicIts(){
    var Message = '';
    var ErroresI = '';
    var ErroresA = '';
    var ErroresPA = '';
    var ErroresPB = '';
    var ErroresN = '';
    var ErrorIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
    var ErrorAntecedentes ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Antecedentes:';
    var ErrorNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Notificaci&oacute;n:';
    
    var ErrorParteA ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Parte A:';
    var ErrorParteB ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Parte B:';
    
    //Individuo
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
    if($("#drpPaisIndividuo").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el pa&iacute;s de la persona.";
    if ($("#drpPaisIndividuo").val()==174){
        if($("#drpProIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia de la persona.";
        if($("#drpRegIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n de la persona.";
        if($("#drpDisIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de la persona.";
        if($("#drpCorIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";
    }
    if($("#drpEstadoCivil").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";
    if($("#drpEscolaridad").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la escolaridad de la persona.";
    if($("#drpGenero").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el genero de la persona.";
    if($("#drpEtnia").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la etnia de la persona.";
    if($("#drpSabeLeer").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la persona sabe leer.";
    
    //Validaciones de Antecedentes    
    if($("#drpAbusoSexual").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si sufrio alg&uacute;n abuso sexual.";
    else if ($("#drpAbusoSexual").val()==1){
        if ($("#drpAbusoSexual12").val()==0) 
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si sufrio abuso sexual en los ultimos 12 meses.";
    }
    if($("#drpTrabajoSexual").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha ejercido como trabajador(a) sexual.";
    if($("#drpTrabajoSexual").val()==1){
        if($("#drpActualTrabajoSexual").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si actualmente es trabajador(a) sexual.";
//        if($("#drpTSTiempo").val()==0)
//            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tiempo que ha ejercido como trabajador(a) sexual.";
        if($("#drpTSOtroPais").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha ejercido como trabajador(a) sexual en otro pa&iacute;s.";
        if($("#drpTipoLugarTS").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de lugar donde ha ejercido como trabajador(a) sexual.";
    }
    if($("#drpRelacionSexual").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha tenido relaciones sexuales con diferentes g&eacute;neros.";
    if($("#drpItsUltimo").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha tenido ITS en el &uacute;ltimo a&ntilde;o.";
    
    if($("#drpVIH").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene diagn&oacute;stico de VIH.";
    if($("#drpVIH").val()==1){
        if($("#drpPreVIH").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si recibio consejer&iacute;a de VIH pre prueba.";
        if($("#drpPosVIH").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si recibio consejer&iacute;a de VIH pos prueba.";
    }
    if($("#drpTARV").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si fue referido a clinica TARV.";
    else if ($("#drpTARV").val()==1){
        if ($("#drpClinicaTarv").val()==0) 
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la clinica TARV a la que fue referido.";
    }
    if($("#drpConsumoAlcohol").val()==0)
        ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha consumido bebidas alcoh&oacute;licas en los &uacute;ltimos 30 d&iacute;as.";
    if($("#drpConsumoAlcohol").val()==1){
        if($("#drpFrecuenciaAlcohol").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la frecuencia con la que consumi&oacute; alcohol en la &uacute;ltima semana.";
    }
    if($("#drpsexo").val()=='F'){
        if($("#drpAnticonceptivo").val()==0)
            ErroresA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si us&oacute; alg&uacute;n m&eacute;todo anticonceptivo.";
    }
    
    //    varSida = $('#check_sida').is(':checked');
    //    varVih = $('#check_vih').is(':checked');
    //    if(!(varSida || varVih) ){
    //        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el caso es de VIH o de SIDA."; 
    //    }
    //    if($("#drpCondicion").val()==0)
    //        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la condicion actual del paciente.";
    //    
    //    
    //    //Notificacion
    //    if(!$('#unidad_disponible').is(':checked')){
    //        if(jQuery.trim($("#notificacion_unidad").val())=="")
    //            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la instalaci&oacute;n de salud.";
    //    }
    //    if(jQuery.trim($("#fechaNotificacion").val())=="")
    //        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de notificacion.";
    drpsexo = $('#drpsexo').val();
    
    if($("#drpMotivoConsulta").val()==0)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el motivo de la consulta.";
    if($("#drpAntibiotico").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si recibi\xf3 un antibi\xf3tico.";
    
    if(drpsexo == 'F'){
        if($("#drpOvulosVaginales").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si us\xf3 \xf3vulos vaginales en los \xfaltimos 30 d\xedas.";
        if($("#drpDuchasVaginales").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si us\xf3 duchas vaginales en los \xfaltimos 30 d\xedas.";
    }
    
    if(drpsexo == 'M'){
        if($("#drpRelacionAnalOtro").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha tenido relaciones sexuales anales con otros hombres diferentes de su pareja en los \xfaltimos 6 meses.";
    }
    if($("#drpRelacionesSexual").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si en los \xfaltimos 30 d\xedas tuvo relaciones.";
    
    if($("#drpRelacionesAnales").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si en los \xfaltimos 30 d\xedas ha tenido relaciones anales.";
    else if($("#drpRelacionesAnales").val()==1){
        if($("#drpTipoRelacionesAnales").val()==-1 && drpsexo == 'M')
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar cual tipo de relaci\xf3n anal.";
    }
    
    if($("#drpSexoOral").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si en los \xfaltimos 30 d\xedas ha tenido practicado sexo oral.";
    
    if($("#drpUltRelCondon").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si en su \xfaltima relaci\xf3n sexual utiliz\xf3 cond\xf3n.";
    
    if(drpsexo == 'M'){
        if($("#drpHombreFija").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja ESTABLE o FIJA Hombre.";
        else if($("#drpHombreFija").val()==1){
            if($("#drpHombreFijaUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Estable Hombre.";
            if($("#drpHombreFijaUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Estable Hombre.";   
        }
    
        if($("#drpHombreCasual").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja Casual Hombre.";
        else if($("#drpHombreCasual").val()==1){
            if($("#drpHombreCasualUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Casual Hombre.";
            if($("#drpHombreCasualUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Casual Hombre.";   
        }
        
        if($("#drpMujerFija").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja ESTABLE o FIJA Mujer.";
        else if($("#drpMujerFija").val()==1){
            if($("#drpMujerFijaUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Estable Mujer.";
            if($("#drpMujerFijaUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Estable Mujer.";   
        }
        
        if($("#drpMujerCasual").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja Casual Mujer.";
        else if($("#drpMujerCasual").val()==1){
            if($("#drpMujerCasualUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Casual Mujer.";
            if($("#drpMujerCasualUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Casual Mujer.";   
        }
    }
    else{
        if($("#drpHombreFija").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja ESTABLE o FIJA.";
        else if($("#drpHombreFija").val()==1){
            if($("#drpHombreFijaUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Estable.";
            if($("#drpHombreFijaUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Estable.";   
        }
    
        if($("#drpHombreCasual").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene pareja Casual.";
        else if($("#drpHombreCasual").val()==1){
            if($("#drpHombreCasualUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si utiliz\xf3 cond\xf3n en los \xfaltimos 30 d\xedas para pareja Casual.";
            if($("#drpHombreCasualUltUsoCondon").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la \xfaltima vez us\xf3 cond\xf3n para pareja Casual.";   
        }
    }
    
    if($("#drpExaGeneral").val()==-1)
        ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si realizo examen general.";
    else if($("#drpExaGeneral").val()==1){
        if($("#exaTemperatura").val()== "")
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe llenar el campo de temperatura oral.";
        if($("#exaLibras").val()== "")
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe llenar el campo de peso.";
        if($("#exaPA").val()== "")
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe llenar el campo P/A.";
        
        if($("#drpExaGanglio").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de ganglios.";
        else if($("#drpExaGanglio").val()==2){
            if(!(
                $('#exaGanglioCuello').is(':checked')
                || $('#exaGanglioAxilar').is(':checked')
                || $('#exaGanglioInguinal').is(':checked')
                ))
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de ganglios.";
        }
        
        if($("#drpExaRash").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de rash.";
        else if($("#drpExaRash").val()==2){
            if($("#drpExaRashOpcion").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de rash.";
        }
        
        if($("#drpExaBoca").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de boca.";
        else if($("#drpExaBoca").val()==2){
            if(!(
                $('#exaBocaMonilia').is(':checked')
                || $('#exaBocaUlcera').is(':checked')
                || $('#exaBocaAmigdalas').is(':checked')
                || $('#exaBocaIrritacionFaringea').is(':checked')
                || $('#exaBocaOtro').is(':checked')
                ))
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de boca.";
        }
        if(drpsexo == 'M'){
            if($("#drpExaPene").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de pene.";
            else if($("#drpExaPene").val()==2){
                if(!(
                    $('#exaPeneUlcera').is(':checked')
                    || $('#exaPeneVerruga').is(':checked')
                    || $('#exaPeneAmpolla').is(':checked')
                    || $('#exaPeneOtro').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de pene.";
            }
        
            if($("#drpExaTesticulo").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de test\xedculo.";
            else if($("#drpExaTesticulo").val()==2){
                if(!(
                    $('#exaTesticuloUlcera').is(':checked')
                    || $('#exaTesticuloVerruga').is(':checked')
                    || $('#exaTesticuloAmpolla').is(':checked')
                    || $('#exaTesticuloOtro').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de test\xedculo.";
            }
            
        }else{
            if($("#drpExaAbdomen").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de abdomen.";
            else if($("#drpExaAbdomen").val()==2){
                if(!(
                    $('#exaAbdomenFosaIzq').is(':checked')
                    || $('#exaAbdomenHipogastrico').is(':checked')
                    || $('#exaAbdomenFosaDer').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de abdomen.";
            }
            
            if($("#drpExaVulva").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de vulva.";
            else if($("#drpExaVulva").val()==2){
                if(!(
                    $('#exaVulvaUlcera').is(':checked')
                    || $('#exaVulvaVerruga').is(':checked')
                    || $('#exaVulvaVesicula').is(':checked')
                    || $('#exaVulvaOtro').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de vulva.";
            }
        }
        
        if($("#drpExaMeato").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de meato uretral.";
        else if($("#drpExaMeato").val()==2){
            if(!(
                $('#exaMeatoHiperemia').is(':checked')
                || $('#exaMeatoSecrecion').is(':checked')
                || $('#exaMeatoVerruga').is(':checked')
                ))
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de meato uretral.";
        }
        
        if($("#drpExaAno").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de ano.";
        else if($("#drpExaAno").val()==2){
            if(!(
                $('#exaAnoUlcera').is(':checked')
                || $('#exaAnoVerruga').is(':checked')
                || $('#exaAnoSecrecion').is(':checked')
                || $('#exaAnoVesicula').is(':checked')
                || $('#exaAnoOtro').is(':checked')
                ))
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de ano.";
        }
    }
    
    if(drpsexo == 'F'){
        if($("#drpExaEspeculo").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si realizo examen con especulo.";
        else if($("#drpExaEspeculo").val()==1){
            if($("#drpExaVagina").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de vagina.";
            else if($("#drpExaVagina").val()==2){
                if(!(
                    $('#exaVaginaUlcera').is(':checked')
                    || $('#exaVaginaHiperemia').is(':checked')
                    || $('#exaVaginaMenstruacion').is(':checked')
                    || $('#exaVaginaAtrofia').is(':checked')
                    || $('#exaVaginaOtro').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de vagina.";
            }
            
            if($("#drpExaFlujo").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de flujo.";
            else if($("#drpExaFlujo").val()==1){
                if($("#drpExaFlujoCantidad").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para la cantidad en el examen de flujo.";
                if($("#drpExaFlujoColor").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el color en el examen de flujo.";
                if(!(
                    $('#exaFlujoAspectoSanguinolento').is(':checked')
                    || $('#exaFlujoAspectoGrumoso').is(':checked')
                    || $('#exaFlujoAspectoEspumoso').is(':checked')
                    || $('#exaFlujoAspectoMucoso').is(':checked')
                    || $('#exaFlujoAspectoOlor').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el aspecto en el examen de flujo.";
            }
            
            if($("#drpExaCervix").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de cervix.";
            else if($("#drpExaCervix").val()==2){
                if(!(
                    $('#exaCervixUlcera').is(':checked')
                    || $('#exaCervixHiperemia').is(':checked')
                    || $('#exaCervixFriable').is(':checked')
                    || $('#exaCervixTumor').is(':checked')
                    || $('#exaCervixPus').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de cervix.";
            }
        } 
        if($("#drpExaBimanual").val()==-1)
            ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si realizo examen bimanual.";
        else if($("#drpExaBimanual").val()==1){
            if($("#drpExaBiAnexo").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de anexos para bimanual.";
            else if($("#drpExaBiAnexo").val()==2){
                if($("#drpExaBiAnexoSangrado").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para sangrado anormal del examen de anexos para bimanual.";
                if($("#drpExaBiAnexoDolor").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para dolor del examen de anexos para bimanual.";
                if($("#drpExaBiAnexoTumor").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para tumor del examen de anexos para bimanual.";
            }
            
            if($("#drpExaBiHipogastrico").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de hipogastrio para bimanual.";
            
            if($("#drpExaBiCervix").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de cervix para bimanual.";
            else if($("#drpExaBiCervix").val()==2){
                if(!(
                    $('#exaBiCervixAusente').is(':checked')
                    || $('#exaBiCervixDolor').is(':checked')
                    ))
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de cervix para bimanual.";
            }
            
            if($("#drpExaBiUtero").val()==-1)
                ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el resultado de examen de utero para bimanual.";
            else if($("#drpExaBiUtero").val()==2){
                if($("#drpExaBiUteroAnormal").val()==-1)
                    ErroresPA+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para el examen de utero para bimanual.";
            }
        }
    }
    
    if($("#drpUsuarioSano").val()==-1)
        ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el usuario esta sano.";
    
    if($("#drpMuestrasLabTomo").val()==-1)
        ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tomo muestra.";
    else if($("#drpMuestrasLabTomo").val()==1){
        if(drpsexo == 'M'){
            if(!(
                $('#muestraSangreHSH').is(':checked')
                || $('#muestraUlcera').is(':checked')
                || $('#muestraSecrecionUretral').is(':checked')
                || $('#muestraSecrecionAnal').is(':checked')
                ))
                ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para la muestra.";
        }else{
            if(!(
                $('#muestraSangreTS').is(':checked')
                || $('#muestraFlujoVaginal').is(':checked')
                || $('#muestraEndocervix').is(':checked')
                || $('#muestraCitologia').is(':checked')
                || $('#muestraUlceraTS').is(':checked')
                ))
                ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para la muestra.";
        }
    }
    if(drpsexo == 'F'){
        if($("#drpEmbarazo").val()==-1)
            ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si esta embarazada.";
        else if($("#drpEmbarazo").val()==1){
            if($("#embarazo_semanas").val()=="")
                ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe llenar el n\xfamero de semanas de embarazo.";
        }
        
        if($("#drpOtroTratamiento").val()==-1)
            ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si tiene otro tratamiento.";
        else if($("#drpOtroTratamiento").val()==1){
            if(!(
                $('#txSulfato').is(':checked')
                || $('#txAcidoFolico').is(':checked')
                || $('#txPrenatales').is(':checked')
                || $('#txToxoide').is(':checked')
                ))
                ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una opci\xf3n para otro tratamiento.";
        }
    }
    
    if($("#drpDiagIntervencion").val()==-1)
        ErroresPB+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si ha recibido una intervenci\xf3n de cambio de comportamiento en los \xfaltimos 12 meses.";
    
    if($("#noti_id_un").val()=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una instalaci\xf3n de salud.";
    
    if(jQuery.trim($("#noti_fecha_consulta").val())=="")
    {
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de consulta.";
    }
    else
    {
        if(!isDate($("#noti_fecha_consulta").val().toString()))
            ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de consulta no tiene el formato adecuado.";
        else{
            if(comparacionFechas($("#noti_fecha_consulta").val().toString(),fechaActualString()))
                ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La ffecha de consulta no puede ser una fecha futura.";
        }
    }

    
    ErrorIndividuo = (ErroresI=="")? "": ErrorIndividuo+ErroresI + "<br/>";
    ErrorAntecedentes = (ErroresA=="")? "": ErrorAntecedentes+ErroresA + "<br/>";
    ErrorParteA = (ErroresPA=="")? "": ErrorParteA+ErroresPA + "<br/>";
    ErrorParteB = (ErroresPB=="")? "": ErrorParteB+ErroresPB + "<br/>";
    ErrorNotificacion = (ErroresN=="")? "": ErrorNotificacion+ErroresN + "<br/>";
    Message = ErrorIndividuo + ErrorAntecedentes + ErrorParteA + ErrorParteB + ErrorNotificacion;
    
    //test
//        Message = "";
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
            //            $('#nombreRegistra').attr('readonly', false);
            //            $('#nombreRegistra').attr('disabled', '');
            $('#drpTipoId').attr('readonly', false);
            $('#drpTipoId').attr('disabled', '');
            $('#no_identificador').attr('readonly', false);
            $('#no_identificador').attr('disabled', '');
            $('#ginecoEmbarazos').attr('readonly', false);
            $('#ginecoEmbarazos').attr('disabled', '');
           
            var param = '';
            var i=0;
            for(i=0; i<globalDrogasRelacionadas.length;i++){
                if(__isset(globalDrogasRelacionadas[i])){
                    param+=globalDrogasRelacionadas[i][0]+"#-#"+globalDrogasRelacionadas[i][2];//Factor de Riesgo
                }
            }
            $('#globalDrogasRelacionadas').val(param);

            var param2 = '';

            for(i=0; i<globalITSRelacionados.length;i++){
                if(__isset(globalITSRelacionados[i])){
                    param2+=globalITSRelacionados[i][0];//enfermedad Oportunista
                }
            }
            $('#globalITSRelacionados').val(param2);
            
            var param3 = '';
        
            for(i=0; i<globalSintomasSignosRelacionados.length;i++){
                if(__isset(globalSintomasSignosRelacionados[i])){
                    param3+=globalSintomasSignosRelacionados[i][0]+"-"//idSintoma
                    +globalSintomasSignosRelacionados[i][2]; //dias
                }
            }
            $('#globalSintomasSignosRelacionados').val(param3);
            
            var param4 = '';
        
            for(i=0; i<globalAntibioticosRelacionados.length;i++){
                if(__isset(globalAntibioticosRelacionados[i])){
                    param4+=globalAntibioticosRelacionados[i][0]+"-"//idAntibiotico
                    +globalAntibioticosRelacionados[i][2]+"-" //Motivo
                    +globalAntibioticosRelacionados[i][3]; //fecha
                }
            }
            $('#globalAntibioticosRelacionados').val(param4);
            
            var param5 = '';
        
            for(i=0; i<globalDiagnosticosTratamientoRelacionados.length;i++){
                if(__isset(globalDiagnosticosTratamientoRelacionados[i])){
                    param5+=globalDiagnosticosTratamientoRelacionados[i][0]+"-"//idDxSindromico
                    +globalDiagnosticosTratamientoRelacionados[i][1]+"-" //idDxEtiologico
                    +globalDiagnosticosTratamientoRelacionados[i][2]; //idTxSupervisado
                }
            }
            $('#globalDiagnosticosTratamientoRelacionados').val(param5);

            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de VICITS, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de VICITS, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#drpsexo").attr('disabled', false);
                $("#dSummaryErrors").css('display','none');
                $('#frmContenido').submit();
            }
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

//Para solo pasarle el nombre del id y lo hace auto
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

function calendarios(){
    colocarCalendario("#fecha_ult_dosis");
    colocarCalendario("#otro_fecha_citologia");
    colocarCalendario("#fecha_menstruacion");
}

function mostrarAntibiotico(){
    antibiotico = $('#drpAntibiotico').val();
    if(antibiotico != 1)
        $( "#mostrarAntibiotico" ).css( "display", "none" );
    else
        $( "#mostrarAntibiotico" ).css( "display", "" );
}

function sexoCambio(){
    sexo = $('#drpsexo').val();
    setPestana(sexo);
    setSintomasSignos(sexo);
    setDxSindromico();
    setDxTotal();
    setOtrosDatos(sexo);
    setDatosUsoCondon(sexo);
    setExamenGeneral();
    setExamenEspeculo();
    setExamenBimanual();
    setMuestrasLaboratorio();
    setDiagnosticoTratamiento();
}

function setPestana(sex){
    if(sex == 'F'){
        $( "#formLabel" ).html("<h2>Formulario de la Vigilancia Centinela de las ITS - VICITS para mujer TS</h2>"); 
    }else if(sex == 'M'){
        $( "#formLabel" ).html("<h2>Formulario de la Vigilancia Centinela de las ITS - VICITS para hombre HSH y TRANS</h2>"); 
    }else{
        $( "#formLabel" ).html("<h2>Formulario de la Vigilancia Centinela de las ITS - VICITS</h2>"); 
    }
}

function setSintomasSignos(sex){
    destruirTabla(tablaSintomasSignos);
    if(sex != 0){
        $.getJSON(urlprefix + 'js/dynamic/sintomasITS.php',{
            sexo: sex,
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpSintomas").html(options);
        })
    }else{
        var options = '';
        options += '<option value="0">Debe escoger el sexo del individuo...</option>';
        $("#drpSintomas").html(options);
    }
}

function setDxSindromico(){
    destruirTabla(tablaDiagnosticoTratamiento);
    drpsexo = $('#drpsexo').val();
    if(drpsexo != 0){
        $.getJSON(urlprefix + 'js/dynamic/dxSindromicoITS.php',{
            sexo: drpsexo,
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpDxSindromico").html(options);
        })
    }else{
        var options = '';
        options += '<option value="0">Debe escoger el sexo del individuo...</option>';
        $("#drpDxSindromico").html(options);
    }
    setDxEtiologico();
    setTxSupervisado();
}

function setDxTotal(){
//    setDxEtiologico();
//    setTxSupervisado();
}

function setDxEtiologico(){
    drpsexo = $('#drpsexo').val();
    if(drpsexo != 0){
        $.getJSON(urlprefix + 'js/dynamic/dxEtiologicoITS.php',{
            sexo: drpsexo,
            sindromico:  $("#drpDxSindromico").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpDxEtiologico").html(options);
        })
    }else{
        var options = '';
        options += '<option value="0">Debe escoger el sexo del individuo...</option>';
        $("#drpDxEtiologico").html(options);
    }
}

function setTxSupervisado(){
    drpsexo = $('#drpsexo').val();
    if(drpsexo != 0){
        $.getJSON(urlprefix + 'js/dynamic/dxTratamientoITS.php',{
            sexo: drpsexo,
            sindromico:  $("#drpDxSindromico").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpTxSupervisado").html(options);
        })
    }else{
        var options = '';
        options += '<option value="0">Debe escoger el sexo del individuo...</option>';
        $("#drpTxSupervisado").html(options);
    }
}

function setOtrosDatos(sex){
    if(sex == 'F'){
        $( "#mostrarOtrosDatosTS" ).css( "display", "" ); 
    }else{
        $( "#mostrarOtrosDatosTS" ).css( "display", "none" );
    }
}

function setDatosUsoCondon(sex){
    if(sex == 'F'){
        $( "#relacionesSexualesOtroHombre" ).css( "display", "none" );
        $( "#labelRelacionSexual" ).html("vaginales"); 
        $( "#labelTipoRelacionesAnales" ).css( "display", "none" );
        $( "#drpTipoRelacionesAnales" ).css( "display", "none" );
        $( "#labelEstableHombre" ).html("");
        $( "#labelCasualHombre" ).html("");
        $( "#mujerFija" ).css( "display", "none" );
        $( "#mujerCasual" ).css( "display", "none" );
        $( "#mujerFijaOpciones" ).css( "display", "none" );
        $( "#mujerCasualOpciones" ).css( "display", "none" );
        setHombreFija();
        setHombreCasual();
    }else{
        $( "#relacionesSexualesOtroHombre" ).css( "display", "" );
        $( "#labelRelacionSexual" ).html("sexuales"); 
        setRelacionesAnales();
        $( "#labelEstableHombre" ).html(" <b>Hombre</b>");
        $( "#labelCasualHombre" ).html(" <b>Hombre</b>");
        $( "#mujerFija" ).css( "display", "" );
        $( "#mujerCasual" ).css( "display", "" );
        setMujerFija();
        setMujerCasual();
        setHombreFija();
        setHombreCasual();
    }
}

function setHombreFija(){
    if( $('#drpHombreFija').val() == 1)
        $( "#hombreFijaOpciones" ).css( "display", "" );
    else
        $( "#hombreFijaOpciones" ).css( "display", "none" );
}

function setHombreCasual(){
    if( $('#drpHombreCasual').val() == 1)
        $( "#hombreCasualOpciones" ).css( "display", "" );
    else
        $( "#hombreCasualOpciones" ).css( "display", "none" );
}

function setMujerFija(){
    sexo = $('#drpsexo').val();
    if(sexo == 'M'){
        if( $('#drpMujerFija').val() == 1)
            $( "#mujerFijaOpciones" ).css( "display", "" );
        else
            $( "#mujerFijaOpciones" ).css( "display", "none" );
    }
}

function setMujerCasual(){
    sexo = $('#drpsexo').val();
    if(sexo == 'M'){
        if( $('#drpMujerCasual').val() == 1)
            $( "#mujerCasualOpciones" ).css( "display", "" );
        else
            $( "#mujerCasualOpciones" ).css( "display", "none" );
    }
}

function setRelacionesAnales(){
    sexo = $('#drpsexo').val();
    if(sexo == 'M'){
        if($( "#drpRelacionesAnales" ).val() == 1){
            $( "#labelTipoRelacionesAnales" ).css( "display", "" ); 
            $( "#drpTipoRelacionesAnales" ).css( "display", "" );
        }
        else{
            $( "#labelTipoRelacionesAnales" ).css( "display", "none" );
            $( "#drpTipoRelacionesAnales" ).css( "display", "none" );
        }
    }
}

function setExamenGeneral(){
    drpExaGeneral = $('#drpExaGeneral').val();
    if( drpExaGeneral == '1')
        $( "#examenGeneralRealizado" ).css( "display", "" );
    else{
        $( "#examenGeneralRealizado" ).css( "display", "none" );
        return;
    }
    drpExaGanglio = $('#drpExaGanglio').val();   
    drpExaBoca = $('#drpExaBoca').val(); 
    drpExaRash = $('#drpExaRash').val(); 
    drpExaMeato = $('#drpExaMeato').val(); 
    drpExaAno = $('#drpExaAno').val(); 
    
    if(drpExaGanglio == '2')  $( "#opcGanglio" ).css( "display", "" );
    else $( "#opcGanglio" ).css( "display", "none" );
    
    if(drpExaBoca == '2') $( "#opcBoca" ).css( "display", "" );
    else $( "#opcBoca" ).css( "display", "none" );
    
    if(drpExaRash == '2') $( "#opcRash" ).css( "display", "" );
    else $( "#opcRash" ).css( "display", "none" );
    
    if(drpExaMeato == '2') $( "#opcMeato" ).css( "display", "" );
    else $( "#opcMeato" ).css( "display", "none" );
    
    if(drpExaAno == '2') $( "#opcAno" ).css( "display", "" );
    else $( "#opcAno" ).css( "display", "none" );
    
    drpsexo = $('#drpsexo').val();
    if(drpsexo == 'F'){
        $( "#mostrarExaAbdomen" ).css( "display", "" );
        $( "#mostrarExaVulva" ).css( "display", "" );
        
        $( "#mostrarExaPene" ).css( "display", "none" );
        $( "#mostrarExaTesticulo" ).css( "display", "none" );
        
        drpExaAbdomen = $('#drpExaAbdomen').val();
        drpExaVulva = $('#drpExaVulva').val();
        
        if(drpExaAbdomen == '2') $( "#opcAbdomen" ).css( "display", "" );
        else $( "#opcAbdomen" ).css( "display", "none" );
        
        if(drpExaVulva == '2') $( "#opcVulva" ).css( "display", "" );
        else $( "#opcVulva" ).css( "display", "none" ); 
    }else{
        $( "#mostrarExaAbdomen" ).css( "display", "none" );
        $( "#mostrarExaVulva" ).css( "display", "none" );
        
        $( "#mostrarExaPene" ).css( "display", "" );
        $( "#mostrarExaTesticulo" ).css( "display", "" );
        
        drpExaPene = $('#drpExaPene').val();
        drpExaTesticulo = $('#drpExaTesticulo').val();
        
        if(drpExaPene == '2') $( "#opcPene" ).css( "display", "" );
        else $( "#opcPene" ).css( "display", "none" );
        
        if(drpExaTesticulo == '2') $( "#opcTesticulo" ).css( "display", "" );
        else $( "#opcTesticulo" ).css( "display", "none" );
        
    }
}
//Solo TS
function setExamenEspeculo(){
    drpsexo = $('#drpsexo').val();
    if(drpsexo == 'F'){
        $( "#mostrarExamenEspeculoTS" ).css( "display", "" ); 
        drpExaEspeculo = $('#drpExaEspeculo').val();
        if(drpExaEspeculo == '1'){
            $( "#examenEspeculoRealizado" ).css( "display", "" );
            
            drpExaVagina = $('#drpExaVagina').val();
            if(drpExaVagina == '2'){
                $( "#opcVagina" ).css( "display", "" );
            }else{
                $( "#opcVagina" ).css( "display", "none" );
            }
            
            drpExaFlujo = $('#drpExaFlujo').val();
            if(drpExaFlujo == '1'){
                $( "#mostrarExamenFlujo" ).css( "display", "" );
            }else{
                $( "#mostrarExamenFlujo" ).css( "display", "none" );
            }
            
            drpExaCervix = $('#drpExaCervix').val();
            if(drpExaCervix == '2'){
                $( "#opcCervix" ).css( "display", "" );
            }else{
                $( "#opcCervix" ).css( "display", "none" );
            }
        }else{
            $( "#examenEspeculoRealizado" ).css( "display", "none" );
            $( "#opcVagina" ).css( "display", "none" );
            $( "#mostrarExamenFlujo" ).css( "display", "none" );
            $( "#opcCervix" ).css( "display", "none" );
        }
        
    }else{
        $( "#mostrarExamenEspeculoTS" ).css( "display", "none" );
        $( "#examenEspeculoRealizado" ).css( "display", "none" );
        $( "#opcVagina" ).css( "display", "none" );
        $( "#mostrarExamenFlujo" ).css( "display", "none" );
        $( "#opcCervix" ).css( "display", "none" );
    }
}
//Solo TS
function setExamenBimanual(){
    drpsexo = $('#drpsexo').val();
    if(drpsexo == 'F'){
        $( "#mostrarExamenBimanualTS" ).css( "display", "" ); 
        
        drpExaBimanual = $('#drpExaBimanual').val();
        if(drpExaBimanual == '1'){
            $( "#examenBimanualRealizado" ).css( "display", "" );
            
            drpExaBiAnexo = $('#drpExaBiAnexo').val();
            if(drpExaBiAnexo == '2'){
                $( "#opcBiAnexo" ).css( "display", "" );
            }else{
                $( "#opcBiAnexo" ).css( "display", "none" );
            }
             
            drpExaBiCervix = $('#drpExaBiCervix').val();
            if(drpExaBiCervix == '2'){
                $( "#opcBiCervix" ).css( "display", "" );
            }else{
                $( "#opcBiCervix" ).css( "display", "none" );
            }
            
            drpExaBiUtero = $('#drpExaBiUtero').val();
            if(drpExaBiUtero == '2'){
                $( "#opcBiUtero" ).css( "display", "" );
            }else{
                $( "#opcBiUtero" ).css( "display", "none" );
            }
        }else{
            $( "#examenBimanualRealizado" ).css( "display", "none" );
            $( "#opcBiAnexo" ).css( "display", "none" );
            $( "#opcBiCervix" ).css( "display", "none" );
            $( "#opcBiUtero" ).css( "display", "none" );
        }
    }else{
        $( "#mostrarExamenBimanualTS" ).css( "display", "none" );
        $( "#examenBimanualRealizado" ).css( "display", "none" );
        $( "#opcBiAnexo" ).css( "display", "none" );
        $( "#opcBiCervix" ).css( "display", "none" );
        $( "#opcBiUtero" ).css( "display", "none" );
    }
}

function setMuestrasLaboratorio(){
    drpsexo = $('#drpsexo').val();
    drpMuestrasLabTomo = $('#drpMuestrasLabTomo').val();
    if( drpMuestrasLabTomo == '1'){
        $( "#tipoMuestraLabel" ).css( "display", "" );
        if(drpsexo == 'M'){
            $( "#opcTipoMuestrasTS" ).css( "display", "none" );
            $( "#opcTipoMuestrasHSH" ).css( "display", "" );
        }else{
            $( "#opcTipoMuestrasTS" ).css( "display", "" );
            $( "#opcTipoMuestrasHSH" ).css( "display", "none" );
        }
    }else{
        $( "#tipoMuestraLabel" ).css( "display", "none" );
        $( "#opcTipoMuestrasTS" ).css( "display", "none" );
        $( "#opcTipoMuestrasHSH" ).css( "display", "none" );
    }
    
    if(drpsexo == 'F'){
        $( "#fechaMenstruacionLabel" ).css( "display", "" );
        $( "#fechaMenstruacionInput" ).css( "display", "" );
        $( "#embarazadaLabel" ).css( "display", "" );
        $( "#drpEmbarazo" ).css( "display", "" );
        
        drpEmbarazo = $('#drpEmbarazo').val();
        if(drpEmbarazo == '1'){
            $( "#semanasLabel" ).css( "display", "" );
            $( "#embarazo_semanas" ).css( "display", "" );
            embarazo_semanas = $( "#embarazo_semanas" ).val();
            if(embarazo_semanas > 43){
                $( "#embarazo_semanas" ).val('43');
            }
        }
        else{
            $( "#semanasLabel" ).css( "display", "none" );
            $( "#embarazo_semanas" ).css( "display", "none" );
        }
        
    }else{
        $( "#fechaMenstruacionLabel" ).css( "display", "none" );
        $( "#fechaMenstruacionInput" ).css( "display", "none" );
        $( "#embarazadaLabel" ).css( "display", "none" );
        $( "#drpEmbarazo" ).css( "display", "none" );
        $( "#semanasLabel" ).css( "display", "none" );
        $( "#embarazo_semanas" ).css( "display", "none" );
    }
}

function setDiagnosticoTratamiento(){
    drpsexo = $('#drpsexo').val();
    if(drpsexo == 'F'){
        $( "#otroTratamientoTS" ).css( "display", "" );
        drpOtroTratamiento = $('#drpOtroTratamiento').val();
        if(drpOtroTratamiento == '1')
            $( "#opcOtroTratamientoTS" ).css( "display", "" );
        else
            $( "#opcOtroTratamientoTS" ).css( "display", "none" );
    }else{
        $( "#otroTratamientoTS" ).css( "display", "none" );
    }
}

function relacionarTabla(tabla){
    var tmpReg = 0;
    var flag = true;
    var i=0;
    if(tabla == tablaSintomasSignos){
        var idSintoma = $("#drpSintomas").val();
        var nombreSintoma = $("#drpSintomas").find(":selected").text();
        var dias = $("#diasSintoma").val();
    
        if (idSintoma !="" && idSintoma != 0 && dias !=0){
            $("#drpSintomas").val(0);
            $("#diasSintoma").val("");
            tmpReg = globalSintomasSignosRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idSintoma == globalSintomasSignosRelacionados[i][0] || "###"+idSintoma == globalSintomasSignosRelacionados[i][0]))
                    flag = false;
            }
            if (flag){
                idSintoma = (tmpReg==0) ? idSintoma : "###"+idSintoma;
                globalSintomasSignosRelacionados[tmpReg] = new Array(idSintoma,nombreSintoma,dias);
                crearTabla(tablaSintomasSignos);
            }
            else
                alert ("Ya existe un registro para esta relacion");
        }
        else
            alert("Debe seleccionar un sintoma y los dias");
    }
    else if(tabla == tablaAntibioticos){
        var idAntibiotico = $("#antibiotico").val();
        var nombreAntibiotico = $("#antibiotico").val();
        var motivoAntibiotico = $("#motivo_antibiotico").val();
        var fechaUltDosis = $("#fecha_ult_dosis").val();
    
        if (idAntibiotico !="" ){
        
            tmpReg = globalAntibioticosRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idAntibiotico == globalAntibioticosRelacionados[i][0] || "###"+idAntibiotico == globalAntibioticosRelacionados[i][0])
                    && (motivoAntibiotico == globalAntibioticosRelacionados[i][2] || "###"+motivoAntibiotico == globalAntibioticosRelacionados[i][2])
                    && (fechaUltDosis == globalAntibioticosRelacionados[i][3] || "###"+fechaUltDosis == globalAntibioticosRelacionados[i][3]))
                    flag = false;
            }
            if (flag){
                idAntibiotico = (tmpReg==0) ? idAntibiotico : "###"+idAntibiotico;
                globalAntibioticosRelacionados[tmpReg] = new Array(idAntibiotico,nombreAntibiotico,motivoAntibiotico,fechaUltDosis);
                crearTabla(tablaAntibioticos);
            }
            else
                alert ("Ya existe un registro para esta relacion");
        }
        else
            alert("Debe llenar un antibiotico, motivo y fecha");
    }
    else if(tabla == tablaDiagnosticoTratamiento){
        var idDxSindromico = $("#drpDxSindromico").val();
        var idDxEtiologico = $("#drpDxEtiologico").val();
        var idTxSupervisado = $("#drpTxSupervisado").val();
        
        var nombreDxSindromico = $("#drpDxSindromico").find(":selected").text();
        var nombreDxEtiologico = $("#drpDxEtiologico").find(":selected").text();
        var nombreTxSupervisado = $("#drpTxSupervisado").find(":selected").text();
            
        if (idDxSindromico !=0 && idDxEtiologico !=0 && idTxSupervisado !=0){
            $("#drpDxSindromico").val(0);
            $("#drpDxEtiologico").val(0);
            $("#drpTxSupervisado").val(0);
            tmpReg = globalDiagnosticosTratamientoRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idDxSindromico == globalDiagnosticosTratamientoRelacionados[i][0] 
                    || "###"+idDxSindromico == globalDiagnosticosTratamientoRelacionados[i][0])
                && (idDxEtiologico == globalDiagnosticosTratamientoRelacionados[i][1] 
                    || "###"+idDxEtiologico == globalDiagnosticosTratamientoRelacionados[i][1])
                && (idTxSupervisado == globalDiagnosticosTratamientoRelacionados[i][2] 
                    || "###"+idTxSupervisado == globalDiagnosticosTratamientoRelacionados[i][2])
                    )
                flag = false;
            }
            if (flag){
                idDxSindromico = (tmpReg==0) ? idDxSindromico : "###"+idDxSindromico;
                globalDiagnosticosTratamientoRelacionados[tmpReg] = new Array(
                    idDxSindromico,idDxEtiologico,idTxSupervisado,
                    nombreDxSindromico,nombreDxEtiologico,nombreTxSupervisado
                    );
                crearTabla(tablaDiagnosticoTratamiento);
            }
            else
                alert ("Ya existe un registro para esta relacion");
        }
        else
            alert("Debe llenar un registro con los diagn\xf3sticos y el tratamiento");
    }
}

function crearTabla(tabla){
    var i=0;
    var html = '';
    if(tabla==tablaSintomasSignos){
        if(globalSintomasSignosRelacionados.length == 0)
            $("#"+tablaSintomasSignos).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Sintomas</th>'+
            '<th class="dxgvHeader_PlasticBlue">Dias</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalSintomasSignosRelacionados.length;i++){
                if(__isset(globalSintomasSignosRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="180px">'+globalSintomasSignosRelacionados[i][1]+'</th>'+
                    '<td class="fila" width="60px">'+globalSintomasSignosRelacionados[i][2]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaSintomasSignos,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaSintomasSignos).html(html);
        }
    }
    else if(tabla==tablaAntibioticos){
        if(globalAntibioticosRelacionados.length == 0)
            $("#"+tablaAntibioticos).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Antibi&oacute;tico</th>'+
            '<th class="dxgvHeader_PlasticBlue">Motivo:</th>'+
            '<th class="dxgvHeader_PlasticBlue">Fecha</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalAntibioticosRelacionados.length;i++){
                if(__isset(globalAntibioticosRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="180px">'+globalAntibioticosRelacionados[i][1]+'</th>'+
                    '<td class="fila" width="60px">'+globalAntibioticosRelacionados[i][2]+'</th>'+
                    '<td class="fila" width="60px">'+globalAntibioticosRelacionados[i][3]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaAntibioticos,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaAntibioticos).html(html);
        }
    }
    else if(tabla==tablaDiagnosticoTratamiento){
        if(globalDiagnosticosTratamientoRelacionados.length == 0)
            $("#"+tablaDiagnosticoTratamiento).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Diagn&oacute;stico Sindr&oacute;mico</th>'+
            '<th class="dxgvHeader_PlasticBlue">Diagn&oacute;stico Etiol&oacute;gico</th>'+
            '<th class="dxgvHeader_PlasticBlue">Tratamiento supervisado</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalDiagnosticosTratamientoRelacionados.length;i++){
                if(__isset(globalDiagnosticosTratamientoRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="100px">'+globalDiagnosticosTratamientoRelacionados[i][3]+'</th>'+
                    '<td class="fila" width="35px">'+globalDiagnosticosTratamientoRelacionados[i][4]+'</th>'+
                    '<td class="fila" width="180px">'+globalDiagnosticosTratamientoRelacionados[i][5]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaDiagnosticoTratamiento,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaDiagnosticoTratamiento).html(html);
        }
    }
}

function borrarRelacionTabla(tabla, pos){
    if(tabla == tablaSintomasSignos){
        if (confirm("Esta seguro de eliminar la relacion\n con el sintoma "+globalSintomasSignosRelacionados[pos][1])){
            globalSintomasSignosRelacionados.splice(pos, 1);
            crearTabla(tablaSintomasSignos);
        }
    }
    else if(tabla == tablaAntibioticos){
        if (confirm("Esta seguro de eliminar la relacion\n con el antibiotico "+globalAntibioticosRelacionados[pos][1])){
            globalAntibioticosRelacionados.splice(pos, 1);
            crearTabla(tablaAntibioticos);
        }
    }
    else if(tabla == tablaDiagnosticoTratamiento){
        if (confirm("Esta seguro de eliminar la relacion\n con el tratamiento "+globalDiagnosticosTratamientoRelacionados[pos][5])){
            globalDiagnosticosTratamientoRelacionados.splice(pos, 1);
            crearTabla(tablaDiagnosticoTratamiento);
        }
    }
}

function inicializarTabla(tabla){
    var i=0;
    if(tabla == tablaSintomasSignos){
        var sintomas = $( "#globalSintomasSignosRelacionados" ).val().split("###");
//        alert($( "#globalSintomasSignosRelacionados" ).val());
        for(i=0; i<sintomas.length;i++){
            var sintoma = sintomas[i].split("#-#");
            agregarRegistroTabla(tablaSintomasSignos, sintoma);
        }
        crearTabla(tablaSintomasSignos);
    }
    else if(tabla == tablaAntibioticos){
        var antibioticos = $( "#globalAntibioticosRelacionados" ).val().split("###");
        for(i=0; i<antibioticos.length;i++){
            var antibiotico = antibioticos[i].split("#-#");
            agregarRegistroTabla(tablaAntibioticos, antibiotico);
        }
        crearTabla(tablaAntibioticos);
    }
    else if(tabla == tablaDiagnosticoTratamiento){
        var diagnosticosTratamientos = $( "#globalDiagnosticosTratamientoRelacionados" ).val().split("###");
        for(i=0; i<diagnosticosTratamientos.length;i++){
            var dxtx = diagnosticosTratamientos[i].split("#-#");
            agregarRegistroTabla(tablaDiagnosticoTratamiento, dxtx);
        }
        crearTabla(tablaDiagnosticoTratamiento);
    }
}

function agregarRegistroTabla(tabla, registro){
    var tmpReg = 0;
    var flag = true;
    var i=0;
    if(tabla == tablaSintomasSignos){
        var idSintoma = registro[0];
        var nombreSintoma = registro[1];
        var dias = registro[2];
        if (idSintoma !="" && idSintoma != 0 && dias !=0){
        
            tmpReg = globalSintomasSignosRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if (idSintoma == globalSintomasSignosRelacionados[i][0] || "###"+idSintoma == globalSintomasSignosRelacionados[i][0])
                    flag = false;
            }
            if (flag){
                idSintoma = (tmpReg==0) ? idSintoma : "###"+idSintoma;
                globalSintomasSignosRelacionados[tmpReg] = new Array(idSintoma,nombreSintoma,dias);
            }
        }       
    }
    else if(tabla == tablaAntibioticos){
        var idAntibiotico = registro[0];
        var nombreAntibiotico = registro[1];
        var motivoAntibiotico = registro[2];
        var fechaUltDosis = registro[3];
        if (idAntibiotico !="" && motivoAntibiotico !="" && fechaUltDosis !=""){
            tmpReg = globalAntibioticosRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if (idAntibiotico == globalAntibioticosRelacionados[i][0] || "###"+idAntibiotico == globalAntibioticosRelacionados[i][0])
                    flag = false;
            }
            if (flag){
                idAntibiotico = (tmpReg==0) ? idAntibiotico : "###"+idAntibiotico;
                globalAntibioticosRelacionados[tmpReg] = new Array(idAntibiotico,nombreAntibiotico,motivoAntibiotico,fechaUltDosis);
            }
        }       
    }
    else if(tabla == tablaDiagnosticoTratamiento){
        var idDxSindromico = registro[0];
        var idDxEtiologico = registro[1];
        var idTxSupervisado = registro[2];
        
        var nombreDxSindromico = registro[3];
        var nombreDxEtiologico = registro[4];
        var nombreTxSupervisado = registro[5];
        if (idDxSindromico !=0 && idDxEtiologico !=0 && idTxSupervisado !=0){
            tmpReg = globalDiagnosticosTratamientoRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idDxSindromico == globalDiagnosticosTratamientoRelacionados[i][0] 
                    || "###"+idDxSindromico == globalDiagnosticosTratamientoRelacionados[i][0])
                && (idDxEtiologico == globalDiagnosticosTratamientoRelacionados[i][1] 
                    || "###"+idDxEtiologico == globalDiagnosticosTratamientoRelacionados[i][1])
                && (idTxSupervisado == globalDiagnosticosTratamientoRelacionados[i][2] 
                    || "###"+idTxSupervisado == globalDiagnosticosTratamientoRelacionados[i][2])
                    )
                flag = false;
            }
            if (flag){
                idDxSindromico = (tmpReg==0) ? idDxSindromico : "###"+idDxSindromico;
                globalDiagnosticosTratamientoRelacionados[tmpReg] = new Array(
                    idDxSindromico,idDxEtiologico,idTxSupervisado,
                    nombreDxSindromico,nombreDxEtiologico,nombreTxSupervisado
                    );
            }
        }       
    }
}

function destruirTabla(tabla){
    if(tabla == tablaSintomasSignos){
        globalSintomasSignosRelacionados = [];
        crearTabla(tablaSintomasSignos);
    }
    else if(tabla == tablaAntibioticos){
        globalAntibioticosRelacionados = [];
        crearTabla(tablaAntibioticos);
    }
    else if(tabla == tablaDiagnosticoTratamiento){
        globalDiagnosticosTratamientoRelacionados = [];
        crearTabla(tablaDiagnosticoTratamiento);
    }
}
