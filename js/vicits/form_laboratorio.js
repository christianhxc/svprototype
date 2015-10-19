var globalMuestrasRelacionados = new Array();
var tablaMuestras = "tablaMuestras";
var globalPruebasRelacionados = new Array();
var tablaPruebas = "tablaPruebas";
var found = false;

$(function() {
    $( "#fecha_consulta" ).datepicker({
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
    $( "#dialog-form" ).bind("dialogclose",function(){
        borrarTabla()
    });
        
    // Divide en tabs el ingreso de los datos
    
    $("#tabs").tabs({
        selected:0, 
        select:function(event, ui){
            if(ui.index==1)
                $('#next').html("Inicio");
            else
                $('#next').html("Siguiente");
        }
    });
       
    $( "#unidad_notificadora" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#unidad_notificadora").val($("<div>").html(li.selectValue).text());
            $("#id_un").val(li.extra[0]);
        },
        autoFill:false
    });
        
    inicializarTabla(tablaMuestras);
    inicializarTabla(tablaPruebas); 
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

function siguienteTab()
{
    if(getSelectedTabIndex()==0)
    {
        $("#tabs").tabs('select', 1)
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

function clinicaTarv(){
    $("#drpTARV").val() == 1 ? $("#divClinica").css("display", "") : $("#divClinica").css("display", "none");
}

function buscarPersona(){
    var tipoId = $("#drpTipoId").val();
    var numeroId = jQuery.trim($("#no_identificador").val());
    if (tipoId != 0 && numeroId!= ""){
        individuo(tipoId,numeroId);
             
    }
}

function individuo(tipoId,idP)
{
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/vicits/datosPersonaVicits.php',
        data: "tipo_id="+tipoId+"&id="+ idP,
        success: function(data)
        {
            var partes = data.toString().split('#');
           
            if(data.toString().length>0)
            {
                if (partes[0] != "no"){
                    $("#primer_nombre").val(replace(partes[0]));
                    $("#segundo_nombre").val(replace(partes[1]));
                    $("#primer_apellido").val(replace(partes[2]));
                    $("#segundo_apellido").val(replace(partes[3]));
                }
                else{
                    alert("Con los datos ingresados de Tipo y numero de indentificacion, \n\
no se encontro ningun formulario de VICITIS llenado,\n\
Por tanto no se podra guardar este formulario.\n\
\n\
Por favor verifique que los datos son los correctos, si es asi entonces,\n\
debe llenar primero el formulario de VICITS para poder continuar... \n\
\n\
GRACIAS");
                    $("#drpTipoId").val(0);
                    $("#no_identificador").val("");
                    $("#primer_nombre").val("");
                    $("#segundo_nombre").val("");
                    $("#primer_apellido").val("");
                    $("#segundo_apellido").val("");
                }  
            }
        }
    });
}

function validarFormLaboratorio(){
    var Message = '';
    var ErroresF = '';
    var ErrorFormulario ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;Individuo:';
        
    //Formulario
    if($("#drpTipoConsulta").val()==0)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de consulta."; 
    if($("#fecha_consulta").val()=="")
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la fecha de consulta.";
    if($("#drpTipoId").val()==0)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de identificador."; 
    if(jQuery.trim($("#no_identificador").val())=="")
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
    if($("#id_un").val()=="")
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una instalaci\xf3n de salud.";
    if($("#drpPrePrueba").val()==0)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar si recibio consejeria pre prueba."; 
    if($("#drpPoblacion").val()==0)
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el grupo de poblaci\xf3n al que pertence."; 
    if(jQuery.trim($("#nombre_medico").val())=="")
        ErroresF+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el nombre del medico o enfermera que lleno el formulario.";
    
        
    ErrorFormulario = (ErroresF=="")? "": ErrorFormulario+ErroresF + "<br/>";
    Message = ErrorFormulario;
    
    //test
    //    Message = "";
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
           
            var param = '';
            var i=0;
            for(i=0; i<globalMuestrasRelacionados.length;i++){
                if(__isset(globalMuestrasRelacionados[i])){
                    param+=globalMuestrasRelacionados[i][0];//enfermedad Oportunista
                }
            }
            $('#globalMuestrasRelacionadas').val(param);

            var param2 = '';

            for(i=0; i<globalPruebasRelacionados.length;i++){
                if(__isset(globalPruebasRelacionados[i])){
                    param2+=globalPruebasRelacionados[i][0];//enfermedad Oportunista
                }
            }
            $('#globalPruebasRelacionadas').val(param2);
            
            var nuevo = '';
            if($('#action').val()=='M'){
                nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de VICITS de Laboratorio, \xbfdesea continuar?';
            }
            else
                nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de VICITS de Laboratorio, \xbfdesea continuar?';
            if(confirm(nuevo)){
                $("#dSummaryErrors").css('display','none');
                $('#frmContenido').submit();
            }
        }
    }
}

function relacionarTabla(tabla){
    var tmpReg = 0;
    var flag = true;
    var i=0;
    if(tabla == tablaMuestras){
        var idMuestra = $("#drpTipoMuestra").val();
        var nombreMuestra = $("#drpTipoMuestra").find(":selected").text();
    
        if (idMuestra !="" && idMuestra != 0){
            $("#drpTipoMuestra").val(0);
            tmpReg = globalMuestrasRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idMuestra == globalMuestrasRelacionados[i][0] || "###"+idMuestra == globalMuestrasRelacionados[i][0]))
                    flag = false;
            }
            if (flag){
                idMuestra = (tmpReg==0) ? idMuestra : "###"+idMuestra;
                globalMuestrasRelacionados[tmpReg] = new Array(idMuestra,nombreMuestra);
                crearTabla(tablaMuestras);
            }
            else
                alert ("Ya existe un registro para esta relacion");
        }
        else
            alert("Debe seleccionar una muestra");
    }
    else if(tabla == tablaPruebas){
        var idPrueba = $("#drpPrueba").val();
        var nombrePrueba = $("#drpPrueba").find(":selected").text();
    
        if (idPrueba !="" ){
            $("#drpPrueba").val(0);
            tmpReg = globalPruebasRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if ((idPrueba == globalPruebasRelacionados[i][0] || "###"+idPrueba == globalPruebasRelacionados[i][0]))
                    flag = false;
            }
            if (flag){
                idPrueba = (tmpReg==0) ? idPrueba : "###"+idPrueba;
                globalPruebasRelacionados[tmpReg] = new Array(idPrueba,nombrePrueba);
                crearTabla(tablaPruebas);
            }
            else
                alert ("Ya existe un registro para esta relacion");
        }
        else
            alert("Debe seleccionar una prueba");
    }
}

function crearTabla(tabla){
    var i=0;
    var html = '';
    if(tabla==tablaMuestras){
        if(globalMuestrasRelacionados.length == 0)
            $("#"+tablaMuestras).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Tipo Muestra</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalMuestrasRelacionados.length;i++){
                if(__isset(globalMuestrasRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="180px">'+globalMuestrasRelacionados[i][1]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaMuestras,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaMuestras).html(html);
        }
    }
    else if(tabla==tablaPruebas){
        if(globalPruebasRelacionados.length == 0)
            $("#"+tablaPruebas).html("");
        else{
            html = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
            '<tr>'+
            '<th class="dxgvHeader_PlasticBlue">Prueba solicitada</th>'+
            '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
            '<tr>';
            for(i=0; i<globalPruebasRelacionados.length;i++){
                if(__isset(globalPruebasRelacionados[i])){
                    html += '<tr>'+
                    '<td class="fila" width="180px">'+globalPruebasRelacionados[i][1]+'</th>'+
                    '<td class="fila" width="40px" align="center"><a href="javascript:borrarRelacionTabla(tablaPruebas,'+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
                    '<tr>';
                }
            }
            html += "</table>";
            $("#"+tablaPruebas).html(html);
        }
    }
}

function borrarRelacionTabla(tabla, pos){
    if(tabla == tablaMuestras){
        if (confirm("Esta seguro de eliminar la relacion\n con el tipo de muestra "+globalMuestrasRelacionados[pos][1])){
            globalMuestrasRelacionados.splice(pos, 1);
            crearTabla(tablaMuestras);
        }
    }
    else if(tabla == tablaPruebas){
        if (confirm("Esta seguro de eliminar la relacion\n con la prueba solicitada "+globalPruebasRelacionados[pos][1])){
            globalPruebasRelacionados.splice(pos, 1);
            crearTabla(tablaPruebas);
        }
    }
}

function inicializarTabla(tabla){
    var i=0;
    if(tabla == tablaMuestras){
        var muestras = $( "#globalMuestrasRelacionadas" ).val().split("###");
        for(i=0; i<muestras.length;i++){
            var muestra = muestras[i].split("#-#");
            agregarRegistroTabla(tablaMuestras, muestra);
        }
        crearTabla(tablaMuestras);
    }
    else if(tabla == tablaPruebas){
        var pruebas = $( "#globalPruebasRelacionadas" ).val().split("###");
        for(i=0; i<pruebas.length;i++){
            var prueba = pruebas[i].split("#-#");
            agregarRegistroTabla(tablaPruebas, prueba);
        }
        crearTabla(tablaPruebas);
    }
}

function agregarRegistroTabla(tabla, registro){
    var tmpReg = 0;
    var flag = true;
    var i=0;
    if(tabla == tablaMuestras){
        var idMuestra = registro[0];
        var nombreMuestra = registro[1];
        if (idMuestra !="" && idMuestra != 0){
        
            tmpReg = globalMuestrasRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if (idMuestra == globalMuestrasRelacionados[i][0] || "###"+idMuestra == globalMuestrasRelacionados[i][0])
                    flag = false;
            }
            if (flag){
                idMuestra = (tmpReg==0) ? idMuestra : "###"+idMuestra;
                globalMuestrasRelacionados[tmpReg] = new Array(idMuestra,nombreMuestra);
            }
        }       
    }
    else if(tabla == tablaPruebas){
        var idPrueba = registro[0];
        var nombrePrueba = registro[1];
        if (idPrueba !="" && idPrueba != 0){
            tmpReg = globalPruebasRelacionados.length;
            flag = true;
            for (i=0; i<tmpReg; i++){
                if (idPrueba == globalPruebasRelacionados[i][0] || "###"+idPrueba == globalPruebasRelacionados[i][0])
                    flag = false;
            }
            if (flag){
                idPrueba = (tmpReg==0) ? idPrueba : "###"+idPrueba;
                globalPruebasRelacionados[tmpReg] = new Array(idPrueba,nombrePrueba);
            }
        }       
    }
}

function destruirTabla(tabla){
    if(tabla == tablaPruebas){
        globalMuestrasRelacionados = [];
        crearTabla(tablaPruebas);
    }
    else if(tabla == tablaPruebas){
        globalPruebasRelacionados = [];
        crearTabla(tablaPruebas);
    }
}
