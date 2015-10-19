var pagina = 1;
var globalGruposRelacionados = new Array();

$(document).ready(function() {
    
//    individuo($("#drpTipoId").val(), $("#no_identificador").val());
//    sexoEmbarazo();
//    llenarFactoresRiesgo();
//    llenarEnfOportunistas();
//    iniciaDatosComportamiento();
//    iniciarDatosCondicionPaciente();
    
    
    // Divide en tabs el ingreso de los datos
    
    $(function() {
        
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                $( "#divErrorEscenario" ).hide();
                $( "#divErrorContacto" ).hide();
                $( "#divErrorGrupo" ).hide();
                $( "#divEscenario" ).hide();
                $( "#divContacto" ).hide();
                $( "#divErrorGeneral" ).hide();
                $( "#divInfoGeneral" ).hide();
                $( "#divListEscenario" ).show();
                $( "#divListContacto" ).show();
                if(ui.index==4)
                    $('#next').html("Inicio");
                else
                    $('#next').html("Siguiente");
            }
        });
    });
    
    $( "#evento" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento").val(li.selectValue);
            $("#eveNombre").val(li.selectValue);
            $("#eveId").val(li.extra[0]);
        },
        autoFill:false
    });
    
    inicializarHtml();
    
    
});

function inicializarHtml(){
    $( "#divEscenario" ).hide();
    $( "#divContacto" ).hide();
    $( "#divInfoEscenario" ).hide();
    $( "#divErrorEscenario" ).hide();
    $( "#divInfoContacto" ).hide();
    $( "#divErrorContacto" ).hide();
    $( "#divInfoGrupo" ).hide();
    $( "#divErrorGrupo" ).hide();
    
    $( "#divPolitica1" ).hide();
    $( "#divPolitica2" ).hide();
    $( "#divPolitica3" ).hide();
    $( "#divPolitica4" ).hide();
    $( "#divPolitica5" ).hide();
    $( "#divPolitica6" ).hide();
    $( "#divPolitica7" ).hide();
    $( "#divPolitica8" ).hide();
    $( "#divTiempo1" ).hide();
    $( "#divTiempo2" ).hide();
    busquedaEscenario();
    busquedaContacto();
}

function nuevoEscenario(){
    $( "#divEscenario" ).show();
    $( "#divListEscenario" ).hide();
    $('#actionEscenario').val("N");
    globalGruposRelacionados = new Array();
    $("#divRelGrupos").html("");
    $("#btnPrincipal").html("Guardar");
}

function cancelarEscenario(){
    $( "#divEscenario" ).hide();
    $( "#divListEscenario" ).show();
    $("#evento").val('');
    $("#eveNombre").val('');
    $("#eveId").val('');
    $("#drpNivelGeo").val(0);
    $("#drpProvincia").val(0);
    $("#drpRegion").val(0);
    $("#drpDistrito").val(0);
    $("#drpCorregimiento").val(0);
    $("#drpAlgoritmo").val(0);
    $("#drpDiaCorte").val(0);
    $("#drpGrupoContacto").val(0);
    $("#parteEmail").val('');
    $("#check_tiempo_inm").attr('checked',false);
    $("#check_tiempo_sem").attr('checked',false);
    $("#divErrorEscenario" ).hide();
    globalGruposRelacionados = new Array();
    $("#divRelGrupos").html("");
}

function busquedaContacto(){
    pagina = 1;
    buscarContacto();
}

function buscarContacto()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/mat/busquedaContacto.php',
        data: "filtro="+jQuery.trim($("#filtroContacto").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resListContacto").html(data);
            if (data!=null&&data!='')
                $( "#resListContacto" ).show();
        }
    });
}

function busquedaEscenario(){
    pagina = 1;
    buscarEscenario();
}

function buscarEscenario()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/mat/busquedaEscenario.php',
        data: "filtro="+jQuery.trim($("#filtroEscenario").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resListEscenario").html(data);
            if (data!=null&&data!='')
                $( "#resListEscenario" ).show();
        }
    });
}

function nivelGeo(){
    var nivel = parseInt($("#drpNivelGeo").val());
    switch (nivel){
        case 2:
            ocultarDivPolitica();
            $( "#divPolitica1" ).show();
            $( "#divPolitica2" ).show();
        break;
        case 3:
            ocultarDivPolitica();
            $( "#divPolitica1" ).show();
            $( "#divPolitica2" ).show();
            $( "#divPolitica3" ).show();
            $( "#divPolitica4" ).show();
        break;
        case 4:
            ocultarDivPolitica();
            $( "#divPolitica1" ).show();
            $( "#divPolitica2" ).show();
            $( "#divPolitica3" ).show();
            $( "#divPolitica4" ).show();
            $( "#divPolitica5" ).show();
            $( "#divPolitica6" ).show();
        break;
        case 5:
            $( "#divPolitica1" ).show();
            $( "#divPolitica2" ).show();
            $( "#divPolitica3" ).show();
            $( "#divPolitica4" ).show();
            $( "#divPolitica5" ).show();
            $( "#divPolitica6" ).show();
            $( "#divPolitica7" ).show();
            $( "#divPolitica8" ).show();
        break;
        default:
            ocultarDivPolitica();
        break;
    }
}

function ocultarDivPolitica(){
    $( "#divPolitica1" ).hide();
    $( "#divPolitica2" ).hide();
    $( "#divPolitica3" ).hide();
    $( "#divPolitica4" ).hide();
    $( "#divPolitica5" ).hide();
    $( "#divPolitica6" ).hide();
    $( "#divPolitica7" ).hide();
    $( "#divPolitica8" ).hide();
    $("#drpProvincia").val(0);
    $("#drpRegion").val(0);
    $("#drpDistrito").val(0);
    $("#drpCorregimiento").val(0);
}

function diaSemana(){
    if ( $('#check_tiempo_sem').is(':checked') ){
        $( "#divTiempo1" ).show();
        $( "#divTiempo2" ).show();        
    }
    else{
        $( "#divTiempo1" ).hide();
        $( "#divTiempo2" ).hide();  
        $("#drpDiaCorte").val(0);
    }
}

function setRegionCascada(){
    setRegionPersona($("#drpProvincia").val(),-1);
}

function setRegionPersona(idProvincia, idRegion)
{
    $.getJSON(urlprefix + 'js/dynamic/mat/regiones.php',{
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

        $("#drpRegion").html(options);
    })
}

function setDistritoCascada(){
    setDistritoPersona($("#drpProvincia").val(),$("#drpRegion").val(),-1);
}

function setDistritoPersona(idProvincia, idRegion, idDistrito)
{
    $.getJSON(urlprefix + 'js/dynamic/mat/distritos.php',{
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

        $("#drpDistrito").html(options);
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDistrito").val(),-1);
}

function setCorregimientoPersona(idDistrito, idCorregimiento)
{
    $.getJSON(urlprefix + 'js/dynamic/mat/corregimientos.php',{
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

        $("#drpCorregimiento").html(options);
    })
}


function validarEscenario(){
    var Message = '';
    var Errores = '';
    var Error ='&nbsp; &nbsp;&nbsp; &nbsp;Escenario:';
    
    //Escenario
    if ($("#eveId").val()=="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el evento que quiere monitorear.";
    if($("#drpAlgoritmo").val()==0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el algoritmo.";
    var nivelGeo = parseInt($("#drpNivelGeo").val());
    if(nivelGeo==0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el nivel geogr&aacute;fico.";
    else if (nivelGeo>1){
        if (nivelGeo==2){
            if($("#drpProvincia").val()==0)
                Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la provincia.";
        }
        else if (nivelGeo==3){
            if($("#drpRegion").val()==0)
                Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la regi&oacute;n.";
        }
        else if (nivelGeo==4){
            if($("#drpDistrito").val()==0)
                Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito.";
        }
        else{
            if($("#drpCorregimiento").val()==0)
                Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el corregimiento.";
        }
    }
         
    var inmediato = $('#check_tiempo_inm').is(':checked');
    var semanal = $('#check_tiempo_sem').is(':checked');
    if(!(inmediato || semanal) ){
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si la alerta se lanza inmediato o semanal."; 
    }
    else if (semanal){
        if($("#drpDiaCorte").val()==0)
            Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el d&iacute;a de la semana en que se debe enviar la alerta.";    
    }
    if(globalGruposRelacionados.length ==0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar al menos un grupo de contactos al que se le notificar&aacute; que la alerta se ha activado.";
    
    Error = (Errores=="")? "": Error+Errores+"<br/>";
    Message = Error;
    
    if(Message!=""){
        $('#divErrorEscenario').show();
        $('#labelErrorEscenario').html(Message);
    }
    else
    {
        var param2 = '';
        for(var i=0; i<globalGruposRelacionados.length;i++){
            if(__isset(globalGruposRelacionados[i])){
                param2+=globalGruposRelacionados[i][0];//enfermedad Oportunista
            }
        }
        $('#globalGruposRelacionados').val(param2);
        $('#nombreCrear').attr('readonly', false);
        $('#nombreCrear').attr('disabled', '');
        $('#fechaCrear').attr('readonly', false);
        $('#fechaCrear').attr('disabled', '');
        $('#divErrorEscenario').hide();
        $('#labelErrorEscenario').html("");
        var nuevo = '';
        if($('#actionEscenario').val()=='ME'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Escenario actual, \xbfdesea continuar?';
        }
        else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Escenario, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $('#frmEscenario').submit();
        }
    }
    
}

function editarEscenario(idEscenario){
    $("#divListEscenario").hide();
    $("#divEscenario").show();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/mat/datosEscenario.php',
        data: "idEscenario="+idEscenario,
        success: function(data)
        {
            var dataArray = data.split("#$#");
            $("#id_escenario").val(dataArray[0]);
            $("#eveId").val(dataArray[1]);
            $("#eveNombre").val(dataArray[2]);
            $("#evento").val(dataArray[2]);
            $("#drpAlgoritmo").val(dataArray[3]);
            $("#drpNivelGeo").val(dataArray[4]);
            $("#check_tiempo_inm").attr('checked',false);
            $("#check_tiempo_sem").attr('checked',false);
            (dataArray[5] == 1)? $("#check_tiempo_inm").attr('checked',true): $("#check_tiempo_sem").attr('checked',true);
            $("#drpDiaCorte").val(dataArray[6]);
            $( "#divTiempo1" ).hide();
            $( "#divTiempo2" ).hide();
            if (dataArray[5] == 2){
                $( "#divTiempo1" ).show();
                $( "#divTiempo2" ).show();
            }
            $("#drpGrupoContacto").val(dataArray[7]);
            $("#parteEmail").val(dataArray[8]);
            $("#nombreCrear").val(dataArray[9]);
            $("#fechaCrear").val(dataArray[10]);
            $("#globalGruposRelacionados").val(dataArray[11]);
            if(dataArray[4]>1){
                ocultarDivPolitica();
                if (__isset(dataArray[12])){
                    $("#drpProvincia").val(dataArray[12]);
                    $( "#divPolitica1" ).show();
                    $( "#divPolitica2" ).show();
                }
                if (__isset(dataArray[13])){
                    setRegionPersona(dataArray[12], dataArray[13]);
                    $( "#divPolitica3" ).show();
                    $( "#divPolitica4" ).show();
                }
                if (__isset(dataArray[14])){
                    setDistritoPersona(dataArray[12], dataArray[13],dataArray[14])
                    $( "#divPolitica5" ).show();
                    $( "#divPolitica6" ).show();
                }
                if (__isset(dataArray[15])){
                    setCorregimientoPersona(dataArray[14], dataArray[15])
                    $( "#divPolitica7" ).show();
                    $( "#divPolitica8" ).show();
                }
            }
            else
                ocultarDivPolitica();
            $('#actionEscenario').val("ME");
            $("#btnPrincipal").html("Actualizar");
            
            llenarGrupos();
        }
    });
}

function eliminarEscenario (idEscenario){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idEscenario+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?actionEscenario=EE&id=' + idEscenario );
    }
}

function nuevoContacto(){
    $("#divContacto" ).show();
    $("#divListContacto" ).hide();
    $('#actionContacto').val("NC");
}

function cancelarContacto(){
    $("#divContacto" ).hide();
    $("#divListContacto" ).show();
    $("#id_contacto").val('');
    $("#contNombres").val('');
    $("#contApellidos").val('');
    $("#contEmail").val('');
    $("#contTelefono").val('');
    $("#check_status_act").attr('checked',false);
    $("#check_status_ina").attr('checked',false);
    $("#divErrorContacto" ).hide();
}

function validarContacto(){
    var Message = '';
    var Errores = '';
    var Error ='&nbsp; &nbsp;&nbsp; &nbsp;Contacto:';
    
    //Escenario
    if ($("#contNombres").val()=="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del contacto.";
    if ($("#contApellidos").val()=="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el apellido del contacto.";
    if ($("#contEmail").val()=="")
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el correo del contacto.";
         
    var activo = $('#check_status_act').is(':checked');
    var inactivo = $('#check_status_ina').is(':checked');
    if(!(activo || inactivo) ){
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si el contacto esta activo o inactivo."; 
    }
        
    Error = (Errores=="")? "": Error+Errores+"<br/>";
    Message = Error;
    
    if(Message!=""){
        $('#divErrorContacto').show();
        $('#labelErrorContacto').html(Message);
    }
    else
    {
        $('#divErrorContacto').hide();
        $('#labelErrorContacto').html("");
        var nuevo = '';
        if($('#actionContacto').val()=='MC'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Contacto actual, \xbfdesea continuar?';
        }
        else{
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Contacto, \xbfdesea continuar?';
            $('#actionContacto').val('NC');
        }
        if(confirm(nuevo)){
            $('#frmContacto').submit();
        }
    }
}

function eliminarContacto (idContacto){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Contacto N. '+idContacto+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?actionContacto=EC&id=' + idContacto );
    }
}

function editarContacto(idContacto){
    $("#divListContacto").hide();
    $("#divContacto").show();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/mat/datosContacto.php',
        data: "idContacto="+idContacto,
        success: function(data)
        {
            var dataArray = data.split("#$#");
            $("#id_contacto").val(dataArray[0]);
            $("#contNombres").val(dataArray[1]);
            $("#contApellidos").val(dataArray[2]);
            $("#contEmail").val(dataArray[3]);
            $("#contTelefono").val(dataArray[4]);
            $("#check_status_act").attr('checked',false);
            $("#check_status_ina").attr('checked',false);
            (dataArray[5] == 1)? $("#check_status_act").attr('checked',true): $("#check_status_ina").attr('checked',true);
            
            $('#actionContacto').val("MC");
            $("#btnContacto").html("Actualizar");
        }
    });
}

function relacionarGrupo(){
    var idGrupo = $("#drpGrupoContacto").val();
    var nombreGrupo = $("#drpGrupoContacto").find(":selected").text();
       
    if (idGrupo !="" && idGrupo != 0){
        
        var tmpReg = globalGruposRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if ((idGrupo == globalGruposRelacionados[i][0] || "###"+idGrupo == globalGruposRelacionados[i][0]))
                flag = false;
        }
        if (flag){
            idGrupo = (tmpReg==0) ? idGrupo : "###"+idGrupo;
            globalGruposRelacionados[tmpReg] = new Array(idGrupo,nombreGrupo);
            crearTablaGrupos();
        }
        else
            alert ("Ya existe un registro para esta relacion");
    }
    else
        alert("Debe seleccionar una grupo de contactos");
}

function crearTablaGrupos(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="70%" align="center">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Grupo Contacto</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalGruposRelacionados.length;i++){
        if(__isset(globalGruposRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalGruposRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelGrupo('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divRelGrupos").html(tabla);
}

function eliminarRelGrupo(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon con del grupo de contacto "+globalGruposRelacionados[pos][1])){
        globalGruposRelacionados.splice(pos, 1);
        crearTablaGrupos();
    }
}

function llenarGrupos(){
    var grupos = $( "#globalGruposRelacionados" ).val().split("###");
    for(var i=0; i<grupos.length;i++){
        var grupo = grupos[i].split("-");
        llenarGrupo(grupo);
    }
    crearTablaGrupos();
}

function llenarGrupo(grupo){
    var grupoId = grupo[0];
    var grupoNombre = grupo[1];
    if (grupoId !="" && grupoId != 0){
        var tmpReg = globalGruposRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (grupoId == globalGruposRelacionados[i][0] || "###"+grupoId == globalGruposRelacionados[i][0])
                flag = false;
        }
        if (flag){
            grupoId = (tmpReg==0) ? grupoId : "###"+grupoId;
            globalGruposRelacionados[tmpReg] = new Array(grupoId,grupoNombre);
        }
    }
}

function validarCorreo(){
    if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#contEmail').val())){
        return true;
    }
    else{
        alert('El email debe responder a la siguiente sintaxis correo@ejemplo.com');
        $('#contEmail').focus();
        return false;
    }
}

