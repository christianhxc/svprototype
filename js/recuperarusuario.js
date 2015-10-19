function validarFormulario(){
    var Message = "";

    if (jQuery.trim($("#nombres").val()) == ""){
        Message += "- Debe ingresar sus nombres<br>";
    }

    if (jQuery.trim($("#apellidos").val()) == ""){
        Message += "- Debe ingresar sus apellidos<br>";
    }

    if (jQuery.trim($("#email").val()) == ""){
        Message += "- Debe ingresar su e-mail<br>";
    }

    if (Message != ""){
        $('#dSummaryErrors').show();
	$('#pSummaryErrors').html(Message);
        return false;
    }else{
    	$("#frmLogin").submit();
    }
}