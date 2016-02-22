$(document).ready(function() {
    borrarTabla();
    busqueda();
    Tooltip_field();
});

function Tooltip_field(){
    $('#filtro').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Esta palabra puede ser nombre de la instalaci&oacute;n de salud, regi&oacute;n, diagn&oacute;stico, identificaci&oacute;n del paciente o la semana epidemiol&oacute;gica.',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    $('#drpHospital').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Al seleccionar una opci&oacute;n en Hospital filtrar&aacute; la informaci&oacute;n con la opci&oacute;n escogida',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    
    
    
    
    $('#drpUceti').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Al seleccionar una opci&oacute;n en Formularios filtrar&aacute; la informaci&oacute;n con las opciones Pendientes, Finalizados o Todos',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
    $('#drpSilab').qtip({
        position: {
            my: 'bottom left',  // Position my top left...
            at: 'top right' // at the bottom right of...            
        },
        style: {
            classes: 'qtip-green',
            tip: true
        },
        content: 'Al seleccionar una opci&oacute;n en Muestras Lab. Silab filtrar&aacute; la informaci&oacute;n con las opciones Pendientes, Confirmadas o Todas',
        show: 'mouseover',
        hide: 'mouseout'
    }); 
}

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
        validar();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busqueda()
{
    pagina = 1;
    validar();
}


function validar()
{
    buscar();
}

function buscar()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/LDBI/busquedaLdbiNotas.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina+
            ($("#status").val() ? "&status="+$("#status").val() : "") +
            ($("#anio").val() ? "&anio="+$("#anio").val() : ""),
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function borrar(id, texto){
    var mensaje = 'A continuaci\xf3n se borrara los datos de la Nota de Ajuste N. '+texto+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('nota.php?action=D&id=' + id);
    }
}

function reporteUceti(idUceti){
    var mensaje = 'A continuaci\xf3n se descargara los datos del Formulario N. '+idUceti+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        //window.open('../reportes/uceti/ReporteIndividualPDF.php?idUceti=' + idUceti,'_blank');
        window.location = 'http://190.34.154.87:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/public/SISVIG/PRD/VICFLU/Reporte_Individual_FLU&P_ID_FORMULARIO='+idFormulario+'&j_username=jasURL&j_password=jasURLMinsa&output=pdf';
    //window.open('','_blank')
    }
}

function exportar(id){
    window.open('pdf/nota.php?id='+id,'_blank');
}