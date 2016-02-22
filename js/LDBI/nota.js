
var existencias = new Array();
var presionado = false;

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

function anadir_existencia(){
    var existencia = $('#insert_existencia').find("input").serializeArray();
    var data = transformarJsonEstandar(existencia);

    var flag = true;
    for (var i=0; i < existencias.length; i++){
        if (data.id_prod == existencias[i].id_prod)
            flag = false;
    }

    if (flag){
        agregarRegistro(data);
    } else
        alert ("Ya existe un registro para este producto");
}

function agregarRegistro(data){
    if (esTextoVacio($("#nombre_prod").val()) || esTextoVacio($("#id_prod").val())){
        alert("Debe ingresar el nombre o codigo de un producto valido.")
        return false;
    }

    if (esTextoVacio($("#cantidad").val())){
        alert("Debe ingresar la cantidad del producto.")
        return false;
    }

    if (esTextoVacio($("#no_lote").val())){
        alert("Debe ingresar el numero del lote.")
        return false;
    }

    if (!esNumero($("#cantidad").val())){
        alert("Debe ingresar una cantidad valida.")
        return false;
    }

    if (!data.cantidad) data.cantidad = 'N/D';
    agregarFila(data);

    $("#nombre_prod").val("");
    $("#id_prod").val("");
    $("#cantidad").val("");
}

function agregarFila(data){
    if (data.cantidad == 0) data.cantidad = 'N/D';
    var tpl = '<tr id="detalle_'+data.id_prod+'">'+
        '<td class="fila" width="54">'+
        data.codigo_insumo+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][cantidad]" value="'+data.cantidad+'">'+
        '</td>'+
        '<td class="fila" width="295"><strong>'+data.nombre_prod+'</strong></td>'+
        '<td class="fila" style="text-align:center" width="144">'+data.unidad_presentacion+'</td>'+
        '<td class="fila" style="text-align:center" width="66">'+data.cantidad+'</td>'+
        '<td class="fila" style="text-align:center" width="79"><input id="no_lote_'+data.id_prod+'" style="width: 95%" type="text" name="data[existencias]['+data.id_prod+'][no_lote]" value="'+data.no_lote+'"></td>'+
        '<td class="fila" align="center">';
        tpl += '    <a href="javascript:eliminarRegistro(' + data.id_prod + ')">' +
        '        <img src="/sisvig2/img/Delete.png" title="Eliminar" border="0">' +
        '    </a>';
        tpl += '</td>'+
        '</tr>';

    $("#desc_envio tbody").append(tpl);
    existencias.push(data);
    toggleEnvioDetalle();
}

function eliminarRegistro(id){
    var posicion = -1;
    for(var i=0;i<existencias.length;i++){
        if (existencias[i].id_prod == id){
            posicion = i;
            break;
        }
    }

    if (posicion < 0) return;
    if (confirm("Esta seguro de eliminar el registro " + existencias[posicion].nombre_prod)){
        existencias.splice(posicion, 1);
        $("#detalle_" + id).remove();
        toggleEnvioDetalle();
    }

}

$(document).ready(function() {

    $( "#nombre_prod" ).autocomplete(urlprefix + "js/dynamic/LDBI/insumos.php", {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#nombre_prod").val($("<div>").html(li.selectValue).text());
            $("#id_prod").val(li.extra[0]);
            $("#unidad_presentacion").val(li.extra[1]);
            $("#codigo_insumo").val(li.extra[2]);
        },
        autoFill:false
    });

    toggleEnvioDetalle();

    var json = $("#existencias_json").val();
    if (json) {
        var existenciasActuales = JSON.parse(json);
        if (existenciasActuales) {
            for (var i = 0; i < existenciasActuales.length; i++) {
                agregarFila(existenciasActuales[i]);
            }
        }
    }

    $(".fecha_lista").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });

    $("#notificacion_unidad_origen").autocomplete(urlprefix + "js/dynamic/unidadNotificadoraRegion.php", {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        useCache:false,
        extraParams: { r: function() { return $("#id_region_origen").val(); } },
        onItemSelect:function(li){
            $("#notificacion_unidad_origen").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un_origen").val(li.extra[0]);
        },
        autoFill:false
    });

    $("#notificacion_unidad_destino").autocomplete(urlprefix + "js/dynamic/unidadNotificadoraRegion.php", {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        useCache:false,
        extraParams: { r: function() { return $("#id_region_destino").val(); } },
        onItemSelect:function(li){
            $("#notificacion_unidad_destino").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un_destino").val(li.extra[0]);
        },
        autoFill:false
    });

    $("#bodega_central").click(function(){
        toggleBodegaCentral();
    });

    toggleBodegaCentral();
});

function toggleBodegaCentral(){
    $("#central").val($("#bodega_central").is(':checked') ? 1 : 0);
    if ($("#bodega_central").is(':checked')){
        $("#id_region_origen").val(0);
        $("#region_origen_sec").hide();
        $("#unidad_origen_sec").hide();
    } else {
        $("#region_origen_sec").show();
        $("#unidad_origen_sec").show();
    }
}

function cargarDatos(){
    var requesicion = $("#no_requesicion").val();
    if (!requesicion) alert("Debe ingresar el numero de requesicion");

    $.getJSON(urlprefix + 'js/dynamic/LDBI/requesicion.php', { numero: requesicion, ajax: 'true'}, function(data){
        existencias = new Array();
        $("#desc_envio tbody").html("");
        toggleEnvioDetalle();

        if (!data){
            alert("No existen datos para la requesicion ingresada");
            return;
        }

        for (var i = 0; i < data.length; i++) {
            $("#id_requesicion").val(data[i].id_requesicion);
            agregarFila(data[i]);
        }
    });
}

function toggleEnvioDetalle(){
    if (existencias.length <= 0 ){
        $("#tablaDescripcionEnvio").hide();
    } else {
        $("#tablaDescripcionEnvio").show();
    }
}

$(function() {
    $( "#fecha_nota" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

function guardar(){
    if (presionado) return;
    presionado = true;

    var Errores = '';

    if (!$("#bodega_central").is(':checked') && $("#id_region").val() <= 0)
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una region de salud.</p>";

    if (esTextoVacio($("#no_nota").val()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el no. de nota.</p>";

    if(!isDate($("#fecha_nota").val().toString()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una fecha de nota valida.</p>";

    if ($("#id_razon").val() == "-1")
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar una razon.</p>";

    if (esTextoVacio($("#nombre_registra").val()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar nombre del responsable.</p>";

    if (existencias.length <= 0)
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe haber al menos un producto.</p>";

    presionado = false;
    if(Errores!="") {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Errores);
    } else {
        $("#guardarPrevio").val('1');
        $("#dSummaryErrors").css('display','none');
        $('#nombre_registra').attr('readonly', false);
        $('#nombre_registra').attr('disabled', '');
        $('#no_envio').attr('readonly', false);
        $('#no_envio').attr('disabled', '');

        var nuevo = '';
        if($('#action').val()=='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de Nota de Ajuste, \xbfdesea continuar?';
        } else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de Nota de Ajuste, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}