$(document).ready(function() {
    borrarTabla();
    busquedaVih();
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
        validarVih();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaVih()
{
    pagina = 1;
    validarVih();
}


function validarVih()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarVih();
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
            buscarVih();
    }
}

function buscarVih()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/busquedaVih.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarVih(idVih){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idVih+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idVih=' + idVih );
    }
}

function reporteIndividual(idVih){
    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idVih+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location = urlprefix+'reportes/vih/ReporteIndividualVih.php?idVihForm=' + idVih;
    }
}