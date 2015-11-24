var pagina = 1;

$(document).ready(function() {
    borrarTabla();
    Tooltip_field();
    memorySearch();
    
    
});

function getUrlParams(url) {
    var params = {};
    url.substring(1).replace(/[?&]+([^=&]+)=([^&]*)/gi,
            function (str, key, value) {
                 params[key] = value;
            });
    return params;
}

function memorySearch (){
    var url = window.location.href;
    var params = getUrlParams(url); 

     var search= params["search"];
     var pag= params["pag"];
     
        
     if (search!="" && typeof search != 'undefined' && pag!="" &&  typeof pag != 'undefined')
     {
         $("#filtro").val(search);
        pagina=pag;
        buscartb();
        return;
     }
     
    if (pag!="1" && pag!="" &&  typeof pag != 'undefined')
     {
        pagina=pag;
        buscartb();
        return;
     }
     
     busquedatb();
    
}

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
        validartb();
    }
    
}


function borrarTabla(){
    $("#resultadosBusqueda").html('');
//$("#notFoundFilter").show();
}

function busquedatb()
{
    pagina = 1;
    validartb();
}


function validartb()
{
    buscartb();
}

function buscartb()
{
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/tb/busquedatb.php',
        data: "filtro="+jQuery.trim($("#filtro").val())+"&pagina="+pagina,
        success: function(data)
        {
            $("#resultadosBusqueda").html(data);
        }
    });
}

function cargarregistro()
{
    if ($("#reg").val()) {
        window.location.assign("formulario.php?action=R&reg=" + $("#reg").val());
    } else
    {
        alert ("Ingrese su número de registro");
    }
    
}

function borrarUceti(idUceti){
    var mensaje = 'A continuaci\xf3n se borrara los datos del Formulario N. '+idUceti+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.location.replace('formulario.php?action=D&idUceti=' + idUceti );
    }
}

function reporteUceti(idUceti){
    var mensaje = 'A continuaci\xf3n se descargara los datos del Formulario N. '+idUceti+', \xbfdesea continuar?';
    if(confirm(mensaje)){
        window.open('../reportes/uceti/ReporteIndividualPDF.php?idUceti=' + idUceti,'_blank');
    //window.open('','_blank')
    }
}