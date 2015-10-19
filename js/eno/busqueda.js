$(document).ready(function() {
    borrarTabla();
    busquedaEno();
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
        validarEno();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedaEno()
{
    pagina = 1;
    validarEno();
}


function validarEno()
{
//    if(jQuery.trim($("#ed").val())=="" || jQuery.trim($("#ed2").val())=="")
//        buscarEno();
//    else
//    {
//        var ed1 = parseInt($("#ed").val());
//        var ed2 = parseInt($("#ed2").val());
//
//        if(ed1 > ed2)
//        {
//            $("#pErrors").show();
//            $("#pDetalle").html("La edad desde no debe ser mayor que la edad hasta.")
//        }
//        else
//            buscarEno();
//    }
    buscarEno();
}

function buscarEno()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/eno/busquedaEno.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+"&B="+$("#permisoBorrar").val()+"&R="+$("#permisoReporte").val(),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrarEno(idEno){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idEno+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idform=' + idEno );
    }
}

function reporteIndividual(idEno){
//    var mensaje = 'A continuaci\xf3n se mostrara el reporte individual del Formulario N. '+idEno+', \xbfdesea continuar?';
//    if(confirm(mensaje)){
//        window.location = urlprefix+'reportes/eno/ReporteIndividualEno.php?idEnoForm=' + idEno;
//    }
}