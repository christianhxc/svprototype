$(document).ready(function() {
    borrarTabla();
    busquedaVicItsLab();
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

function busquedaVicItsLab()
{
    pagina = 1;
    validarVicItsLab();
}


function validarVicItsLab()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarVicItsLab();
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
            buscarVicItsLab();
    }
}

function buscarVicItsLab()
{
    $("#resultadosBusqueda").html("");
    $("#pErrors").hide();
    
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/vicits/busquedaVicItsLab.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarVicItsLab(idVicItsLab){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idVicItsLab+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('form_laboratorio.php?action=D&idVicItsLab=' + idVicItsLab );
    }
}

function reporteIndividualLab(idVicItsLab){
    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idVicItsLab+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location = urlprefix+'reportes/vicIts/ReporteIndividualVicIts.php?idVicItsLabForm=' + idVicItsLab;
    }
}