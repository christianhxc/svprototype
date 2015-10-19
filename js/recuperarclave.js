function validarFormulario(){
    var Message = "";

    if (jQuery.trim($("#username").val()) == ""){
        Message += "- Debe ingresar el nombre de usuario<br>";
    }
    if (Message != ""){
        $('#dSummaryErrors').show();
	$('#pSummaryErrors').html(Message);
        return false;
    }else{
    	$("#frmLogin").submit();
    }
}