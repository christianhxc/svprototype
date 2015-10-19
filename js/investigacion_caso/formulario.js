var globalSintomasRelacionados = new Array();
var globalTratamientosRelacionados = new Array();

$(document).ready(function() {
    // Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({
            selected:0, 
            select:function(event, ui){
                //alert(ui.index);
                if(ui.index==2)
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
    
    $( "#institucion" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#institucion").val($("<div>").html(li.selectValue).text());
            $("#id_institucion").val(li.extra[0]);
            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
    $( "#evento_inicial1" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento_inicial1").val(li.selectValue);
            $("#id_evento_inicial1").val(li.extra[0]);
        },
        autoFill:true
    });
    $( "#evento_inicial2" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento_inicial2").val(li.selectValue);
            $("#id_evento_inicial2").val(li.extra[0]);
        },
        autoFill:true
    }); 
    $( "#evento_final1" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento_final1").val(li.selectValue);
            $("#id_evento_final1").val(li.extra[0]);
        },
        autoFill:true
    });
    $( "#evento_final2" ).autocomplete(urlprefix + "js/dynamic/eventos.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#evento_final2").val(li.selectValue);
            $("#id_evento_final2").val(li.extra[0]);
        },
        autoFill:true
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
   alert(idP);
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
               $("#tipoId").text(replace(partes[16]));
               $("#no_identificador").text(replace(partes[1]));
               $("#ocupacion").text(replace(partes[17]));
               
               $("#nombres").text(replace(partes[2])+" "+replace(partes[3]));
               $("#apellidos").text(replace(partes[4])+" "+replace(partes[5]));
               
               $("#fecha_nacimiento").val((partes[6]==''?'':invFecha(1,partes[6])));
               $("#drptipo_edad").val(partes[7]);
               $("#edad").val(partes[8]);
               $("#drpsexo").val(partes[9]);
               
               $("#nombre_responsable").val(partes[10]);

               idProvincia = partes[11];
               idRegion = partes[12];
               idDistrito = partes[13];
               idCorregimiento = partes[14];

               $("#idPro").val(idProvincia);
               $("#idReg").val(idRegion);
               $("#idDis").val(idDistrito);
               $("#idCor").val(idCorregimiento);

               $("#drpProIndividuo").val(idProvincia);
               setRegionPersona(idProvincia, idRegion)
               
//               munis(dep, mun);
//               zonas(dep, mun, lp);
               
               if (partes[16]!=" - "){
                   $("#direccion_individuo").val(partes[15]);
                   $('#no_direccion_individuo').attr('checked', false);
               }
               else 
                   $('#no_direccion_individuo').attr('checked', true);
               
               $("#resultadosBusqueda").html('');
               $("#dialog-form").dialog('close');
               found = true;
           }
           else
               found = false;
       }
     });    
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

//Datepicker Formulario de investigacion de casos
$(function() {
    $( "#fecha_humanas" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tabla_acciones" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_factor" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_resultados" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tabla_tratamiento" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_inicio_sintomas" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_tabla_sintomas" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_atencion" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_hospitalizacion" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_traslado" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_egreso" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_defuncion" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_atencion_previa" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_notificacion" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_autopsia" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_notifica" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_diag_final1" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
$(function() {
    $( "#fecha_diag_final2" ).datepicker({
        showOn: "button",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

//Relacionar Sintomas
function relacionarSintomas(){
    var fecha = $("#fecha_tabla_sintomas").val();
    var idSintoma = $("#drpSintomas").val();
    var nombreSintoma = $("#drpSintomas").find(":selected").text();
    
    if (fecha !="" && idSintoma != 0){
        //        alert("Fecha "+fecha + " Sintoma: "+idSintoma + " - "+nombreSintoma);
        //        return;
        var tmpReg = globalSintomasRelacionados.length;
        //        var combo = $('sintoma');
        //        var txtCombo = combo.options[combo.selectedIndex].text;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (idSintoma == globalSintomasRelacionados[i][0] || "###"+idSintoma == globalSintomasRelacionados[i][0])
                flag = false;
        }
        if (flag){
            idSintoma = (tmpReg==0) ? idSintoma : "###"+idSintoma;
            globalSintomasRelacionados[tmpReg] = new Array(idSintoma,nombreSintoma,fecha);
            crearTablaSintomas();
        }
        else
            alert ("Ya existe un registro para ese sintoma");
    }
    else
        alert("Debe seleccionar el sintoma e ingresar una fecha");
}

function crearTablaSintomas(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Sintoma</th>'+
    '<th class="dxgvHeader_PlasticBlue">Fecha</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalSintomasRelacionados.length;i++){
        if(__isset(globalSintomasRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalSintomasRelacionados[i][1]+'</th>'+
            '<td class="fila" width="60px">'+globalSintomasRelacionados[i][2]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelSintoma('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#tablaSignosSintomas").html(tabla);
}

function eliminarRelSintoma(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon el sintoma "+globalSintomasRelacionados[pos][1])){
        globalSintomasRelacionados.splice(pos, 1);
        crearTablaSintomas();
    }
}

//Relacionar Tratamientos
function relacionarTratamientos(){
    var fecha = $("#fecha_tabla_tratamiento").val();
    var tratamiento = $("#tratamiento").val();
    
    if (fecha !="" && tratamiento != ""){
        var tmpReg = globalTratamientosRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (tratamiento == globalTratamientosRelacionados[i][0])
                flag = false;
        }
        if (flag){
            globalTratamientosRelacionados[tmpReg] = new Array(tratamiento,fecha);
            crearTablaTratamiento();
        }
        else
            alert ("Ya existe un registro con el mismo tratamiento");
    }
    else
        alert("Debe agregar texto al tratamiento e ingresar una fecha");
}

function crearTablaTratamiento(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="100%">'+
    '<tr>'+
    '<th class="dxgvHeader_PlasticBlue">Tratamiento</th>'+
    '<th class="dxgvHeader_PlasticBlue">Fecha</th>'+
    '<th class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '<tr>';
    for(var i=0; i<globalTratamientosRelacionados.length;i++){
        if(__isset(globalTratamientosRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalTratamientosRelacionados[i][0]+'</th>'+
            '<td class="fila" width="60px">'+globalTratamientosRelacionados[i][1]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarRelTratamiento('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#tablaTratamiento").html(tabla);
}

function eliminarRelTratamiento(pos){
    if (confirm("Esta seguro de eliminar la relacion\ncon el tratamiento "+globalTratamientosRelacionados[pos][0])){
        globalTratamientosRelacionados.splice(pos, 1);
        crearTablaTratamiento();
    }
}

function autopsia(){
    autopsiaSi = $('#autopsiaSi').is(':checked');
    //autopsiaNo = $('#autopsiaNo').is(':checked');
    if(autopsiaSi)
        autopsiaOn();
    else 
        autopsiaOff();
}


function autopsiaOn(){
    $( "#label_fecha_autopsia" ).css( "display", "" );
    $( "#display_fecha_autopsia" ).css( "display", "" );
    $( "#drpMuestraTomada" ).css( "display", "" );
    $( "#label_muestra_tomada" ).css( "display", "" );
}

function autopsiaOff(){
    $( "#label_fecha_autopsia" ).css( "display", "none" );
    $( "#display_fecha_autopsia" ).css( "display", "none" );
    $( "#drpMuestraTomada" ).css( "display", "none" );
    $( "#label_muestra_tomada" ).css( "display", "none" );
}

function otraInstitucion(){
    noDisponible = $('#institucion_no_disponible').is(':checked');
    if(noDisponible){
        $('#institucion').attr('readonly', true);
        $('#institucion').attr('disabled', 'disabled');
        $("#label_region1" ).css("display", "none");
        $("#label_valor_region1" ).css("display", "none");
        
        $("#label_region2" ).css("display", "");
        $("#label_otra_institucion" ).css("display", "");
        $("#drpOtraRegion" ).css("display", "");
        $("#otra_institucion" ).css("display", "");
    }
    else{
        $('#institucion').attr('readonly', false);
        $('#institucion').attr('disabled', '');
        $("#label_region1" ).css("display", "");
        $("#label_valor_region1" ).css("display", "");
        
        $("#label_region2" ).css("display", "none");
        $("#label_otra_institucion" ).css("display", "none");
        $("#drpOtraRegion" ).css("display", "none");
        $("#otra_institucion" ).css("display", "none");
    }
}

function verificar(){
    institucion = $('#institucion').val();
    if(institucion==""){
        $("#label_valor_region1").html("");
    }
}