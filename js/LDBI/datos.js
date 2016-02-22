
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

$(document).ready(function() {
    $('#id_tipo').change(function() {
        if ($(this).val() == 1){
            $("#exportar").show();
            $("#importar").hide();
        } else {
            $("#exportar").hide();
            $("#importar").show();
        }
    });
});

$(function() {
    $( "#fh_inicio" ).datepicker({
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
    $( "#fh_fin" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        buttonImage: urlprefix+"img/calendar.gif",
        buttonImageOnly: true,
        showAnim: "slideDown"
    });
});

function generar(){
    if (presionado) return;
    presionado = true;

    var tipo = $("#id_tipo").val();

    var Errores = '';

    if (tipo == 1){
        if(!isDate($("#fh_inicio").val().toString()))
            Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una fecha inicio valida.</p>";

        if(!isDate($("#fh_fin").val().toString()))
            Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar una fecha fin valida.</p>";
    } else {
        if ($("#file").val() == '')
            Errores+= "<p>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar un archivo.</p>";
    }

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

        $("#dSummaryErrors").css('display','none');

        if (tipo == 1){
            $.ajax({
                type: "POST",
                url: urlprefix + 'LDBI/data/export.php',
                data: "ini=" + $("#fh_inicio").val() + "&fin=" + $("#fh_fin").val(),
                success: function(data) {
                    $("#resultadosBusqueda").html(data);
                }
            });
        } else {
            $("#frmContenido").submit();
        }
    }
}

$('#frmContenido').ajaxForm({
    beforeSubmit: ShowRequest,
    success: SubmitSuccesful,
    error: AjaxError
});

function ShowRequest(formData, jqForm, options) {
    $("#resultadosBusqueda").html("Importando los datos, esto puede tomar un tiempo, por favor no presione ningun boton ...");
    return true;
}

function SubmitSuccesful(responseText, statusText) {
    $("#resultadosBusqueda").html(responseText);
};

function AjaxError() {
    $("#resultadosBusqueda").html("Ocurrio un error al subir el archivo, intente de nuevo");
};