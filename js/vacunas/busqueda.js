var pagina = 1; 

$(document).ready(function() {
    borrarTabla();
    busquedaEsquemas();
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
        validarEsquemas();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaEsquemas()
{
    $("#resultadosBusqueda").html("");
    $("#pErrors").hide();
    
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/vacunas/busquedaEsquemas.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarEsquema(idForm){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Escenario N. '+idForm+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idForm=' + idForm );
    }
}

//function reporteIndividual(idForm){
//    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idVicIts+', \xbfdesea continuar?';
//    if(confirm(mensaje)){
//        window.location = urlprefix+'reportes/vicits/ReporteIndividualVicits.php?idVicitsForm=' + idVicIts+'&s='+sexo;
//    }
//}