$(document).ready(function() {
    borrarTabla();
    busquedaVicIts();
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
        validarVicIts();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaVicIts()
{
    pagina = 1;
    validarVicIts();
}


function validarVicIts()
{
    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
        buscarVicIts();
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
            buscarVicIts();
    }
}

function buscarVicIts()
{
    $("#resultadosBusqueda").html("");
    $("#pErrors").hide();
    
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/vicits/busquedaVicIts.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarVicIts(idVicIts){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idVicIts+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idVicIts=' + idVicIts );
    }
}

function reporteIndividual(idVicIts,sexo){
    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idVicIts+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location = urlprefix+'reportes/vicits/ReporteIndividualVicits.php?idVicitsForm=' + idVicIts+'&s='+sexo;
    }
}