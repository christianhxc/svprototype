var globalSintomasSignosRelacionados = new Array();

$(function() {
    $( "#fecha_nacimiento" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        yearRange: "1900:"+new Date().getFullYear() ,
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_ini_sintomas" ).datepicker({
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
    $( "#fecha_hospitalizacion" ).datepicker({
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
    $( "#fecha_defuncion" ).datepicker({
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
    $( "#fecha_muestra" ).datepicker({
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
    $( "#fecha_signos" ).datepicker({
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
    $( "#fecha_regional" ).datepicker({
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
    $( "#fecha_notificacion" ).datepicker({
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

function llenarSignosSintomas(){
    var signosSintomas = $( "#globalSignoSintomaRelacionados" ).val().split("###");
    for(var i=0; i<signosSintomas.length;i++){
        var signoSintoma = signosSintomas[i].split("#-#");
        llenarSignoSintoma(signoSintoma);
    }
    crearTablaSignoSintoma();
}

function llenarSignoSintoma(signoSintoma){
    var idSignoSintoma = signoSintoma[0];
    var nombreSignoSintoma = signoSintoma[1];
    var fechaSignoSintoma = signoSintoma[2];
    //var nombreEnfermedad = enfermedad[0];
    if (idSignoSintoma !="" && idSignoSintoma != 0 && fechaSignoSintoma !=0){
        
        var tmpReg = globalSintomasSignosRelacionados.length;
        idSignoSintoma = (tmpReg==0) ? idSignoSintoma : "###"+idSignoSintoma;
        globalSintomasSignosRelacionados[tmpReg] = new Array(idSignoSintoma,nombreSignoSintoma,fechaSignoSintoma);
    }
}

$(document).ready(function() {
    
    individuo($("#drpTipoId").val(), $("#no_identificador").val());
    // Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                if(ui.index==3)
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
    setTipoId();
        
    $( "#evento1" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento1").val(li.selectValue);
            $("#evento1Nombre").val(li.selectValue);
            $("#evento1Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#evento2" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento2").val(li.selectValue);
            $("#evento2Nombre").val(li.selectValue);
            $("#evento2Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#evento3" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento3").val(li.selectValue);
            $("#evento3Nombre").val(li.selectValue);
            $("#evento3Id").val(li.extra[0]);
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
    if ($("#numeroForm").val()!=''){
        lugarContagio();
        var idPaisConta = $("#drpPaisConta").val();
        if (idPaisConta == 174){
            setRegionPersona($("#idProConta").val(), $("#idRegConta").val(), 1);
            setDistritoPersona($("#idProConta").val(), $("#idRegConta").val(), $("#idDisConta").val(), 1);
            setCorregimientoPersona($("#idDisConta").val(), $("#idCorConta").val(), 1);
        }
        llenarSignosSintomas();
    }
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
                $('#drpTipoId').attr('readonly', true);
                $('#drpTipoId').attr('disabled', 'disabled');
                $("#tipoId").val(replace(partes[0]));
                if (partes[0]==1){
                    $arrayIdentificador = partes[1].split("-");
                    $("#no_identificador1").val($arrayIdentificador[0]);
                    $("#no_identificador2").val($arrayIdentificador[1]);
                    $("#no_identificador3").val($arrayIdentificador[2]);
                    $('#no_identificador1').attr('readonly', true);
                    $('#no_identificador1').attr('disabled', 'disabled');
                    $('#no_identificador2').attr('readonly', true);
                    $('#no_identificador2').attr('disabled', 'disabled');
                    $('#no_identificador3').attr('readonly', true);
                    $('#no_identificador3').attr('disabled', 'disabled');
                }
                else{    
                    $("#no_identificador").val(replace(partes[1]));
                    $('#no_identificador').attr('readonly', true);
                    $('#no_identificador').attr('disabled', 'disabled');
                }
                setTipoId();
                $("#primer_nombre").val((replace(partes[2])));
                $("#segundo_nombre").val((replace(partes[3])));
                $("#primer_apellido").val((replace(partes[4])));
                $("#segundo_apellido").val((replace(partes[5])));
                if (partes[6]!='0000-00-00')
                    $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
                $("#lugar_poblado").val(utf8_decode(partes[15]));
                $("#telefono").val(partes[19]);
                $("#punto_ref").val(utf8_decode(partes[18]));
                $idPais = partes[25];
                $("#drpPais").val($idPais);
                if ($idPais == 174){
                    idProvincia = partes[11];
                    idRegion = partes[12];
                    idDistrito = partes[13];
                    idCorregimiento = partes[14];

                    $("#idPro").val(idProvincia);
                    $("#idReg").val(idRegion);
                    $("#idDis").val(idDistrito);
                    $("#idCor").val(idCorregimiento);

                    $("#drpProIndividuo").val(idProvincia);
                    setRegionPersona(idProvincia, idRegion, 0);
                    setDistritoPersona(idProvincia, idRegion, idDistrito, 0);
                    setCorregimientoPersona(idDistrito, idCorregimiento, 0);
                }
                else{
                    $("#divPolitica1").css( "display", "none" ); 
                    $("#divPolitica2").css( "display", "none" );
                }
               
                $("#resultadosBusqueda").html('');
                $("#dialog-form").dialog('close');
                found = true;
                if($("#fecha_nacimiento").val()!=="")
                    calcularEdad();
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
    $("#no_identificador1").val("");
    $("#no_identificador2").val("");
    $("#no_identificador3").val("");
    setTipoId();

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

//$("#no_direccion_individuo").attr('checked',false);
//clickNoDirIndividuo();
}

function calcularSemana(val){
    validarFechas();
    if($("#fecha_ini_sintomas").val()!=""){
        var fechaEpi = $("#fecha_ini_sintomas").val().split("/");
        fechaEpi= fechaEpi[2]+"/"+fechaEpi[1]+"/"+fechaEpi[0];
        recibirSemanaEpi(calculate(fechaEpi));
        var fechaEpi = $("#fecha_ini_sintomas").val().split("/");
        $("#fecha_ini_sintomas").val(fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
    }
}

function recibirSemanaEpi(val){
    var datos = val.split("###");
    $("#semana_epi").val(datos[0]);
    $("#anio_epi").val(datos[1]);
}

function validarHora(){
    var hora = $("#def_hora").val();
    $("#def_hora").val(setHoras(hora));
}

function validarMin(){
    var min = $("#def_minutos").val();
    $("#def_minutos").val(setMinutos(min));
}

function validarEdad(){
    var tipoEdad = $("#drptipo_edad").val();
    var edad = $("#edad").val();
    if (tipoEdad == 1){
        if (edad > 30){
            $("#edad").val("");
            alert("Si selecciona dias, debe ser menor de 30, datos mayores ya son tipo edad meses");
        }
    }
    else if (tipoEdad == 2){
        if (edad > 12){
            $("#edad").val("");
            alert("Si selecciona meses, debe ser menor de 12, datos mayores ya son tipo edad anios");
        }
    }
    else if (tipoEdad == 4){
        if (edad > 24){
            $("#edad").val("");
            alert("Si selecciona horas, debe ser menor de 24, datos mayores ya son tipo edad dias");
        }
    }
    else if (tipoEdad == 5){
        if (edad > 60){
            $("#edad").val("");
            alert("Si selecciona minutos, debe ser menor de 60, datos mayores ya son tipo edad horas");
        }
    }
}

function validarFechas(){
    var fecIniSintomas = $("#fecha_ini_sintomas").val();
    var fecHospital = $("#fecha_hospitalizacion").val();
    var fecDefuncion = $("#fecha_defuncion").val();
    var fecMuestra = $("#fecha_muestra").val();
    var fecSignos = $("#fecha_signos").val();
    var menor = false;
    if (fecIniSintomas != ''){
        if (fecHospital != ''){
            if (fecDefuncion != ''){
                menor = comparaFecha(1,fecHospital,fecDefuncion);
                if (!menor){
                    alert("La Fecha de Hospitalizacion debe ser menor a la de defuncion");
                    $("#fecha_defuncion").val("");
                }
                menor = comparaFecha(1,fecIniSintomas,fecHospital);
                if (!menor){
                    alert("La Fecha de Inicio de sintomas debe ser menor a la de Hospitalizacion");
                    $("#fecha_hospitalizacion").val("");
                    $("#fecha_defuncion").val("");
                }
            }
            else {
               menor = comparaFecha(1,fecIniSintomas,fecHospital);
               if (!menor){
                    alert("La Fecha de Inicio de sintomas debe ser menor a la de Hospitalizacion");
                    $("#fecha_hospitalizacion").val("");
                }
            }
        }
        else if (fecDefuncion != ''){
            menor = comparaFecha(1,fecIniSintomas,fecDefuncion);
            if (!menor){
                 alert("La Fecha de Inicio de sintomas debe ser menor a la de Defuncion");
                 $("#fecha_defuncion").val("");
             }
        }
        if (fecMuestra != ''){
            menor = comparaFecha(1,fecIniSintomas,fecMuestra);
            if (!menor){
                alert("La Fecha de Inicio de sintomas debe ser menor a la de Toma de muestra");
                $("#fecha_muestra").val("");
            }
        }
        if (fecSignos != ''){
            menor = comparaFecha(1,fecIniSintomas,fecSignos);
            if (!menor){
                alert("La Fecha de Inicio de sintomas debe ser menor a la de Signos y Sintomas");
                $("#fecha_signos").val("");
            }
        }
        validarFechaNoti();
    }
    else{
        alert("Debes ingresar primero la fecha de inicio de sintomas antes que cualquier otra fecha");
        $("#fecha_signos").val("");
        $("#fecha_muestra").val("");
        $("#fecha_defuncion").val("");
        $("#fecha_hospitalizacion").val("");
    }
}

function validarFechaNoti(){
    var fecHospital = $("#fecha_hospitalizacion").val();
    var fecDefuncion = $("#fecha_defuncion").val();
    var fecIniSintomas = $("#fecha_ini_sintomas").val();
    var fecNoti = $("#fecha_notificacion").val();
    var menor = false;
    if(fecNoti != ''){
        if (fecIniSintomas != ''){
            menor = comparaFecha(1,fecIniSintomas,fecNoti);
            if (!menor){
                alert("La Fecha de notificacion debe ser mayor a la de Inicio de sintomas");
                $("#fecha_ini_sintomas").val("");
            }
        }
        else if (fecHospital != ''){
            menor = comparaFecha(1,fecHospital,fecNoti);
            if (!menor){
                alert("La Fecha de notificacion debe ser mayor a la de hospitalizacion");
                $("#fecha_notificacion").val("");
            }
        }
        else if (fecDefuncion != ''){
            menor = comparaFecha(1,fecDefuncion,fecNoti);
            if (!menor){
                alert("La Fecha de notificacion debe ser mayor a la de defuncion");
                $("#fecha_notificacion").val("");
            }
        }
    }
}

function setTipoId(){
    var tipo = $("#drpTipoId").val();
    if (tipo != 1){
        $("#divCedula1").show();
        $("#divCedula2").hide();
    }
    else{
        $("#divCedula1").hide();
        $("#divCedula2").show();
    }
}

function lugarContagio(){
    var contagio = $("#drpContagio").val();
    if (contagio==1 || contagio==5){
        $("#divPolitica00").css( "display", "none" );
        $("#divPolitica11").css( "display", "none" ); 
        $("#divPolitica22").css( "display", "none" ); 
        $("#divPolitica33").css( "display", "none" );
    }
    else{
        $("#divPolitica00").css( "display", "" ); 
        $("#divPolitica11").css( "display", "" );
        $("#divPolitica22").css( "display", "" );
        $("#divPolitica22").css( "display", "" );
    }
}

function setPaisConta(){
    var pais = $("#drpPaisConta").val();
    if (pais==174){
        $("#divPolitica11").css( "display", "" ); 
        $("#divPolitica22").css( "display", "" ); 
    }
    else{
        $("#divPolitica11").css( "display", "none" ); 
        $("#divPolitica22").css( "display", "none" ); 
    }
}

function setPais(){
    var pais = $("#drpPais").val();
    if (pais==174){
        $("#divPolitica1").css( "display", "" ); 
        $("#divPolitica2").css( "display", "" ); 
    }
    else{
        $("#divPolitica1").css( "display", "none" ); 
        $("#divPolitica2").css( "display", "none" ); 
    }
}


function setRegionCascada(tipo){
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

function validarNotic(){
    var Message = '';
    var ErroresI = '';
    var ErroresC = '';
    var ErroresN = '';
    var ErrorIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
    var ErrorClinica ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos clinicos - epidemiologicos:';
    var ErrorNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Notificaci&oacute;n:';
    var asegurado = 0;
    //Individuo
    if ($("#aseguradoSi").is(":checked"))
        asegurado = 1;
    else if ($("#aseguradoNo").is(":checked"))
        asegurado = 1;
    else if ($("#aseguradoNoSabe").is(":checked"))
        asegurado = 1;
    if(asegurado==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el paciente esta o no asegurado.";
    if($("#drpTipoId").val()==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de identificador.";
    if($("#drpTipoId").val() == 1){
        if(jQuery.trim($("#no_identificador1").val())=="" || jQuery.trim($("#no_identificador2").val())=="" || jQuery.trim($("#no_identificador3").val())=="" )
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador en las 3 casillas.";
    }
    else{
        if(jQuery.trim($("#no_identificador").val())=="")
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
    }
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
    if($("#drpPais").val()==174){
        if($("#drpProIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia de la persona.";
        if($("#drpRegIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n de la persona.";
        if($("#drpDisIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de la persona.";
        if($("#drpCorIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";
    }
    $lugarContagio = $("#drpContagio").val();
    if($lugarContagio==0)
        ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el lugar donde se presume ocurrio el contagio.";
    if ($lugarContagio!=1&&$lugarContagio!=5){
        if ($("#drpPaisConta").val()==="")
            $("#drpPaisConta").val(174);
        if($("#drpPaisConta").val()==174){
            if($("#drpProConta").val()==0)
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia del lugar de contagio.";
            if($("#drpRegConta").val()==0)
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n del lugar de contagio.";
            if($("#drpDisConta").val()==0)
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito del lugar de contagio.";
            if($("#drpCorConta").val()==0)
                ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento del lugar de contagio.";
        }
    }
    
    else{
        if ($("#drpPaisConta").val()===0){
            var idPais = $("#drpPaisConta").val() === "" ? 174:$("#drpPaisConta").val();
            $("#drpPaisConta").val(idPais);
        }
        if ($("#drpCorConta").val()===0)
            $("#drpCorConta").val($("#drpCorIndividuo").val());
    }
   
    //Datos clinicos
    if(jQuery.trim($("#evento1Nombre").val())=="")
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar al menos el primer diagnostico.";
    if($("#drpEstadoEvento1").val()==0)
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar al menos el estado del primer diagnostico.";
    if(jQuery.trim($("#fecha_ini_sintomas").val())=="")
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de inicio de sintomas.";
        
    //Notificacion
    if(jQuery.trim($("#notificacion_unidad").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la unidad notificadora.";
    if(jQuery.trim($("#drpServicio").val())==0)
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el servicio o NO Aplica.";
    if(jQuery.trim($("#drpCargo").val())==0)
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el cargo de la persona que notifica.";
    if(jQuery.trim($("#fecha_notificacion").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de notificacion.";
    if(jQuery.trim($("#nombreInvestigador").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del notificador.";
    
    (ErroresI=="")? ErrorIndividuo="": ErrorIndividuo = ErrorIndividuo+ErroresI + "<br/>";
    (ErroresC=="")? ErrorClinica="": ErrorClinica = ErrorClinica+ErroresC + "<br/>";
    (ErroresN=="")? ErrorNotificacion="": ErrorNotificacion = ErrorNotificacion+ErroresN + "<br/>";
    Message = ErrorIndividuo + ErrorClinica + ErrorNotificacion;
    
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
            $('#no_identificador1').attr('readonly', false);
            $('#no_identificador1').attr('disabled', '');
            $('#no_identificador2').attr('readonly', false);
            $('#no_identificador2').attr('disabled', '');
            $('#no_identificador3').attr('readonly', false);
            $('#no_identificador3').attr('disabled', '');
            $('#fecForm').attr('readonly', false);
            $('#fecForm').attr('disabled', '');
            $('#semana_epi').attr('readonly', false);
            $('#semana_epi').attr('disabled', '');
            $('#anio_epi').attr('readonly', false);
            $('#anio_epi').attr('disabled', '');
            $('#form_hora').attr('readonly', false);
            $('#form_hora').attr('disabled', '');
            $('#form_minutos').attr('readonly', false);
            $('#form_minutos').attr('disabled', '');
            $('#form_tipo_horaAM').attr('readonly', false);
            $('#form_tipo_horaAM').attr('disabled', '');
            $('#form_tipo_horaPM').attr('readonly', false);
            $('#form_tipo_horaPM').attr('disabled', '');
            $
            
            var param = '';
            $('#globalSignoSintomaRelacionados').val("");
            var i=0;
            for(i=0; i<globalSintomasSignosRelacionados.length;i++){
                if(__isset(globalSintomasSignosRelacionados[i])){
                    if (globalSintomasSignosRelacionados[i][0]!="")
                        param+=globalSintomasSignosRelacionados[i][0]+"#-#"+globalSintomasSignosRelacionados[i][2];//Sintomas y fecha
                }
            }
            $('#globalSignoSintomaRelacionados').val(param);
            
            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de NOTIC, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de NOTIC, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#dSummaryErrors").css('display','none');
                $('#frmContenidoNotic').submit();
            }
        }
    }
}

function relacionarSignoSintoma(){
    var idSignoSintoma = $("#drpSignos").val();
    var nombreSignoSintoma = $("#drpSignos").find(":selected").text();
    var fechaSignoSintoma = $("#fecha_signos").val();
    
    if (idSignoSintoma !="" && idSignoSintoma != 0 && fechaSignoSintoma !=0){
        var tmpReg = globalSintomasSignosRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idSignoSintoma == globalSintomasSignosRelacionados[i][0] || "###"+idSignoSintoma == globalSintomasSignosRelacionados[i][0]))
                flag = false;
        }
        if (flag){
            idSignoSintoma = (tmpReg==0) ? idSignoSintoma : "###"+idSignoSintoma;
            globalSintomasSignosRelacionados[tmpReg] = new Array(idSignoSintoma,nombreSignoSintoma,fechaSignoSintoma);
            crearTablaSignoSintoma();
        }
        else
            alert ("Ya existe un registro para esta relacion");
    }
    else
        alert("Debe seleccionar un Signo o Sintoma y su fecha");
}

function crearTablaSignoSintoma(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="70%">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Signo y Sintoma</th>'+
    '<th class="dxgvHeader_PlasticBlue">Fecha</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalSintomasSignosRelacionados.length;i++){
        if(__isset(globalSintomasSignosRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="180px">'+globalSintomasSignosRelacionados[i][1]+'</th>'+
            '<td class="fila" width="80px">'+globalSintomasSignosRelacionados[i][2]+'</th>'+
            '<td class="fila" width="30px" align="center"><a href="javascript:eliminarRelSignoSintoma('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divSignos").html(tabla);
}

function eliminarRelSignoSintoma(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con el signo o sintoma "+globalSintomasSignosRelacionados[pos][1])){
        globalSintomasSignosRelacionados.splice(pos, 1);
        crearTablaSignoSintoma();
    }
}