// LOAD
$(document).ready(function() {
    $('#dSummaryErrors').hide();
});

// Función principal de validación de campos ingresados
function validarLogin()
{
    var Errores = '';

    if(jQuery.trim($("#usuario").val())=="")
        Errores+= "- Debe ingresar su nombre de usuario.<br/>";
    if(jQuery.trim($("#password").val())=="")
        Errores+= "- Debe ingresar su contrase&ntilde;a.";

    if(Errores!="")
    {
        $('#dSummaryErrors').show();
        $('#pSummaryErrors').html(Errores);
        $('#lSummaryErrors').hide();
    }
    else
        $('form:frmContenido').submit();
}
