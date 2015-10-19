$(document).ready(function() {
	$("#fecha_notificacion").datepicker({
		showOn: 'button',buttonImage: urlprefix + '/images/calendar.gif',buttonImageOnly: true,dateFormat: 'dd/mm/yy', appendText: ' dd/mm/aaaa'
	});
	
	$("#nacimiento").datepicker({
		showOn: 'button',buttonImage: urlprefix + '/images/calendar.gif',buttonImageOnly: true,dateFormat: 'dd/mm/yy', appendText: ' dd/mm/aaaa'
	});
	
	$("#drpidas").change(function(){
		cargarMunicipios(-1);
		cargarDistritos(-1);
  	});	
	
	$("#drpidmun").change(function(){
		cargarDistritos(-1);
  	});	
	
	$("#drpidds").change(function(){
		cargarServicios(-1);
  	});	
	
	$("#drpidtss").change(function(){
		cargarServicios(-1);
  	});

        $("#etnia").change(function(){
		habilitarEtnia("etnia", "etnia_otro");
  	});
	
	$("#otro_servicio").bind('blur', function() { otroServicio(); } );
	$("#otro_servicio_privado").bind('click', function() { otroServicio(); } );
	
	$("#departamento").change(function(){
    	$("#municipio").attr('disabled', 'disabled');
		$("#zona").attr('disabled', 'disabled');
		
		$.getJSON(urlprefix + 'js/dynamic/municipios.php',{iddep: $(this).val(), ajax: 'true'}, function(j){
      	var options = '';
		
		options += '<option value="0">N/A</option>';
		$("#zona").attr('disabled', '');
		$("#zona").html(options);
		
      	for (var i = 0; i < j.length; i++) {
        	options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
      	}
		
		$("#municipio").attr('disabled', '');
      	$("#municipio").html(options);
   	  })
  	});
	
	$("#municipio").change(function(){
		$("#zona").attr('disabled', 'disabled');
    	$.getJSON(urlprefix + 'js/dynamic/zonas.php',{iddep: $("#departamento").val(), idmun: $(this).val(), ajax: 'true'}, function(j){
      	var options = '';
		options += '<option value="0">N/A</option>';
      	for (var i = 0; i < j.length; i++) {
        	options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
      	}
		$("#zona").attr('disabled', '');
      	$("#zona").html(options);
   	  })
  	});
	
	otroServicio();
	
	cargarMunicipios($("#idmun").val());
	cargarDistritos($("#idds").val());
	cargarServicios($("#idts").val());

	habilitarNombre();

	habilitarNacimiento();
	habilitarLugar();

        habilitarEtnia("etnia", "etnia_otro");
});

function habilitarEtnia(condicion, texto){
	if ($("#" + condicion).val() == 0){
		$("#" + texto).show();
                $("#" + texto).attr('disabled', '');
		$("#" + texto).focus();
	}else{
		$("#" + texto).val('');
		$("#" + texto).attr('disabled', 'disabled');
                $("#" + texto).hide();
	}
}

function otroServicio(){
	if (jQuery.trim($("#otro_servicio").val()) != ""){
		$("#idts").val(0);
		$('#drpidts option:first-child').attr('selected','selected');
		$('#drpidtss option:first-child').attr('selected','selected');
		$("#drpidtss").attr('disabled', 'disabled');
		$("#drpidts").attr('disabled', 'disabled');
		$("#otro_servicio_privado").attr('disabled', '');
	}else{
		$("#drpidtss").attr('disabled', '');
		$("#drpidts").attr('disabled', '');
		$("#otro_servicio_privado").attr('disabled', 'disabled');
	}
}

function cargarMunicipios(idmunSel){
	var field = $("#drpidas").val();
	var codigo = field.split("_");
	var drpidas = codigo[0]; $("#idas").val(drpidas);
	var drpiddep = codigo[1]; $("#iddep").val(drpiddep);
	var drpidmun = $("#drpidmun").val();
	
	if (idmunSel == -1){
		$("#idmun").val(0);
		$("#idds").val(0);
		$('#drpidmun option:first-child').attr('selected','selected');
	}
	// Cargar Municipios
	$.getJSON(urlprefix + 'js/dynamic/municipios.php',{iddep: drpiddep, ajax: 'true'}, function(j){
		var options = '';
		
		// Habilitar campo y poner un valor N/A por default
		options += '<option value="0">N/A</option>';
		$("#drpidmun").attr('disabled', '');
		$("#drpidmun").html(options);
		
		for (var i = 0; i < j.length; i++) {
			// Verificar si el valor preseleccionado coincide
			var preSel = "";
			if (j[i].optionValue == idmunSel){ preSel = 'selected="selected"'; }
			options += '<option value="' + j[i].optionValue + '" ' + preSel + '>' + j[i].optionDisplay + '</option>';
		}
		
		$("#drpidmun").html(options);
  	});
}

function cargarDistritos(iddsSel){
	var campo = "drpidds";
	
	if (iddsSel == -1){
		$("#idts").val(0);
		$("#idtss").val(0);
		$('#drpidts option:first-child').attr('selected','selected');
		$('#drpidtss option:first-child').attr('selected','selected');
	}
	// Deshabilitar campos
	$("#" + campo).attr('disabled', 'disabled');
	$("#drpidtss").attr('disabled', 'disabled');
	$("#drpidts").attr('disabled', 'disabled');
	
	var drpidas = $("#idas").val();
	var drpiddep = $("#iddep").val();
	var drpidmun = $("#" + (iddsSel == -1 ? "drpidmun" : "idmun")).val();
	
	// Cargar Distritos de Salud
	$.getJSON(urlprefix + 'js/dynamic/distritos.php',{idas: drpidas, iddep: drpiddep, idmun: drpidmun, ajax: 'true'}, function(j){
		var options = '';
		
		// Habilitar campo y poner un valor N/A por default
		options += '<option value="0">N/A</option>';
		$("#" + campo).attr('disabled', '');
		$("#" + campo).html(options);
		
		for (var i = 0; i < j.length; i++) {
			// Verificar si el valor preseleccionado coincide
			var preSel = "";
			if (j[i].optionValue == iddsSel){ preSel = 'selected="selected"'; }
			options += '<option value="' + j[i].optionValue + '" ' + preSel + '>' + j[i].optionDisplay + '</option>';
		}
		
		$("#" + campo).html(options);
  	});
}

function cargarServicios(idtsSel){
	var campo = "drpidts";
	var drpidas = $("#idas").val();
	var drpidds = $("#" + (idtsSel == -1 ? "drpidds" : "idds")).val();
	var drpidtss = $("#drpidtss").val(); $("#idtss").val(drpidtss);
	if (idtsSel == -1) $("#idds").val(drpidds);
	
	// Deshabilitar campos
	$("#" + campo).attr('disabled', 'disabled');
	$("#drpidtss").attr('disabled', 'disabled');

	if (idtsSel != "") $("#idds").val(drpidds);

	$.getJSON(urlprefix + 'js/dynamic/servicios.php',{idas: drpidas, idds: drpidds, idtss: drpidtss, ajax: 'true'}, function(j){
		var options = '';
		
		// Habilitar campo y poner un valor N/A por default
		options += '<option value="0">N/A</option>';
		if (jQuery.trim($("#otro_servicio").val()) == "")
			$("#" + campo).attr('disabled', '');
		$("#" + campo).html(options);
		$("#idts").val(0);
		
		for (var i = 0; i < j.length; i++) {
			// Verificar si el valor preseleccionado coincide
			var preSel = "";
			if (j[i].optionValue == idtsSel){ preSel = 'selected="selected"'; $("#idts").val(idtsSel);}
			options += '<option value="' + j[i].optionValue + '" ' + preSel + '>' + j[i].optionDisplay + '</option>';
		}
		
		$("#" + campo).html(options);
		$("#drpidtss").attr('disabled', '');
		otroServicio();
  	})
}

function validarDatosGenerales(){
	var General = "";
	
	if (!isDate($('#fecha_notificacion').val()))
		General += "- Debe ingresar la fecha de notificacion<br>";
	
	if (jQuery.trim($('#no_ficha').val()) == "")
		General += "- Debe escribir el numero de la ficha<br>";
		
	if (jQuery.trim($('#drpidas').val()) == 0)
		General += "- Debe seleccionar el Area de Salud<br>";
		
	if (jQuery.trim($('#drpidds').val()) == 0)
		General += "- Debe seleccionar el Distrito de Salud<br>";

        if ($('#centro_salud').val() != null){
            if (jQuery.trim($('#centro_salud').val()) == "")
		General += "- Debe escribir el nombre del centro de salud<br>";
        }

        if ($('#puesto_salud').val() != null){
            if (jQuery.trim($('#puesto_salud').val()) == "")
		General += "- Debe escribir el nombre del puesto de salud<br>";
        }
        
        if ($('#centro_convergencia').val() != null){
            if (jQuery.trim($('#centro_convergencia').val()) == "")
		General += "- Debe escribir el nombre del centro de convergencia<br>";
        }

	if (jQuery.trim($('#drpidts').val()) == 0 && jQuery.trim($('#otro_servicio').val()) == "")
		General += "- Debe seleccionar el Servicio de Salud<br>";
	
	if (jQuery.trim($('#responsable').val()) == "")
		General += "- Debe escribir el nombre del responsable<br>";
		
	if (jQuery.trim($('#cargo').val()) == "")
		General += "- Debe escribir el cargo del responsable<br>";
	
	return General;
}

function validarDatosPersonales(){
	semanaEpidemiologica();
	
	var Paciente = "";
	
	if (!$("#nombre_na").is(":checked")){
		if (jQuery.trim($('#primer_nombre').val()) == ""){Paciente += "- Debe ingresar el primer nombre del paciente<br>";}
		if (jQuery.trim($('#primer_apellido').val()) == ""){Paciente += "- Debe ingresar el primer apellido del paciente<br>";}
	}
	
	if (!$("#nacimiento_na").is(":checked")){
		if (!isDate($('#nacimiento').val()) && 
			(
			 (jQuery.trim($('#meses').val()) == "mm" || jQuery.trim($('#meses').val()) == "") || 
			 (jQuery.trim($('#anios').val()) == "aa" || jQuery.trim($('#anios').val()) == "")
			)
		   )
		{
			Paciente += "- Debe ingresar la edad o fecha de nacimiento<br>";
		}
	}
	
	if (!$("#lugar_na").is(":checked")){
		if ($('#zona').val() == "0"){Paciente += "- Debe seleccionar la localidad del paciente<br>";}
	}
	
        if ($("#etnia").val() == "-1")
		Paciente += "- Debe seleccionar un grupo etnico<br>";
            
        if ($("#etnia").val() == "0" && jQuery.trim($('#etnia_otro').val()) == "")
		Paciente += "- Debe indicar la descripcion del grupo etnico<br>";

	if (!$("#sexo_f").is(":checked") && !$("#sexo_m").is(":checked"))
		Paciente += "- Debe seleccionar un sexo para el paciente<br>";
		
	return Paciente;
}

function habilitarNacimiento(){
	if ($("#nacimiento_na").is(":checked")){
		$("#nacimiento").val("dd/mm/aaaa");
		$("#meses").val("mm");
		$("#anios").val("aa");
		$('#nacimiento').attr('disabled', 'disabled');
		$('#meses').attr('disabled', 'disabled');
		$('#anios').attr('disabled', 'disabled');
	}else{
		$('#nacimiento').attr('disabled', '');
		$('#meses').attr('disabled', '');
		$('#anios').attr('disabled', '');
	}
}

function habilitarLugar(){
	if ($("#lugar_na").is(":checked")){
		/*$('#departamento option:first-child').attr('selected','selected');
		$('#municipio option:first-child').attr('selected','selected');
		$('#zona option:first-child').attr('selected','selected');*/
		$("#direccion").val("")
		$('#direccion').attr('disabled', 'disabled');
		$('#departamento').attr('disabled', 'disabled');
		$('#municipio').attr('disabled', 'disabled');
		$('#zona').attr('disabled', 'disabled');
	}else{
		$('#direccion').attr('disabled', '');
		$('#departamento').attr('disabled', '');
		$('#municipio').attr('disabled', '');
		$('#zona').attr('disabled', '');
	}
}

function habilitarNombre(){
	if ($("#nombre_na").is(":checked")){
		$("#primer_nombre").val("");
		$("#segundo_nombre").val("");
		$("#primer_apellido").val("");
		$("#segundo_apellido").val("");
		$("#casada_apellido").val("");
		$('#primer_nombre').attr('disabled', 'disabled');
		$('#segundo_nombre').attr('disabled', 'disabled');
		$('#primer_apellido').attr('disabled', 'disabled');
		$('#segundo_apellido').attr('disabled', 'disabled');
		$('#casada_apellido').attr('disabled', 'disabled');
	}else{
		$('#primer_nombre').attr('disabled', '');
		$('#segundo_nombre').attr('disabled', '');
		$('#primer_apellido').attr('disabled', '');
		$('#segundo_apellido').attr('disabled', '');
		$('#casada_apellido').attr('disabled', '');
	}
}