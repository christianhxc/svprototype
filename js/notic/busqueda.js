$(document).ready(function() {
    borrarTabla();
    busquedaFormulario();
});

function clearSearch()
{
    $('#formDialog').each(function() {
        this.reset();
    });
}

function refrescarResultados(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        validarFormulario();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaFormulario()
{
    pagina = 1;
    validarFormulario();
}


function validarFormulario()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarFormulario();
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
            buscarFormulario();
    }
}

function buscarFormulario()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/notic/busquedaNotic.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarFormulario(idFormulario){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idFormulario+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idForm=' + idFormulario );
    }
}

function reporteIndividual(idFormulario){
    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idFormulario+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.open ('http://52.33.93.215:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/public/SISVIG/PRD/NOTIC/formulario_individual&P_ID_FORMULARIO='+idFormulario+'&j_username=jasURL&j_password=jasURLMinsa&output=pdf');
    }
}