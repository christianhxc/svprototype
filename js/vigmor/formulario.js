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
    $( "#fecha_morgue" ).datepicker({
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
        
    $( "#def_evento1" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#def_evento1").val(li.selectValue);
            $("#defEvento1Nombre").val(li.selectValue);
            $("#defEvento1Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#def_evento2" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#def_evento2").val(li.selectValue);
            $("#defEvento2Nombre").val(li.selectValue);
            $("#defEvento2Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#def_evento3" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#def_evento3").val(li.selectValue);
            $("#defEvento3Nombre").val(li.selectValue);
            $("#defEvento3Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#def_eve_cierre1" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#def_eve_cierre1").val(li.selectValue);
            $("#defEveCierre1Nombre").val(li.selectValue);
            $("#defEveCierre1Id").val(li.extra[0]);
        },
        autoFill:false
    });
    $( "#def_eve_cierre2" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#def_eve_cierre2").val(li.selectValue);
            $("#defEveCierre2Nombre").val(li.selectValue);
            $("#defEveCierre2Id").val(li.extra[0]);
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
                $("#primer_nombre").val(utf8_decode(replace(partes[2])));
                $("#segundo_nombre").val(utf8_decode(replace(partes[3])));
                $("#primer_apellido").val(utf8_decode(replace(partes[4])));
                $("#segundo_apellido").val(utf8_decode(replace(partes[5])));
                if (partes[6]!='0000-00-00')
                    $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
                $("#lugar_poblado").val(utf8_decode(partes[15]));
                $("#telefono").val(partes[19]);
                $("#dir_trabajo").val(utf8_decode(partes[18]));
                $("#tel_laboral").val(partes[26]);
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
                    setRegionPersona(idProvincia, idRegion);
                    setDistritoPersona(idProvincia, idRegion, idDistrito);
                    setCorregimientoPersona(idDistrito, idCorregimiento);
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
    $("#drpEstadoCivil").val(0);
    $("#drpEscolaridad").val(0);

//$("#no_direccion_individuo").attr('checked',false);
//clickNoDirIndividuo();
}

function calcularSemana(fechaCal){
    validarFechas();
    if($("#fecha_defuncion").val()!=""){
        var fechaEpi = $("#fecha_defuncion").val().split("/");
        var time = fechaEpi[1]+"/"+fechaEpi[0]+"/"+fechaEpi[2];
        $("#fecha_defuncion").val( fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
        //alert(calcularSemanaEpi(time));
        var valores = "time="+time;
        pedirAJAX(urlprefix +"libs/semana_epi.php", recibirSemanaEpi, {timeout: 20,
                                                                metodo: metodoHTTP.POST,
                                                                parametrosPOST: valores,
                                                                cartelCargando: "divCargando",
                                                                cache: false,
                                                                onerror: procesarError});
    }
}

function procesarError(codigo){
    if (codigo === 602)
        alert("La p\xe1gina no responde, intentelo de nuevo mas tarde");
    else if (codigo === 404)
        alert("No se encontr\xf3 la p\xe1gina solicitada");
    else
        alert("hubo un error, codigo: "+codigo);
}

//function calcularSemana(fechaCal){
//    
//    validarFechas();
//    if($("#fecha_defuncion").val()!=""){
//        var fechaEpi = $("#fecha_defuncion").val().split("/");
//        fechaEpi= fechaEpi[2]+"/"+fechaEpi[1]+"/"+fechaEpi[0];
//        recibirSemanaEpi(calculate(fechaEpi));
//        var fechaEpi = $("#fecha_defuncion").val().split("/");
//        $("#fecha_defuncion").val(fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
//    }
//}

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
    var fecHospital = $("#fecha_hospitalizacion").val();
    var fecDefuncion = $("#fecha_defuncion").val();
    var fecMorgue = $("#fecha_morgue").val();
    var menor = false;
    if (fecHospital != ''){
        if (fecDefuncion != ''){
            if (fecMorgue != ''){
                menor = comparaFecha(1,fecDefuncion,fecMorgue);
                if (!menor){
                    alert("La Fecha de defuncion debe ser menor a la de ingreso a la morgue");
                    $("#fecha_morgue").val("");
                }
                menor = comparaFecha(1,fecHospital,fecDefuncion);
                if (!menor){
                    alert("La Fecha de Hospitalizacion debe ser menor a la de Defuncion");
                    $("#fecha_defuncion").val("");
                }
            }
            else {
               menor = comparaFecha(1,fecHospital,fecDefuncion);
               if (!menor){
                    alert("La Fecha de Hospitalizacion debe ser menor a la de Defuncion");
                    $("#fecha_defuncion").val("");
                }
            }
        }
        else if (fecMorgue != ''){
            menor = comparaFecha(1,fecHospital,fecMorgue);
            if (!menor){
                 alert("La Fecha de Hospitalizacion debe ser menor a la de ingreso a la morgue");
                 $("#fecha_morgue").val("");
             }
        }
    }
    else if (fecMorgue != '' && fecDefuncion != ''){
        menor = comparaFecha(1,fecDefuncion,fecMorgue);
        if (!menor){
            alert("La Fecha de defuncion debe ser menor a la de ingreso a la morgue");
            $("#fecha_morgue").val("");
        }
    }
    validarFechaNoti();
}

function validarFechaNoti(){
    var fecHospital = $("#fecha_hospitalizacion").val();
    var fecDefuncion = $("#fecha_defuncion").val();
    var fecNoti = $("#fecha_notificacion").val();
    var menor = false;
    if(fecNoti != ''){
        if (fecHospital != ''){
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

function validarVigmor(){
    var Message = '';
    var ErroresI = '';
    var ErroresC = '';
    var ErroresN = '';
    var ErrorIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
    var ErrorCondicion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos de la defunci&oacute;n:';
    var ErrorNotificacion ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos Notificaci&oacute;n:';
    
    //Individuo
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
    if($("#drpPais").val()==173){
        if($("#drpProIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia de la persona.";
        if($("#drpRegIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n de la persona.";
        if($("#drpDisIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de la persona.";
        if($("#drpCorIndividuo").val()==0)
            ErroresI+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento de la persona.";
    }
   
    //Datos clinicos
    if(jQuery.trim($("#fecha_defuncion").val())=="")
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Fecha de defunci&oacute;n."; 
    if(jQuery.trim($("#defEvento1Nombre").val())=="")
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar al menos el primer diagnostico.";
    if($("#drpEstadoEvento1").val()==0)
        ErroresC+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar al menos el estado del primer diagnostico.";
        
    //Notificacion
    if(jQuery.trim($("#notificacion_unidad").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la unidad notificadora.";
    if(jQuery.trim($("#fecha_notificacion").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de notificacion.";
    if(jQuery.trim($("#nombreInvestigador").val())=="")
        ErroresN+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del notificador.";
    
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
            $('#no_identificador1').attr('readonly', false);
            $('#no_identificador1').attr('disabled', '');
            $('#no_identificador2').attr('readonly', false);
            $('#no_identificador2').attr('disabled', '');
            $('#no_identificador3').attr('readonly', false);
            $('#no_identificador3').attr('disabled', '');
            $('#fecFormVigmor').attr('readonly', false);
            $('#fecFormVigmor').attr('disabled', '');
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
            
            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de VIGMOR, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de VIGMOR, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#dSummaryErrors").css('display','none');
                $('#frmContenido').submit();
            }
        }
    }
}
