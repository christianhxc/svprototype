var referida_por_obligatorio = false;
var confidencial = false;
var inicioSintomasOpcional = false;
var donadorPaciente = false;
var cargaViral = false;
var tabActivo = 0;
var semanaEpi = 0;
var encuestaSerologica = false;
var dep = 0;
var mun=0;
var lp = 0;
var found = false;
var pagina = 1;
var alicuotas = new Array();
var conteo = 0;

// LOAD
$(document).ready(function() {

    // Popup de búsqueda
    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    $("#alicuotasLista").hide();

    // Divide en tabs el ingreso de los datos
    $(function() {
        $("#tabs").tabs({selected:0, select:function(event, ui){
            if(ui.index==0 || ui.index==1)
                $('#next').html("<span class='ui-icon ui-icon-arrowthick-1-e'></span>Siguiente");
            else
                $('#next').html("<span class='ui-icon ui-icon-seek-first'></span>Inicio");
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

    $('#dSummaryErrors').hide();

    // Campo que depende del servicio
    //$("#servicio_hospitalar").hide();

//    ($("#ref")).hide();

    // No es muestra humana
    $("#no_muestra_humana").click(function(){
        clickNohumana();
    });

    $("#no_distrito_muestra").click(function(){
        clickNoDistrito();}
    );

    $("#no_establecimiento").click(function(){
        clickNoEstablecimiento();
    });

    $("#no_direccion_muestra").click(function(){
        clickNoDirMuestra();
    });

    // CASO CONFIDENCIAL
    $("#confidencial").click(function(){
        clickConfidencial();
    });

    //----------------------------------------------------------
    // Campos no disponibles en ficha
    $("#identificador_no_disponible").click(function(){
        clickIdentificadorDisponible();
    });

    $("#no_direccion_individuo").click(function(){
        clickNoDirIndividuo();
    });
    //----------------------------------------------------------

    // Rechazo en ventanilla
    $("#muestra_rechazada_si").click(function()
    {
        // Rechazada en ventanilla?
        if ($("#muestra_rechazada_si").is(":checked"))
            ($("#rechazoRow")).show();
        else
            ($("#rechazoRow")).hide();
    });

    $("#muestra_rechazada_no").click(function()
    {
        if ($("#muestra_rechazada_no").is(":checked"))
        {
            ($("#drprazonrechazo")).val(1);
            $("#razon_rechazo").val(1);
            ($("#rechazoRow")).hide();
        }
        else
            ($("#rechazoRow")).show();
    });

    // ==============================================================================================================
    // Listas de procedencia en cascada
    // ==============================================================================================================

    // INDIVIDUO
    $("#drpdepartamento").change(function(){
            $.getJSON(urlprefix + 'js/dynamic/municipios.php',{
            iddep: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';
            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue+ '">' + j[i].optionDisplay + '</option>';
            }

            $("#drpmunicipio").html(options);
            $("#drplocalidad").html('<option value="0">N/A</option>');
        })
        $("#iddep").val($(this).val());
    });

    $("#drpmunicipio").change(function()
    {
            $.getJSON(urlprefix + 'js/dynamic/zonas.php',{
            iddep: $("#drpdepartamento").val(), idmun:$(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drplocalidad").html(options);
        })
        $("#idmun").val($(this).val());
    });

    $("#drplocalidad").change(function(){
        $("#lp").val($(this).val());
    });

// MUESTRA

    $("#drpareamuestra").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/distritos.php',{
            idas: $(this).val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpdistrito").html(options);
            $("#drpservicio").html('<option value="0">N/A</option>');
        })
        $("#area_salud_muestra").val($(this).val());

        $("#servicio_intrahosp").val('');
        $("#servicio_hospitalar").hide();

    });

    $("#drpdistrito").change(function()
    {
        $.getJSON(urlprefix + 'js/dynamic/servicios.php',{idas:$("#drpareamuestra").val(), idds:$("#drpdistrito").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '-' + j[i].tipo + '-' + j[i].hosp + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drpservicio").html(options);
        })
        $("#distrito_muestra").val($("#drpdistrito").val());
        $("#servicio_intrahosp").val('');
        $("#servicio_hospitalar").hide();
    });

    $("#drpservicio").change(function()
    {
        changeServicio();
    });

    $("#vigilancia").change(function(){
        $("#tipo_vigilancia").val($(this).val());
    });

    // ==============================================================================================================
    // Listas eventos y muestras en cascada
    // ==============================================================================================================

    $("#drpareaanalisis").change(function()
    {
        if(conteo==0)
            changeArea();
        else
        {
           // Perderá las alicuotas
           if(confirm("Si cambia de \xe1rea se perder\xe1n las muestras de la lista de al\xedcuotas. \xbfContinuar?"))
           {
               changeArea();
               borrarTabla();
           }
        }

    });

 $("#drptipomuestra").change(function(){
        // Carga áreas de análisis de alícuotas según área de analisis de la muestra, tipo y evento de la muestra
        $.getJSON(urlprefix + 'js/dynamic/areas_analisis_derivacion.php',{
            a: $("#drpareaanalisis").val(),
            e: $("#drpevento").val(),
            t: $("#drptipomuestra").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#area_alicuota").html(options);
            $("#evento_alicuota").html('<option value="0">N/A</option>');
        })
 })


    $("#drpevento").change(function(){

        if(conteo==0)
        {
           $("#carga_viral").attr('checked',false);
           $("#carga").hide();

           $("#encuesta_serologica").attr('checked',false);
           $("#serologica").hide();

           $("#donador").attr('checked',false);
           $("#paciente").attr('checked',true);
           cargaEventos();
        }
        else
        {
           // Perderá las alicuotas
           if(confirm("Si cambia de evento se perder\xe1n las muestras de la lista de al\xedcuotas. \xbfContinuar?"))
           {
               $("#carga_viral").attr('checked',false);
               $("#carga").hide();

               $("#encuesta_serologica").attr('checked',false);
               $("#serologica").hide();

               $("#donador").attr('checked',false);
               $("#paciente").attr('checked',true);
               cargaEventos();
               borrarLista();
           }
           // else cancelar cambio de índice
        }
    });

    $("#area_alicuota").change(function(){
        cargarEventosAlicuota();
    });

     $("#drptipomuestra").change(function(){
         $("#tipo_muestra").val($(this).val());
     });

     $("#drprazonrechazo").change(function(){
         $("#razon_rechazo").val($(this).val());
     });

    $("#drptipo_edad").change(function(){
        validarEdad();
    });

    $("#drpPopTipo").change(function(){
        validarEdadPop();
    });

    if($("#no_muestra_humana").attr('checked'))
        clickNohumana();

    if($("#identificador_no_disponible").attr('checked'))
        clickIdentificadorDisponible();

    if($("#confidencial").attr('checked'))
        clickConfidencial();

    if($("#no_direccion_individuo").attr('checked'))
        clickNoDirIndividuo();

    if($("#no_distrito_muestra").attr('checked'))
       clickNoDistrito();

    if($("#no_establecimiento").attr('checked'))
        clickNoEstablecimiento();

    if($("#no_direccion_muestra").attr('checked'))
        clickNoDirMuestra();

    $("#hospitalario").change(function()
    {
        if($("#hospitalario").val()==1)
        {
            $("#senal").html('<img src="'+urlprefix+'img/alerta_naranja.png" alt="Hospitalizado" width="16" height="16"/>');
        }
        else if ($("#hospitalario").val()==2)
        {
            $("#senal").html('<img src="'+urlprefix+'img/alerta_rojo.png" alt="Fallecido" width="16" height="16"/>');
        }
        else if ($("#hospitalario").val()==0)
        {
            $("#senal").html('');
        }
    });


    changeServicio();
    revisarOtroEstablecimiento();
});

function changeArea()
{
    $.getJSON(urlprefix + 'js/dynamic/eventos.php',{
        idarea: $("#drpareaanalisis").val(),
        ajax: 'true'
        }, function(j){
        var options = '';
        options += '<option value="0">N/A</option>';

        for (var i = 0; i < j.length; i++){
            options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
        }

        $("#drpevento").html(options);
        $("#drptipomuestra").html('<option value="0">N/A</option>');
        })

        $("#don_pac").hide();donadorPaciente = false;
        cargaViral = false;
        $("#don_pac").hide();donadorPaciente = false;
        $("#area_analisis_muestra").val($(this).val());
        encuestaSerologica = false;

       $("#carga_viral").attr('checked',false);
       $("#carga").hide();

       $("#encuesta_serologica").attr('checked',false);
       $("#serologica").hide();

       $("#donador").attr('checked',false);
       $("#paciente").attr('checked',true);
}


function changeServicio()
{
//    $("#servicio_intrahosp").val('');
    $("#servicio_hospitalar").hide();

    var string = $("#drpservicio").val();
    var partes = string.split('-');
    $("#servicio_muestra").val(partes[0]);
    $("#tipo_servicio_muestra").val(partes[1]);

    if(partes[2]=='h')
    {
        $("#servicio_hospitalar").show();
        $("#senal_hospital").show();
        $("#senal_hospital").val(0);
    }
   else
   {
       $("#servicio_hospitalar").hide();
       $("#senal_hospital").hide();
       $("#senal_hospital").val(0);
   }
}


// Utiliza los criterios del filtro y los ingresa para evitar que los meta nuevamente
function useSearched()
{
    if(jQuery.trim( $("#id").val())==''  && jQuery.trim( $("#his").val())==''&& jQuery.trim( $("#n").val())=='' && jQuery.trim( $("#p").val())=='' && $("#drpsexoP").val()=='0')
    {
        alert("Debe ingresar al menos un dato")
    }
    else
    {

        $("#id_individuo").val(-1);
        if(jQuery.trim($("#id").val())!="")
        {
            if(!$("#identificador_no_disponible").attr('checked'))
                $("#identificador").val($("#id").val());
        }

        if(jQuery.trim($("#his").val())!="")
            $("#no_historia_clinica").val((jQuery.trim($("#his").val())==''? $("#no_historia_clinica").val():$("#his").val()));

        if(!$("#no_muestra_humana").attr('checked') && !$("#confidencial").attr('checked'))
        {
            $("#identificador").val((jQuery.trim($("#id").val())==''? $("#identificador").val():$("#id").val()));
            $("#primer_nombre").val((jQuery.trim($("#n").val())==''? $("#primer_nombre").val():$("#n").val()));
            $("#primer_apellido").val((jQuery.trim($("#p").val())==''? $("#primer_apellido").val():$("#p").val()));
            $("#drpsexo").val($("#drpsexoP").val());
        }
        $( "#dialog-form" ).dialog("close");
    }
}

function revisarOtroEstablecimiento()
{
    if(jQuery.trim($("#otro_establecimiento").val())!="")
    {
        $("#drpservicio").val(0);
        $("#drpservicio").attr('disabled',true);
        $("#no_establecimiento").attr('disabled',true);
        $("#no_establecimiento").attr('checked',false);
        $("#servicio_intrahosp").val("");
        $("#servicio_hospitalar").hide();
        $("#senal_hospital").hide();
        $("#senal_hospital").val(0);
    }
    else
    {
        $("#drpservicio").attr('disabled',false);
        $("#no_establecimiento").attr('disabled',false);
    }
}


function cargaEventos()
{
        $("#evento_muestra").val($("#drpevento").val());
        // Obtiene las propiedades del evento seleccionado
        $.getJSON(urlprefix + 'js/dynamic/eventosProp.php',{
            idevento: $("#drpevento").val(),
            ajax: 'true'
        }, function(j){
            if(j.length==0)
            {
                $("#evento_donador").val(2);
                $("#evento_confidencial").val(2);
                $("#evento_carga_viral").val(2);
                $("#evento_inicio_sintomas").val(1);
                $("#evento_encuesta_serologica").val(2);

                $("#oblig").show();inicioSintomasOpcional = false;
                $("#don_pac").hide();donadorPaciente = false;
                $("#carga").hide();cargaViral = false;
                $("#serologica").hide();encuestaSerologica = false;
                $("#don_pac").hide();donadorPaciente = false;
            }
            else
            {
                $("#evento_donador").val(j[0].pacienteDonador);
                $("#evento_confidencial").val(j[0].confidencial);
                $("#evento_carga_viral").val(j[0].cargaViral);
                $("#evento_inicio_sintomas").val(j[0].inicioSintomas);
                $("#evento_encuesta_serologica").val(j[0].encuestaSerologica);

                // Mostrar campos obligatorios según propiedades del evento
                // Fecha de inicio de síntomas
                if(j[0].inicioSintomas!=1)
                {
                    $("#oblig").hide();
                    inicioSintomasOpcional = true;
                }
                else
                {
                    $("#oblig").show();
                    inicioSintomasOpcional = false;
                }

                // Encuesta serológica
                if(j[0].encuestaSerologica!=1)
                {
                    $("#serologica").hide();
                    encuestaSerologica = true;
                }
                else
                {
                    $("#serologica").show();
                    encuestaSerologica = false;
                }

                // Carga viral
                if(j[0].cargaViral!=1)
                {
                    $("#carga").hide();
                    cargaViral = false;
                }
                else
                {
                    $("#carga").show();
                    cargaViral = true;
                }

                // Confidencial obligatoriamente
                if(j[0].confidencial==1)
                    confidencial = true;
                else
                    confidencial = false;

                // Paciente donador
                if(j[0].pacienteDonador==1)
                {
                    donadorPaciente = true;
                    $("#don_pac").show();
                }
                else
                {
                    donadorPaciente = false;
                    $("#don_pac").hide();
                }
            }
            verificar();
        })

        // Muestra tipos de muestra según evento
        $.getJSON(urlprefix + 'js/dynamic/tipomuestras.php',{
            idevento: $("#drpevento").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#drptipomuestra").html(options);
        })       
}

function getSelectedTabIndex() {
    return $("#tabs").tabs('option', 'selected');
}

function clickNoDirMuestra()
{
        if ($("#no_direccion_muestra").attr('checked'))
        {
            $("#direccion_muestra").val("");
            $("#direccion_muestra").attr('disabled',true);
        }
        else
            $("#direccion_muestra").attr('disabled',false);
}

function clickNoDistrito()
{
        // Distrito no disponible
        if ($("#no_distrito_muestra").attr('checked'))
        {
            $("#no_establecimiento").attr('checked',true);
            $("#no_direccion_muestra").attr('checked',true);
            $("#drpdistrito").attr('disabled', true);
            $("#drpservicio").attr('disabled', true);
            $("#no_establecimiento").attr('disabled', true);
            $("#drpdistrito").val(0);
            $("#drpservicio").html('<option value="0"> N/A</option>');
            $("#drpservicio").val(0);
            $("#distrito_muestra").val(0);
            $("#servicio_muestra").val(0);
            $("#tipo_servicio_muestra").val(0);
            $("#drpservicio").val(0);
            clickNoEstablecimiento();
        }
        else
        {
            $("#drpdistrito").attr('disabled', false);
            $("#drpservicio").attr('disabled', false);
            $("#no_establecimiento").attr('checked',false);
            $("#no_establecimiento").attr('disabled', false);
            $("#no_direccion_muestra").attr('checked',false);
        }
        clickNoDirMuestra();
}

function clickNoEstablecimiento()
{
    if ($("#no_establecimiento").attr('checked'))
    {
        $("#drpservicio").attr('disabled',true);
        $("#drpservicio").val(0);
        $("#servicio_muestra").val(0);
        $("#servicio_intrahosp").val('');
        $("#servicio_hospitalar").hide();
    }
    else{
        $("#drpservicio").attr('disabled',false);
    }
}

function clickNoDirIndividuo()
{
    if ($("#no_direccion_individuo").is(":checked"))
    {
        $("#direccion_individuo").val("");
        $("#direccion_individuo").attr('disabled', true);
    }
    else
        $("#direccion_individuo").attr('disabled', false);
}

function clickConfidencial()
{
    // Caso confidencial
        if ($("#confidencial").attr('checked'))
        {
            $("#primer_nombre").val("");
            $("#segundo_nombre").val("");
            $("#primer_apellido").val("");
            $("#segundo_apellido").val("");
            deshabilitarConfidencial(true);
        }
        else
            deshabilitarConfidencial(false);
}

function clickNohumana()
{
    // No es muestra humana
    if ($("#no_muestra_humana").attr('checked'))
    {
        referida_por_obligatorio = true;
        ($("#ref")).show();
        deshabilitarNoHumana(true);
        $("#primer_nombre").val("");
        $("#segundo_nombre").val("");
        $("#primer_apellido").val("");
        $("#segundo_apellido").val("");
        $("#edad").val("");
        $("#fecha_nacimiento").val("");
        $("#drpsexo").val(0);
    }
    // Sí es muestra humana
    else
    {
        referida_por_obligatorio = false;
        ($("#ref")).hide();
        deshabilitarNoHumana(false);
        changeServicio();
    }
}

function clickIdentificadorDisponible()
{
    // Caso confidencial
    if ($("#identificador_no_disponible").attr('checked'))
    {
        $("#identificador").val("");
        $("#identificador").attr('disabled', true);
    }
    else
        $("#identificador").attr('disabled', false);
}

function servicioHospitalar()
{
    if($("#drpservicio").val()!="0")
    {
        var datosServicio = $("#drpservicio").val().split('-');
        $("#servicio_muestra").val(datosServicio[0]);
        if(datosServicio[2]=='h')
            $("#servicio_hospitalar").show();
        else
            $("#servicio_hospitalar").hide();
    }
    else
        $("#servicio_hospitalar").hide();
}

function deshabilitarNoHumana(estado)
{
    $("#primer_nombre").attr('disabled', estado);
    $("#segundo_nombre").attr('disabled', estado);
    $("#primer_apellido").attr('disabled', estado);
    $("#segundo_apellido").attr('disabled', estado);

    $("#confidencial").attr('disabled', estado);
    $("#confidencial").attr('checked', false);
    $("#drptipo_edad").attr('disabled', estado);
    $("#edad").attr('disabled', estado);
    $("#drpsexo").attr('disabled', estado);
    $("#fecha_nacimiento").attr('disabled', estado);
    $("#confidencial").attr('disabled', estado);
}

function deshabilitarConfidencial (estado)
{
    $("#primer_nombre").attr('disabled', estado);
    $("#segundo_nombre").attr('disabled', estado);
    $("#primer_apellido").attr('disabled', estado);
    $("#segundo_apellido").attr('disabled', estado);
    $("#no_muestra_humana").attr('disabled', estado);
    $("#identificador_no_disponible").attr('checked', false);
    $("#identificador_no_disponible").attr('disabled', estado);
    $("#no_muestra_humana").attr('disabled', estado);
    $('#no_muestra_humana').attr('checked', false);
    $("#identificador").attr('disabled', false);
}


function individuo(idP)
{
    $.ajax({
       type: "POST",
       url: urlprefix + 'js/dynamic/datosPersona.php',
       data: "id="+ idP,
       success: function(data)
       {
           var partes = data.toString().split('#');

           if(data.toString().length>0)
           {
               if(partes[1] == 1)
                    $('#no_muestra_humana').attr('checked', false);
               else{
                    $('#no_muestra_humana').attr('checked', true);
                    clickNohumana();
               }

               if(partes[2] == 1){
                   $('#confidencial').attr('checked', true);
                   clickConfidencial();
               }
               else
                   $('#confidencial').attr('checked', false);

               $("#identificador").val(replace(partes[3]));

               if(partes[4] != 1){
                   $('#identificador_no_disponible').attr('checked', true);
                   clickIdentificadorDisponible();
               }
               else
                   $('#identificador_no_disponible').attr('checked', false);

               $("#no_historia_clinica").val(replace(partes[5]));
               $("#primer_nombre").val(replace(partes[6]));
               $("#segundo_nombre").val(replace(partes[7]));
               $("#primer_apellido").val(replace(partes[8]));

               $("#segundo_apellido").val(replace(partes[9]));
               $("#edad").val(partes[10]);
               $("#drptipo_edad").val(partes[11]);
               $("#fecha_nacimiento").val((partes[12]==''?'':invFecha(1,partes[12])));
               $("#drpsexo").val(partes[13]);
               $("#vigilancia").val(partes[14]);$("#tipo_vigilancia").val(partes[14]);

               dep = partes[15];
               mun = partes[16];
               lp = partes[17];

               $("#iddep").val(dep);
               $("#idmun").val(mun);
               $("#lp").val(lp);

               $("#drpdepartamento").val(dep);
               munis(dep, mun);
               zonas(dep, mun, lp);

               $("#direccion_individuo").val(replace(partes[18]));

               if(partes[19] != 1){
                   $('#no_direccion_individuo').attr('checked', true);
                   clickNoDirIndividuo();
               }
               else{
                   $('#no_direccion_individuo').attr('checked', false);
                   $("#direccion_individuo").attr('disabled', false);
               }
               $("#resultadosBusqueda").html('');
               $("#dialog-form").dialog('close');
               found = true;
               $("#id_individuo").val(idP);
           }
           else
               found = false;
       }
     });
}

function nuevo()
{
    $("#id_individuo").val(-1);
    $("#dialog-form").dialog('close');
    $("resultadosBusqueda").html('');
    found = false;
}

function borrarIndividuo()
{
   found = false;
   $("#id_individuo").val(-1);
   // borra todos los datos de la pestaña de individuo

       // Datos personales
       $("#no_muestra_humana").attr('checked', false);
       $("#identificador_no_disponible").attr('checked',false);
       $("#confidencial").attr('checked',false);
       clickNoDirIndividuo();
       clickConfidencial();
       clickIdentificadorDisponible();
       clickNohumana();

       $("#identificador").val("");
       $("#no_historia_clinica").val("");

       $("#primer_nombre").val("");
       $("#segundo_nombre").val("");
       $("#primer_apellido").val("");
       $("#segundo_apellido").val("");

       $("#drptipo_edad").val(0);
       $("#edad").val("");
       $("#fecha_nacimiento").val("");
       $("#drpsexo").val(0);
       $("#vigilancia").val(0);$("#tipo_vigilancia").val(0);

       $("#drpdepartamento").val(0);$("#iddep").val(0);
       $("#drpmunicipio").val(0);$("#idmun").val(0);
       $("#drplocalidad").val(0);$("#lp").val(0);
       $("#direccion_individuo").val("");

       $("#no_direccion_individuo").attr('checked',false);
       clickNoDirIndividuo();
}

function munis(a, b)
{
        $.getJSON(urlprefix + 'js/dynamic/municipios.php',{
            iddep: a,
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';
            var extra='';
            for (var i = 0; i < j.length; i++)
            {
                if(j[i].optionValue != b)
                    extra = '';
                else
                    extra='selected="selected"';
                options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
            }

            $("#drpmunicipio").html(options);
            $("#drplocalidad").html('<option value="0">N/A</option>');
        })
}

function zonas(a, b, c)
{
        $.getJSON(urlprefix + 'js/dynamic/zonas.php',{
            iddep: a, idmun:b,
            ajax: 'true'
        }, function(j){
            var extra='';
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                if(j[i].optionValue != c)
                    extra = '';
                else
                    extra='selected="selected"';
                options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
            }
            $("#drplocalidad").html(options);
        })
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


function borrarTabla(){
   $("#resultadosBusqueda").html('');
   //$("#notFoundFilter").show();
}

function buscar(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form" ).dialog("open");
}

function dp()
{
    if($("#paciente").is(':checked'))
    {
        $("#donador").attr('checked','');
    }
    else
    {
        $("#donador").attr('checked',true)
    }
}

function pd()
{
    if($("#donador").is(':checked'))
    {
        $("#paciente").attr('checked','');
    }
    else
    {
        $("#paciente").attr('checked',true)
    }
}

function nr()
{
    if($("#muestra_rechazada_no").is(':checked'))
    {
        $("#muestra_rechazada_si").attr('checked','');
    }
    else
    {
        $("#muestra_rechazada_si").attr('checked',true)
    }
}

function sr()
{
    if($("#muestra_rechazada_si").is(':checked'))
    {
        $("#rechazoRow").show();
        $("#muestra_rechazada_no").attr('checked','');
    }
    else
    {
        $("#rechazoRow").hide();
        $("#muestra_rechazada_no").attr('checked',true)
    }
}

// Moverse al siguiente TAB de datos de la muestra
function siguienteTab()
{
    if(getSelectedTabIndex()==0)
    {
        $("#tabs").tabs('select', 1)
        $('#next').html("<span class='ui-icon ui-icon-arrowthick-1-e'></span>Siguiente");
    }
    else if(getSelectedTabIndex()==1)
    {
        $("#tabs").tabs('select', 2);
        $('#next').html("<span class='ui-icon ui-icon-seek-first'></span>Inicio");
    }
    else if(getSelectedTabIndex()==2)
    {
        $("#tabs").tabs('select', 0);
        $('#next').html("<span class='ui-icon ui-icon-arrowthick-1-e'></span>Siguiente");
    }
}


// Función principal de validación de campos ingresados
function validarIngresoAlicuotas()
{
    var Message = '';
    var ErroresP = '';
    var ErroresM = '';
    var ErroresA = '';
    var ErroresPaciente ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;DATOS PERSONALES';
    var ErroresMuestra ='<br/>&nbsp; &nbsp;&nbsp; &nbsp;DATOS DE LA MUESTRA';
    var ErroresAlicuotas = '<br/>&nbsp; &nbsp;&nbsp; &nbsp;INGRESO DE ALICUOTAS';

    if(!$("#no_muestra_humana").is(':checked'))
    {
        if(!$("#confidencial").is(':checked')){
            if(jQuery.trim($("#primer_nombre").val())=="")
                ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer nombre.";
        }

        if(!$("#confidencial").is(':checked')){
            if(jQuery.trim($("#primer_apellido").val())=="")
                ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el primer apellido.";
        }

        if(!$("#identificador_no_disponible").is(':checked'))
        {
            if(jQuery.trim($("#identificador").val())=="")
                ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
        }

        if(!$("#confidencial").is(':checked')){
            if(jQuery.trim($("#edad").val())=="")
            {
                if(jQuery.trim($("#fecha_nacimiento").val())!="")
                    calcularEdad();
                else
                    ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la edad de la persona.";
            }
        }

        if(!$("#confidencial").is(':checked')){
            if($("#drpsexo").val()==0)
                ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el sexo de la persona.";
        }

        if(!$("#confidencial").is(':checked')){
            if(jQuery.trim($("#fecha_nacimiento").val())=="")
            {
                if(jQuery.trim($("#edad").val())!="")
                    calcularFechaNacimiento();
                else
                    ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de nacimiento.";
            }
            else
            {
                if(!isDate($("#fecha_nacimiento").val().toString()))
                    ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de nacimiento no tiene el formato adecuado.";
            }
        }
    }
    else
    {
        if(!$("#identificador_no_disponible").is(':checked'))
        {
            if(jQuery.trim($("#identificador").val())=="")
                ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el n&uacute;mero identificador.";
        }
    }

    if($("#vigilancia").val()==0)
        ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de vigilancia.";

    if(jQuery.trim($("#drpdepartamento").val())==0)
        ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el departamento de procedencia " + (($("#no_muestra_humana").is(':checked'))?"del individuo":"de la persona");

    if(jQuery.trim($("#drpmunicipio").val())==0)
        ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el municipio de procedencia " + (($("#no_muestra_humana").is(':checked'))?"del individuo":"de la persona");

    if(jQuery.trim($("#drplocalidad").val())==0)
        ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la localidad de procedencia " + (($("#no_muestra_humana").is(':checked'))?"del individuo":"de la persona");

    // Dirección no disponible
    if(!($("#no_direccion_individuo").is(':checked')))
    {
        if(jQuery.trim($("#direccion_individuo").val())=="")
            ErroresP+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificar la direcci&oacute;n de procedencia "+ (($("#no_muestra_humana").is(':checked'))?"del individuo":"de la persona");
    }

    // Datos de la muestra
    if($("#drpareamuestra").val()==0)
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el &aacute;rea de salud de procedencia de la muestra.";

    if(!($("#no_distrito_muestra").is(':checked')))
    {
        if($("#drpdistrito").val()==0)
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el distrito de procedencia de la muestra.";

        if(!($("#no_establecimiento").is(':checked')))
        {
            if(jQuery.trim($("#otro_establecimiento").val())=="")
            {
                if($("#drpservicio").val()==0)
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el establecimiento de salud de procedencia de la muestra.";
            }
        }
    }

    if(referida_por_obligatorio)
    {
        if(jQuery.trim($("#referida_por").val())=="")
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificar la persona que refiere la muestra no humana.";
    }

    if($("#drpareaanalisis").val()==0)
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el &aacute;rea de an&aacute;lisis.";

    if($("#drpevento").val()==0)
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el evento de la muestra.";

    if($("#confidencial").is(':checked'))
    {
        if(!confidencial)
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar un evento que cumpla con la propiedad 'confidencial' que seleccion&oacute;.";
    }

    if($("#drptipomuestra").val()==0)
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el tipo de muestra.";

    // Dirección de procedencia de muestra no disponible
    if($("#muestra_rechazada_si").is(':checked'))
    {
        if($("#drprazonrechazo").val()==0)
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificar la raz&oacute;n de rechazo de la muestra.";
    }

    if(!inicioSintomasOpcional)
    {
        if(jQuery.trim($("#fecha_inicio_sintomas").val())=="")
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de inicio de s&iacute;ntomas.";
        else
        {
            if(!isDate($("#fecha_inicio_sintomas").val().toString()))
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de inicio de s&iacute;ntomas no es correcta.";
            else
            {
                if(!compararFechas($("#fecha_inicio_sintomas").val()))
                    ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de inicio de s&iacute;ntomas no puede ser mayor que la fecha de hoy.";
            }
        }
    }

    if(jQuery.trim($("#fecha_toma").val())=="")
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de toma de muestra.";
    else
    {
        if(!isDate($("#fecha_toma").val().toString()))
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de toma de muestra no es correcta.";
        else if(!compararFechas($("#fecha_toma").val()))
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de toma no puede ser mayor que la fecha de hoy.";
    }

    if(jQuery.trim($("#fecha_recepcion").val())=="")
        ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe especificarse una fecha de recepci&oacute;n.";
    else
    {
        if(!isDate($("#fecha_recepcion").val().toString()))
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de recepci&oacute;n no es correcta.";
        else if(!compararFechas($("#fecha_recepcion").val()))
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de recepci&oacute;n no puede ser mayor que la fecha de hoy.";
    }

    $("#no_historia_clinica").val(jQuery.trim($("#no_historia_clinica").val()));
    $("#segundo_nombre").val(jQuery.trim($("#segundo_nombre").val()));
    $("#segundo_apellido").val(jQuery.trim($("#segundo_apellido").val()));

    var inicio = jQuery.trim($("#fecha_inicio_sintomas").val());
    var toma = jQuery.trim($("#fecha_toma").val());
    var recepcion = jQuery.trim($("#fecha_recepcion").val());

    // VALIDAR FECHAS A<=B>=C
    if((inicio!="" && isDate(inicio) && compararFechas(inicio)) && (toma!="" && isDate(toma)&& compararFechas(toma)) && (recepcion!="" && isDate(recepcion) && compararFechas(recepcion)))
    {
        // Si ya se validó lo anterior (no vacío, es fecha y no mayor que hoy)
        if(compararFechasMuestra(inicio, toma))
        {
            if(!compararFechasMuestra(toma, recepcion))
                ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de toma no puede ser mayor que la de recepci&oacute;n.";
        }
        else
            ErroresM+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- La fecha de inicio de s&iacute;ntomas no puede ser mayor que la de toma de muestra.";
    }    

    if(conteo==0)
        ErroresA = "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Por favor ingrese al menos una al&iacute;cuota.";

    (ErroresP=="")? ErroresPaciente="": ErroresPaciente = ErroresPaciente+ErroresP + "<br/>";
    (ErroresM=="")? ErroresMuestra="": ErroresMuestra = ErroresMuestra+ErroresM + "<br/>";
    (ErroresA=="")? ErroresAlicuotas="": ErroresAlicuotas = ErroresAlicuotas+ErroresA + "<br/>";
    Message = ErroresPaciente + ErroresMuestra + ErroresAlicuotas;

    if(Message!="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#dSummaryErrors").css('display','none');
        $("#conteo").val(conteo);

        var param = '';
        for(a=0; a<conteo; a++)
        {
           param+=alicuotas[a][1]+'-';
        }
        param = param.substr(0, param.length-1);
        $("#alic").val(param);
        
        $("#tabla_alicuotas").val($("#alicuotasLista").html());
        $('#frmContenido').submit();
    }
}

function verificar()
{
    if($("#drpevento").val()!=0){
        if($("#evento_inicio_sintomas").val()=='1')
        {
            if(isDate(jQuery.trim($("#fecha_inicio_sintomas").val())))
            {
                semanaEpi = calcularSemana($("#fecha_inicio_sintomas").val());
                $("#semanaEpi").html(semanaEpi);
                $("#se").val(semanaEpi);
            }
            else
                $("#semanaEpi").html('0');
        }
        else
        {
            if(isDate(jQuery.trim($("#fecha_toma").val())))
            {
                semanaEpi = calcularSemana($("#fecha_toma").val());
                $("#semanaEpi").html(semanaEpi);
                $("#se").val(semanaEpi);
            }
            else
                $("#semanaEpi").html(0);
        }
    }
}

function agregarAlicuota()
{
    if($("#drpareaanalisis").val()==0 || $("#drpevento").val()==0 || $("#drptipomuestra").val()==0)
       alert("Por favor seleccione el \xE1rea de an\xe1lisis, evento y tipo de muestra en los Datos de la Muestra");
    else
    {
        if($("#area_alicuota").val()==0 || $("#evento_alicuota").val()==0)
            alert("Por favor seleccione el \xE1rea y el evento de la al\xedcuota");
        else
        {
            if(existeAlicuota($("#area_alicuota").val(), $("#evento_alicuota").val()))
                alert("La al\xedcuota que desea agregar ya se encuentra en la lista.")
            else
            {
                // Agrega una alicuota 
                var entrada = new Array(2);
                entrada[0] = $("#area_alicuota").val();
                entrada[1] = $("#evento_alicuota").val();
                alicuotas.push(entrada);

                var clasetd = ' class="dxgv" ';
                var clasetr = ' class="dxgvDataRow_PlasticBlue" ';

                var correlativo = $("#area_alicuota").val()+'-'+$("#evento_alicuota").val();
                var area = '<td '+clasetd+'>'+ $("#area_alicuota :selected").text()+'</td><input id="a'+correlativo+
                    '" type="hidden" value="'+ $("#area_alicuota").val()+'"/></td>';
                var evento = '<td '+clasetd+'>'+ $("#evento_alicuota :selected").text()+'</td><input id="e'+correlativo+
                    '" type="hidden" value="'+ $("#evento_alicuota").val()+'"/></td>';                

                var borrar = '<td '+clasetd+' align="center" style="width: 26px;">'
                    + '<a href="javascript:borrarAlicuota(\''+correlativo+'\')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Borrar alicuota">'
                    +'<span class="ui-icon ui-icon-trash"></span></a></td>';

                var fila = '<tr id="aR'+correlativo+'" '+ clasetr+'>' +area + evento + borrar+'</tr>';
                        $("#alicuotas > tbody").append(fila);

                conteo++;
                    if(conteo > 0)
                        $("#alicuotasLista").show();
            }
        }
    }
}

function borrarAlicuota(fila)
{   
    var retVal = confirm('\xbfBorrar esta al\xedcuota?');
    if(retVal)
    {
        $("#aR"+fila).remove();
        alicuotas.splice(indice(fila), 1);

        conteo--;
        if(conteo==0)
            $("#alicuotasLista").hide();
    }
}

function indice(fila)
{
    var index = 0;
    
    for(a=0; a<alicuotas.length; a++)
    {
        if(fila== alicuotas[a][0]+'-'+alicuotas[a][1])
        {
            index = a;
            break;
        }
    }
    return index;
}



function existeAlicuota(area,evento)
{
    var flag = false;
    
    for(a=0; a<alicuotas.length; a++)
    {
        if(alicuotas[a][0] == area && alicuotas[a][1]==evento)
        {
            flag = true;
            break;
        }
    }

    return flag;
}

function cargarEventosAlicuota()
{
        $.getJSON(urlprefix + 'js/dynamic/eventos_derivacion.php',{
            a:$("#drpareaanalisis").val(),
            e:$("#drpevento").val(),
            d:$("#area_alicuota").val(),
            t:$("#drptipomuestra").val(),
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">N/A</option>';

            for (var i = 0; i < j.length; i++){
                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#evento_alicuota").html(options);
        })
}

function borrarLista()
{
    $("#alicuotasLista").hide();
    for(var row in alicuotas)
       $("#aR"+alicuotas[row][0]+'-'+alicuotas[row][1]).remove();
    alicuotas = new Array();
    conteo = 0;
}