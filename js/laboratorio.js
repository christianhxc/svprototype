$(document).ready(function() {
	$("#muestra").change(function(){
    	cargarPruebas();
  	});
	
	$("#prueba").change(function(){
    	cargarResultadosPrueba();
  	});
	
	$("#fecha_prueba").datepicker({
		showOn: 'button',buttonImage: urlprefix + 'images/calendar.gif',buttonImageOnly: true,dateFormat: 'dd/mm/yy', appendText: ' dd/mm/aaaa'
	});
	
	cargarPruebas();
});

var pruebas = 0;
function agregarPrueba(){
	var idsPrueba = $("#idsPrueba").val();
	var txtPrueba = $("#prueba").find('option').filter(':selected').text();
	var valPrueba = $("#prueba").val();
	var valPruebaResultado = $("#resultado_prueba").val();
	var valLaboratorio = jQuery.trim($("#laboratorio").val());
	var valFecha = $("#fecha_prueba").val();
	var txtOtro = $("#resultado_otro").val();
	var	txtResultado = $("#resultado_prueba").find('option').filter(':selected').text();
	var valResultado = $("#resultado_prueba").val();
		
	if ($("#lns").is(":checked")){
		valLaboratorio = "LNS";
	}
	
	if (!isDate(valFecha)){
		alert("La fecha no es valida, intente de nuevo");
		return;
	}else{
		var unidad = valFecha.split("/");
		var dia = unidad[0]; var mes = unidad[1]; var anio = unidad[2];
		var valFechaSQL = anio + "-" + mes + "-" + dia;
	}
	
	if (txtOtro == "" && txtResultado == "Otros"){
		alert("Debe especificar el resultado de la prueba");
		 $("#resultado_otro").focus();
		return;
	}
	
	if (valLaboratorio == ""){
		alert("Debe ingresar el nombre del laboratorio o chequear la casilla LNS");
		return;
	}
	
	if (txtResultado == "Otros"){
		txtResultado = txtOtro;
	}
	
	// Validar que no existan repetidos
	var idPrueba = valPruebaResultado + valLaboratorio + ":";
	if (idsPrueba.search(idPrueba) >= 0){
		alert("Ya existe una entrada en el laboratorio \"" + valLaboratorio + "\" para la prueba \"" + txtPrueba + "\"");
		return;
	}else{
		$("#idsPrueba").val(idsPrueba + idPrueba);
	}
	
	// Armar la tupla de pruebas
	var template = "<tr id=\"trPrueba"+pruebas+"\"><td><input name=\"data[pruebas]["+pruebas+"][idprueba_resultado]\" id=\"pruebas"+pruebas+"idprueba_resultado\" type=\"hidden\" value=\""+valResultado+"\"/><input name=\"data[pruebas]["+pruebas+"][otro]\" type=\"hidden\" value=\""+txtOtro+"\"/><input name=\"data[pruebas]["+pruebas+"][laboratorio]\"  id=\"pruebas"+pruebas+"laboratorio\" type=\"hidden\" value=\""+valLaboratorio+"\"/><input name=\"data[pruebas]["+pruebas+"][fecha]\" type=\"hidden\" value=\""+valFechaSQL+"\"/></td><td><a href=\"#\" onmouseout=\"RollOut(this)\" onmouseover=\"RollOver(this)\" class=\"ui-state-default ui-corner-all ui-link-button\" title=\"Borrar\" onclick=\"if (confirm('¿Esta seguro que desea borrar esta entrada?')) removerPrueba("+pruebas+");\"><span class=\"ui-icon ui-icon-trash\"></span></a></td><td>" + valFecha + "</td><td>" + valLaboratorio + "</td><td> " + txtPrueba + "</td><td>" + txtResultado + "</td></tr>";
	// Agregar dinamicamente una tupla de pruebas
	$(template).appendTo("#tblPruebas tbody");
	// Aumentar el id a utilizar la proxima vez
	pruebas++;
}

function removerPrueba(id){
	var idsPrueba = $("#idsPrueba").val();
	var idPrueba = $("#pruebas"+id+"idprueba").val();
	var valLaboratorio = $("#pruebas"+id+"laboratorio").val();
	
	idsPrueba = idsPrueba.replace(idPrueba + valLaboratorio + ":","");
	$("#idsPrueba").val(idsPrueba);
	$('#trPrueba' + id).remove();
}

function habilitarTextoLaboratorio(){
	if ($("#lns").is(":checked")){ 
		$("#laboratorio").val(""); 
		$('#laboratorio').attr('disabled', 'disabled');
	}else{
		$('#laboratorio').attr('disabled', '');
	}
}

function habilitarResultadoOtro(){
	var	txtResultado = $("#resultado_prueba").find('option').filter(':selected').text();
	if (txtResultado == "Otros"){
		$("#resultado_otro").show();
		$("#resultado_otro").focus();
	}else{
		$("#resultado_otro").val("");
		$("#resultado_otro").hide();
	}
}

function cargarPruebas(){
	$("#prueba").attr('disabled', 'disabled');
	$("#resultado_prueba").attr('disabled', 'disabled');
		
	$.getJSON(urlprefix + 'js/dynamic/resultadosprueba.php',{consulta: 'PRUEBA', idmuestra: $("#muestra").val(), ajax: 'true'}, function(j){
  		var options = '';
	
    	for (var i = 0; i < j.length; i++) {
    		options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
    	}
		$("#prueba").attr('disabled', '');
    	$("#prueba").html(options);
		cargarResultadosPrueba();
	});
}

function cargarResultadosPrueba(){
	$("#resultado_prueba").attr('disabled', 'disabled');
	$.getJSON(urlprefix + 'js/dynamic/resultadosprueba.php',{consulta: 'RESULTADO', idprueba: $("#prueba").val(), ajax: 'true'}, function(j){
  		var opciones = '';
    	for (var i = 0; i < j.length; i++) {
    		opciones += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
    	}

		$("#resultado_prueba").attr('disabled', '');
    	$("#resultado_prueba").html(opciones);
		habilitarResultadoOtro();
	});
}