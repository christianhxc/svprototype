var globalCondicionesRelacionados = new Array();

$(function() {
    $( "#popup_info" ).dialog({
        autoOpen: false,
        modal: true,
        width: 500        
    });
});

$(function() {
    var fecha_nacimiento = $("#fecha_nacimiento").val();
    var permisoEspecial = $("#per_especial").val();
    if (fecha_nacimiento !== ""){
        if (permisoEspecial === "1"){
            $( "#fecha_nacimiento" ).datepicker({
                changeYear: true,
                showOn: "both",
                yearRange: "1900:"+new Date().getFullYear() ,
                maxDate: new Date(),
                buttonImage: urlprefix+"img/calendar.gif",
                buttonImageOnly: true,
                showAnim: "slideDown"
            });
        }
    }
    else{
        $( "#fecha_nacimiento" ).datepicker({

            changeYear: true,
            showOn: "both",
            yearRange: "1900:"+new Date().getFullYear() ,
            maxDate: new Date(),
            buttonImage: urlprefix+"img/calendar.gif",
            buttonImageOnly: true,
            showAnim: "slideDown"
        });
    }
    $( "#fecha_formulario" ).datepicker({
        
        changeYear: true,
        showOn: "both",
        yearRange: "2000:"+new Date().getFullYear() ,
        maxDate: new Date(),
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

$(document).ready(function() {
    
    veficar_data_new();
    
    //individuo($("#drpTipoId").val(), $("#no_identificador").val());
    // Popup de búsqueda
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
    $("#botonSubir").click(function(){
        // Indicamos lo que tiene que hacer la animación y el
        // tiempo que tiene que durar
        $('html,frmContenidoNotic').animate({scrollTop:0},{duration:"slow"})
    });
    // Popup cambio de identificacion
    $( "#dialog-form-cambio" ).dialog({
        autoOpen: false,
        height: 500,
        width: 750,
        modal: true,
        position: 'center',
        buttons: {
            Salir: function() {
                borrarNuevoId();
                $( this ).dialog( "close" );
            }
        }
    });

    $( "#dialog-form-cambio" ).bind("dialogclose",function(){
        borrarNuevoId();
    });
    // Ejecutamos la función manualmente, ya que no todos los navegadores
    // ejecutan el evento scroll al cargar una página, aunque se posicione
    // en una posición inferior con algún "ancla"
    crearBoton();

    /**
     * Si se ejecuta el evento scroll o el evento resize, se ejecuta la
     * función crearBoton()
     */
    $(document).scroll(function(){
            crearBoton();
    });
    $(window).resize(function(){
            crearBoton();
    });
    
    if ($("#idForm").val()!=""){
        var idPais = $("#drpPais").val();
        if (idPais == 174){
            var idProvincia = $("#idPro").val();
            var idRegion = $("#idReg").val();
            var idDistrito = $("#idDis").val();
            var idCorregimiento = $("#idCor").val();
            $("#drpProIndividuo").val(idProvincia);
            setRegionPersona(idProvincia, idRegion, 0);
            setDistritoPersona(idProvincia, idRegion, idDistrito, 0);
            setCorregimientoPersona(idDistrito, idCorregimiento, 0);
        }
        else{
            $("#divPolitica1").css( "display", "none" ); 
            $("#divPolitica2").css( "display", "none" );
        }
    }
    
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
    
    $('#nombreRegistra').attr('readonly', true);
    $('#nombreRegistra').attr('disabled', 'disabled');
    
    borrarTabla();
    setTipoId();
    setTipoIdNuevo();
    setModalidad();
    llenarCondiciones();
    traerTablaDosis();
    
    var fechaNac = $("#fecha_nacimiento").val();
    if ( fechaNac !== "")
        calcularEdad();
});


function veficar_data_new()
{
    
    if ($("#inst_salud_guardar").val() == ""){
      
        $("#notificacion_unidad").val($("#inst_salud_new").val());
        $("#nombreInvestigador").val($("#funcionario_new").val());
        $("#drpModalidad").val($("#mod_apli").val());
        $("#fecha_formulario").val($("#fecha_aplicacion").val());
    } else
    {
        $("#notificacion_unidad").val($("#inst_salud_guardar").val());
        $("#nombreInvestigador").val($("#funcionario_guardar").val());
        $("#drpModalidad").val($("#mod_apli_guardar").val());
        $("#fecha_formulario").val($("#fecha_aplicacion_guardar").val());
        
    }
}

function crearBoton(){
    // Revisamos que el scroll este por encima de 250 píxeles para
    // mostrar el botón de subir
    var scrollTop = $(document).scrollTop();
    (scrollTop>200) ? $("#botonSubir").show():$("#botonSubir").hide();
}

function traer_info(id_dosis){
    $( "#popup_info" ).dialog("open");
    var valores = "id_dosis="+id_dosis;
    pedirAJAX(urlprefix +"vacunas/TraerDatosDosis.php", recibirDatosDosis, {timeout: 20,
                                                                                metodo: metodoHTTP.POST,
                                                                                parametrosPOST: valores,
                                                                                cartelCargando: "divCargando",
                                                                                cache: false,
                                                                                onerror: procesarError});
    
}

function recibirDatosDosis(val){
    $("#info").html(val);
}

function traerTablaDosis(){
    var fechaFormulario = $("#fecha_formulario").val();
    var tipoId = $("#drpTipoId").val();
    var numeroId =  (tipoId == 1) ? $("#no_identificador1").val()+"-"+$("#no_identificador2").val()+"-"+$("#no_identificador3").val() :  $("#no_identificador").val();
    var idRegistroDiario = $("#idForm").val();
    if (tipoId!= "" && numeroId != "" && idRegistroDiario != ""){
        var valores = "numero_identificacion="+numeroId+"&tipo_identificacion="+tipoId+"&registro_diario="+idRegistroDiario+"&fecha_formulario="+fechaFormulario;
        pedirAJAX(urlprefix +"vacunas/TraerDosisPorPersona.php", recibirDosisPorPersona, {timeout: 20,
                                                                                    metodo: metodoHTTP.POST,
                                                                                    parametrosPOST: valores,
                                                                                    cartelCargando: "divCargando",
                                                                                    cache: false,
                                                                                    onerror: procesarError});
    }
}

function recibirDosisPorPersona(val){
    $("#showTable").html(val);
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function borrarNuevoId(){
    $("#id_nuevo").val("");
    $("#id_nuevo1").val("");
    $("#id_nuevo2").val("");
    $("#id_nuevo3").val("");
    $("#nActual").val("");
    $("#n2Actual").val("");
    $("#pActual").val("");
    $("#p2Actual").val("");
    $("#idActual").val("");
    $("#drpTipoIdActual").val(0);
    $("#drpTipoIdNuevo").val(0);
    setTipoIdNuevo();
}

function cambiarIdentificacion(){
    $( "#dialog-form-cambio" ).dialog("open");
    $("#drpTipoIdActual").val($("#drpTipoId").val());
    if ($("#drpTipoId").val()!=1)
        $("#idActual").val($("#no_identificador").val());
    else
        $("#idActual").val($("#no_identificador1").val()+"-"+$("#no_identificador2").val()+"-"+$("#no_identificador3").val());
    $("#nActual").val($("#primer_nombre").val());
    $("#n2Actual").val($("#segundo_nombre").val());
    $("#pActual").val($("#primer_apellido").val());
    $("#p2Actual").val($("#segundo_apellido").val());
    setTipoIdNuevo();
}

function validarPopupCambioId(){
    var nuevoTipoId = $("#drpTipoIdNuevo").val();
    if (nuevoTipoId != 0){
        var nuevoNumId = $("#id_nuevo").val();
        if (nuevoTipoId == 1){
          if ($("#id_nuevo1").val()!="" && $("#id_nuevo2").val() != "" && $("#id_nuevo3").val() != "")
              nuevoNumId = $("#id_nuevo1").val()+"-"+$("#id_nuevo2").val()+"-"+$("#id_nuevo3").val();
        }
            
        if (nuevoNumId != ""){
            var actualNumId = $("#idActual").val();
            var actualTipoId = $("#drpTipoIdActual").val();
            if ( confirm("Esta seguro que quiere cambiar estos datos?\nActual ID: "+actualTipoId+" - "+actualNumId+" \n Nuevo ID: "+nuevoTipoId+" - "+nuevoNumId)){
                var valores = "numActual="+actualNumId+"&tipoActual="+actualTipoId+"&numNuevo="+nuevoNumId+"&tipoNuevo="+nuevoTipoId;
                pedirAJAX(urlprefix +"vacunas/ActualizarIdentificacion.php", recibirCambioId, {timeout: 20,
                                                                                            metodo: metodoHTTP.POST,
                                                                                            parametrosPOST: valores,
                                                                                            cartelCargando: "divCargando",
                                                                                            cache: false,
                                                                                            onerror: procesarError});
            }
        }
        else 
            alert ("debe ingresar el nuevo numero de Identificacion")
    }
    else
        alert("Debe seleccionar el nuevo tipo de Identificacion");
}

function recibirCambioId(texto){
    $( "#dialog-form-cambio" ).dialog("close");
    if (texto === "")
        location.reload();
    else 
        alert(texto);
}

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
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
        + "&n="+jQuery.trim($("#n").val()) + "&tipoid="+$("#drpTipoId2").val() 
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

function setModalidad(){
    var selModalidad = $("#drpModalidad").val().split("###");
    if (selModalidad[1]=="1")  
        $("#divNombreModalidad").show();
    else {
        $("#divNombreModalidad").hide();
        $("#nombre_modalidad_otro").val("");
    }
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
                $("#primer_nombre").val(replace(partes[2]));
                $("#segundo_nombre").val(replace(partes[3]));
                $("#primer_apellido").val(replace(partes[4]));
                $("#segundo_apellido").val(replace(partes[5]));
                if (partes[6]!='0000-00-00')
                    $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
                $("#drptipo_edad").val(partes[7]);
                $("#edad").val(partes[8]);
                $("#drpsexo").val(partes[9]);
                $("#nombre_responsable").val(partes[10]);
                $("#lugar_poblado").val(partes[15]);
                $("#telefono").val(partes[19]);
                $("#punto_ref").val(partes[18]);
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
                $("#correo_electronico").val(partes[27]);
                
                $('#fecha_nacimiento').attr('readonly', true);
                $('#fecha_nacimiento').attr('disabled', 'disabled');
                var permisoEspecial = $("#per_especial").val();
                if (permisoEspecial === 1){
                    $('#fecha_nacimiento').attr('readonly', false);
                    $('#fecha_nacimiento').attr('disabled', '');
                }
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

function setTipoIdNuevo(){
    var tipo = $("#drpTipoIdNuevo").val();
    if (tipo != 1){
        $("#divCedula1Nuevo").show();
        $("#divCedula2Nuevo").hide();
    }
    else{
        $("#divCedula1Nuevo").hide();
        $("#divCedula2Nuevo").show();
    }
}

function calcularSemana(val){
    if($("#fecha_notificacion").val()!=""){
        var fechaEpi = $("#fecha_notificacion").val().split("/");
        fechaEpi= fechaEpi[2]+"/"+fechaEpi[1]+"/"+fechaEpi[0];
        recibirSemanaEpi(calculate(fechaEpi));
        var fechaEpi = $("#fecha_notificacion").val().split("/");
        $("#fecha_notificacion").val(fechaEpi[0]+"/"+fechaEpi[1]+"/"+fechaEpi[2]);
    }
}

function recibirSemanaEpi(val){
    var datos = val.split("###");
    $("#semana_epi").val(datos[0]);
    $("#anio_epi").val(datos[1]);
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
    }
    else{
        alert("Debes ingresar primero la fecha de inicio de sintomas antes que cualquier otra fecha");
        $("#fecha_signos").val("");
        $("#fecha_muestra").val("");
        $("#fecha_defuncion").val("");
        $("#fecha_hospitalizacion").val("");
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

function validarRegistroDiario(){
    var Message = '';
    var ErroresI = '';
    var ErroresN = '';
    var ErrorIndividuo ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
    var asegurado = 0;
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
           
    (ErroresI=="")? ErrorIndividuo="": ErrorIndividuo = ErrorIndividuo+ErroresI + "<br/>";
    Message = ErrorIndividuo;

    $("#inst_salud_guardar").val($("#notificacion_unidad").val());
    $("#funcionario_guardar").val($("#nombreInvestigador").val());
    $("#fecha_aplicacion_guardar").val($("#fecha_formulario").val());
    $("#mod_apli_guardar").val($("#drpModalidad").val());
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
            $('#fecha_nacimiento').attr('readonly', false);
            $('#fecha_nacimiento').attr('disabled', '');
            $('#edad').attr('readonly', false);
            $('#edad').attr('disabled', '');
            $('#drptipo_edad').attr('readonly', false);
            $('#drptipo_edad').attr('disabled', '');
            $('#globalCondicionesRelacionados').val("");
            var param = '';
            var i=0;
            for(i=0; i<globalCondicionesRelacionados.length;i++){
                if(__isset(globalCondicionesRelacionados[i])){
                    if (globalCondicionesRelacionados[i][0]!=="")
                        param+=globalCondicionesRelacionados[i][0];//Sintomas y fecha
                }
            }
            $('#globalCondicionesRelacionados').val(param);
                        
            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de Registro Diario de Vacunas, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de Registro Diario de Vacunas, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#dSummaryErrors").css('display','none');
                $('#frmContenidoNotic').submit();
            }
        }
    }
}

function nuevoRegistro(){
    if (confirm("Esta seguro de crear un nuevo registro? guarde sus cambios o los puede perder")){
        
        $("#inst_salud_new").val($("#notificacion_unidad").val());
        $("#funcionario_new").val($("#nombreInvestigador").val());
        $("#fecha_aplicacion").val($("#fecha_formulario").val());
        $("#mod_apli").val($("#drpModalidad").val());
        $('#nuevo_registro').submit();
    }
        
//        window.location = urlprefix+"vacunas/registroDiario.php";
    
}


function llenarCondiciones(){
    if ($("#globalCondicionesRelacionados" ).val() != ""){
        var condiciones = $("#globalCondicionesRelacionados" ).val().split("###");
        for(var i=0; i<condiciones.length;i++){
            var condicion = condiciones[i].split("#-#");
            llenarCondicion(condicion);
        }
        crearTablaCondicion();
    }
}

function llenarCondicion(condicion){
    var idCondicion = condicion[0];
    var nombreCondicion = condicion[1];
    if (idCondicion !=""){
        var tmpReg = globalCondicionesRelacionados.length;
        idCondicion = (tmpReg==0) ? idCondicion : "###"+idCondicion;
        globalCondicionesRelacionados[tmpReg] = new Array(idCondicion,nombreCondicion);
    }
}
    
function relacionarCondiciones(){
    var idCondicion = $("#drpCondicion").val();
    var nombreCondicion = $("#drpCondicion").find(":selected").text();
    
    if (idCondicion != 0){
        
        var tmpReg = globalCondicionesRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (idCondicion == globalCondicionesRelacionados[i][0] || "###"+idCondicion == globalCondicionesRelacionados[i][0])
                flag = false;
        }
        if (flag){
            idCondicion = (tmpReg==0) ? idCondicion : "###"+idCondicion;
            globalCondicionesRelacionados[tmpReg] = new Array(idCondicion,nombreCondicion);
            $("#drpCondicion").val(0);
            crearTablaCondicion();
        }
        else{
            alert ("Ya existe un registro para esta relacion");
            $("#drpCondicion").val(0);
        }
    }
    else
        alert("Debe seleccionar una de las condiciones");
}

function crearTablaCondicion(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%" align="center">'+
    '<tr>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Condicion</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '</tr>';
    for(var i=0; i<globalCondicionesRelacionados.length;i++){
        if(__isset(globalCondicionesRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalCondicionesRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarCondiciones('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divTablaCondicion").html(tabla);
}

function eliminarCondiciones(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon la condicion")){
        globalCondicionesRelacionados.splice(pos, 1);
        crearTablaCondicion();
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

function guardar_dosis_registro(idDosis,idEsquema){
    var idDosis = idDosis;
    var idFormRegistro = $("#id_form_registro").val();
    var fechaRegistro = $("#fecha_formulario").val();
    var lote1 = $("#lote1_"+idDosis).val();
    var lote2 = $("#lote2_"+idDosis).val();
    var lote3 = $("#lote3_"+idDosis).val();
    var idUn = $("#notificacion_id_un").val();
    var selModalidad = $("#drpModalidad").val().split("###");
    var idModalidad =  selModalidad[0];
    var otroModalidad = $("#nombre_modalidad_otro").val();
    var drpZona = $("#drpZona").val();
    var nombreFuncionario = $("#nombreInvestigador").val();
    $('#nombreRegistra').attr('readonly', false);
    $('#nombreRegistra').attr('disabled', '');
    var nombreRegistra = $("#nombreRegistra").val();
    var edad = $("#edad_vac").val();
    var tipo_edad = $("#drptipo_edad_vac").val();
    var Errores = "";
    if(idUn==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Instalaci&oacute;n de Salud o Unidad Notificadora.";
    if(idModalidad === 0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Modalidad (Incluye la opci&oacute;n Visita hogar - Otros).";
    if(drpZona ===0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Zona.";
    if(nombreFuncionario === "")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del funcionario encargado.";
    if(fechaRegistro === "")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de aplicacion de la dosis en el formato dd/mm/yyyy.";
    if (Errores === ""){
        $('#pSummaryErrorsDosis').html('');
        $('#dSummaryErrorsDosis').hide();
        if (fechaRegistro!= "" && idDosis != "" && idFormRegistro != ""){
//            var fechaSlit = fechaRegistro.split("/");
//            fechaRegistro = fechaSlit[2]+"-"+fechaSlit[1]+"-"+fechaSlit[0];
            var valores = "mode=save_new&id_dosis="+idDosis+"&id_form_registro="+idFormRegistro+"&fecha_reporte="+fechaRegistro+"&lote1="+lote1+"&lote2="+lote2+"&lote3="+lote3;
            valores += "&id_un="+idUn+"&id_modalidad="+idModalidad+"&id_zona="+drpZona+"&nombre_funcionario="+nombreFuncionario+"&nombre_registra="+nombreRegistra;
            valores += "&otro_modalidad="+otroModalidad+"&edad="+edad+"&tipo_edad="+tipo_edad;
            valores += "&esquema="+idEsquema;
            pedirAJAX(urlprefix +"js/dynamic/vacunas/accionesDosisRegistroDiario.php", recibirAcciones, {timeout: 20,
                                                                                        metodo: metodoHTTP.POST,
                                                                                        parametrosPOST: valores,
                                                                                        cartelCargando: "divCargando",
                                                                                        cache: false,
                                                                                        onerror: procesarError});
        }
    }
    else {
        var Message = "<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos que hacen falta de la Dosis:"+Errores;
        $('#dSummaryErrorsDosis').show();
        $('#pSummaryErrorsDosis').html(Message);
    }
}

function borrar_dosis_registro(idDosis){
    var idDosis = idDosis;
    
    var valores = "mode=delete&id_dosis="+idDosis
    
    pedirAJAX(urlprefix +"js/dynamic/vacunas/accionesDosisRegistroDiario.php", recibirAcciones, {timeout: 20,
                                                                            metodo: metodoHTTP.POST,
                                                                            parametrosPOST: valores,
                                                                            cartelCargando: "divCargando",
                                                                            cache: false,
                                                                            onerror: procesarError});
}

function editar_dosis_registro(idDosis){
    var idDosis = idDosis;
    var idFormRegistro = $("#id_form_registro").val();
    var fechaRegistro = $("#fec_"+idDosis).val();
    var lote1 = $("#lote1_"+idDosis).val();
    var lote2 = $("#lote2_"+idDosis).val();
    var lote3 = $("#lote3_"+idDosis).val();
    var idUn = $("#notificacion_id_un").val();
    var selModalidad = $("#drpModalidad").val().split("###");
    var idModalidad =  selModalidad[0];
    var otroModalidad = $("#nombre_modalidad_otro").val();
    var drpZona = $("#drpZona").val();
    var nombreFuncionario = $("#nombreInvestigador").val();
    var nombreRegistra = $("#nombreRegistra").val();
    var Errores = "";
    if(idUn==="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Instalaci&oacute;n de Salud o Unidad Notificadora.";
    if(idModalidad === 0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Modalidad (Incluye la opci&oacute;n Visita hogar - Otros).";
    if(drpZona ===0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Zona.";
    if(nombreFuncionario === "")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del funcionario encargado.";
    if(fechaRegistro === "")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha del registro de la dosis en el formato dd/mm/yyyy.";
    if (Errores === ""){
        if (fechaRegistro!= "" && idDosis != "" && idFormRegistro != ""){
            var fechaSlit = fechaRegistro.split("/");
            fechaRegistro = fechaSlit[2]+"-"+fechaSlit[1]+"-"+fechaSlit[0];
            var valores = "mode=update_data&id_dosis="+idDosis+"&id_form_registro="+idFormRegistro+"&fecha_reporte="+fechaRegistro+"&lote1="+lote1+"&lote2="+lote2+"&lote3="+lote3;
            valores += "&id_un="+idUn+"&id_modalidad="+idModalidad+"&id_zona="+drpZona+"&nombre_funcionario="+nombreFuncionario+"&nombre_registra="+nombreRegistra;
            valores += "&otro_modalidad="+otroModalidad;
            pedirAJAX(urlprefix +"js/dynamic/vacunas/accionesDosisRegistroDiario.php", recibirAcciones, {timeout: 20,
                                                                                        metodo: metodoHTTP.POST,
                                                                                        parametrosPOST: valores,
                                                                                        cartelCargando: "divCargando",
                                                                                        cache: false,
                                                                                        onerror: procesarError});
        }
    }
    else {
        var Message = "<br/>&nbsp; &nbsp;&nbsp; &nbsp;Datos que hacen falta de la Dosis:"+Errores;
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
}

function recibirAcciones(text){
    alert(text);
    traerTablaDosis();
}

// Calcula la edad a partir de la fecha de nacimiento
//function calcularEdadVacuna(){
//    if(jQuery.trim($("#fecha_nacimiento").val())!=""){
//        if(isDate($("#fecha_nacimiento").val())){
//            var edad=0;
//            var fechaActual = new Date();
//            if(compararFechas($("#fecha_nacimiento").val())){
//                var fechaNacimiento = $("#fecha_nacimiento").val().toString().split("/");
//                var diaNac = fechaNacimiento[0];
//                var mesNac = fechaNacimiento[1];
//                var anioNac = fechaNacimiento[2];
//                var anioAct = fechaActual.getFullYear();
//                var mesAct = fechaActual.getMonth()+1;
//                var diaAct = fechaActual.getDate();
//                var fechaForm = $("#fecha_formulario").val();
//                if (fechaForm !== ""){
//                    var fechaAct = fechaForm.toString().split("/");
//                    diaAct = parseInt(fechaAct[0]);
//                    mesAct = parseInt(fechaAct[1])+1;
//                    anioAct = parseInt(fechaAct[2]);
//                }
//                // Calcula años
//                edad = anioAct - anioNac;
//                if(edad!=0){
//                    if (mesNac > mesAct )
//                        edad--;
//                    else if(mesNac == mesAct){
//                        if(diaAct < diaNac)
//                            edad--;
//                    }
//                }
//                // 0 años
//                if(edad>0){
//                    $("#edad_vac").val(edad);
//                    $("#drptipo_edad_vac").val(3);
//                }
//                else{
//                    // Comparar meses
//                    if(mesAct == mesNac){
//                        if(diaAct == diaNac){
//                            // 0 días
//                            $("#edad_vac").val(0);
//                            $("#drptipo_edad_vac").val(1);
//                            $("#fecha_nacimiento").val(fechaActualString());
//                        }
//                        else if(diaNac < diaAct){
//                            // hoy menor que fecha actual días
//                            $("#edad_vac").val(diaAct - diaNac);
//                            $("#drptipo_edad_vac").val(1);
//                        }
//                    }
//                    else if (mesNac <mesAct){
//                        if(diaNac < diaAct || diaNac == diaAct){
//                            $("#edad_vac").val(mesAct - mesNac);
//                            $("#drptipo_edad_vac").val(2);
//                        }
//                        else{
//                            if((mesAct - mesNac - 1)==0){
//                                $("#edad_vac").val(30 - diaNac + diaAct);
//                                $("#drptipo_edad_vac").val(1);
//                            }
//                            else{
//                                $("#edad_vac").val(mesAct - mesNac - 1);
//                                $("#drptipo_edad_vac").val(2);
//                            }
//                        }
//                    }
//                    else if (mesNac > mesAct){
//                        if(diaNac < diaAct || diaNac == diaAct){
//                            $("#edad_vac").val(mesAct + (12 - mesNac));
//                            $("#drptipo_edad_vac").val(2);
//                        }
//                        else{
//                            $("#edad_vac").val(mesAct + (12 - mesNac) - 1);
//                            $("#drptipo_edad_vac").val(2);
//                        }
//                    }
//                }
//                traerTablaDosis();
//            }
//            else{
//                $("#fecha_nacimiento").val(fechaActualString());
//                calcularEdadVacuna();
//            }
//        }
//        else{
//            $("#fecha_nacimiento").val(fechaActualString());
//            calcularEdadVacuna();
//        }
////        $('#drptipo_edad_vac').attr('readonly', true);
////        $('#drptipo_edad_vac').attr('disabled', 'disabled');
////        $('#edad_vac').attr('readonly', true);
////        $('#edad_vac').attr('disabled', 'disabled');
////        $("#drpsexo").focus();
//    }
//}

function calcularEdadVacuna(){
    if(jQuery.trim($("#fecha_nacimiento").val())!=""){
        if(isDate($("#fecha_nacimiento").val())){
            var edad=0;
            var diaAct;
            var mesAct;
            var anioAct;
            var fechaForm;
            
            if(compararFechas($("#fecha_nacimiento").val())){

                if ($("#fecha_formulario").val() !== ""){
                    fechaForm = new Date( $("#fecha_formulario").datepicker("getDate"));
                    anioAct = fechaForm.getFullYear();
                    mesAct = fechaForm.getMonth();
                    diaAct = fechaForm.getDate();;
                }else
                {
                    fechaForm = new Date();
                    anioAct =  fechaForm.getFullYear();
                    mesAct = fechaForm.getMonth();
                    diaAct = fechaForm.getDate();
                }    

                var fecha_nacimiento = new Date($("#fecha_nacimiento").datepicker("getDate"));
                var anioNac = fecha_nacimiento.getFullYear();
                var mesNac = fecha_nacimiento.getMonth();
                var diaNac = fecha_nacimiento.getDate();
                var dias;

                dias = (fechaForm - fecha_nacimiento)/1000/60/60/24;
                if (anioNac == anioAct || dias < 360){

                    if (mesAct == mesNac && dias <=29 ){
//                        dias = (fechaForm - fecha_nacimiento)/1000/60/60/24;
                        $("#edad_vac").val(dias);
                        $("#drptipo_edad_vac").val(1);
                    } else {

                        if (mesAct - mesNac == 1  && diaAct < diaNac){
//                            dias = (fechaForm - fecha_nacimiento)/1000/60/60/24;
                            $("#edad_vac").val(dias);
                            $("#drptipo_edad_vac").val(1);
                        } else {
                            if (diaAct < diaNac) {
                                if (mesAct > mesNac)
                                    {$("#edad_vac").val(mesAct - mesNac - 1);}
                                else
                                    {$("#edad_vac").val((12- mesNac) + mesAct );}
                            }else
                            {
                                if (mesAct > mesNac)
                                    {$("#edad_vac").val(mesAct - mesNac); }
                                else
                                    {$("#edad_vac").val((12 - mesNac) + mesAct);}
                            }
                            $("#drptipo_edad_vac").val(2);
                        }
                    }

                } else
                {
                    if (mesAct < mesNac ){
                            $("#edad_vac").val(anioAct - anioNac - 1);
                            $("#drptipo_edad_vac").val(3);
                    }else
                        {
                            if ( mesAct == mesNac && diaAct < diaNac){
                                if ((anioAct - anioNac - 1) > 0) 
                                    {
                                        $("#edad_vac").val(anioAct - anioNac - 1);
                                        $("#drptipo_edad_vac").val(3);
                                    }
                                else
                                    {
                                        $("#edad_vac").val("11")
                                        $("#drptipo_edad_vac").val(2);
                                    }
                            } else {
                                $("#edad_vac").val(anioAct - anioNac);
                                $("#drptipo_edad_vac").val(3);
                            }
                         
                        }
//                    $("#drptipo_edad_vac").val(3);
                }
                traerTablaDosis();
            }
        }
        else{
            $("#fecha_nacimiento").val(fechaActualString());
            calcularEdadVacuna();
        }
    }
}        
