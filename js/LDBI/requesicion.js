
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

    if (!esNumero($("#cantidad").val())){
        alert("Debe ingresar un cantidad valida.")
        return false;
    }

    agregarFila(data);

    $("#nombre_prod").val("");
    $("#id_prod").val("");
    $("#cantidad").val("");
}

function agregarFila(data){
    var tpl = '<tr id="detalle_'+data.id_prod+'">'+
        '<td class="fila" width="54">'+
        data.codigo_insumo+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][id_insumo]" value="'+data.id_prod+'">'+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][cantidad]" value="'+data.cantidad+'">'+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][fh_vencimiento]" value="'+data.fh_vencimiento+'">'+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][no_lote]" value="'+data.no_lote+'">'+
        '<input type="hidden" name="data[existencias]['+data.id_prod+'][enviado]" value="'+data.enviado+'">'+
        '</td>'+
        '<td class="fila" width="295"><strong>'+data.nombre_prod+'</strong></td>'+
        '<td class="fila" style="text-align:center" width="144">'+data.unidad_presentacion+'</td>'+
        '<td class="fila" style="text-align:center" width="66">'+data.cantidad+'</td>'+
        '<td class="fila" style="text-align:center" width="78">'+data.enviado+'</td>';

        if (!$("#nodelete").val()) {
            tpl += '<td class="fila" align="center" width="48">' +
            '    <a href="javascript:eliminarRegistro(' + data.id_prod + ')">' +
            '        <img src="/sisvig2/img/Delete.png" title="Eliminar" border="0">' +
            '    </a>' +
            '</td>';
        }

        tpl +='</tr>';

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

    $("#notificacion_unidad").autocomplete(urlprefix + "js/dynamic/unidadNotificadoraRegion.php", {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        useCache:false,
        extraParams: { r: function() { return $("#id_region").val(); } },
        onItemSelect:function(li){
            $("#notificacion_unidad").val($("<div>").html(li.selectValue).text());
            $("#notificacion_id_un").val(li.extra[0]);
            $("#label_valor_region1").html(li.extra[1]);
        },
        autoFill:false
    });
});

function toggleEnvioDetalle(){
    if (existencias.length <= 0 ){
        $("#tablaDescripcionEnvio").hide();
    } else {
        $("#tablaDescripcionEnvio").show();
    }
}

$(function() {
    $( "#fecha_requesicion" ).datepicker({
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
    $( "#fecha_despacho" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});
//$(function() {
//    $( "#fh_vencimiento" ).datepicker({
//        dateFormat: 'dd/mm/yy',
//        changeMonth: true,
//        changeYear: true,
//        showOn: "both",
//        buttonImage: urlprefix+"img/calendar.gif",
//        buttonImageOnly: true,
//        showAnim: "slideDown"
//    });
//});

function guardar(){
    if (presionado) return;
    presionado = true;

    var Errores = '';

    if ($("#id_region").val() == -1){
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la region.</p>";
    }

    //if (esTextoVacio($("#notificacion_unidad").val()) || esTextoVacio($("#notificacion_id_un").val())){
    //    Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la unidad notificadora.</p>";
    //}

    if ($("#notificacion_unidad").val() == ''){
        $("#notificacion_id_un").val(0);
    }

    if (esTextoVacio($("#no_requesicion").val()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el no. de requesici&oacute;n.</p>";

    if(!isDate($("#fecha_requesicion").val().toString()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una fecha de requesici&oacute;n valida.</p>";

    if (esTextoVacio($("#nombre_registra").val()))
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar nombre del responsable.</p>";

    if (existencias.length <= 0)
        Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar al menos un producto.</p>";

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
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Formulario de Requesicion, \xbfdesea continuar?';
        } else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Formulario de Requesicion, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}