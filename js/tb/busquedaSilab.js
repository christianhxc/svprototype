$(document).ready(function() {
    //alert("test");
    $( "#dialog-form-silab" ).dialog({
        autoOpen: false,
        height: 750,
        width: 1000,
        modal: true,
        position: 'center',
        buttons: {
            Salir: function() {
                borrarTablaSilab();
                $( this ).dialog( "close" );
            }
        }
    });

    $( "#dialog-form-silab" ).bind("dialogclose",function(){
        borrarTablaSilab();
    });
    borrarTablaSilab();
//    borrarTabla();
//    busquedaUceti();
});

function clearSearchSilab()
{
    $('#formDialogSilab').each(function() {
        this.reset();
    });
}

function refrescarResultadosSilab(nuevaPag)
{
    if(nuevaPag >= '1' )
    {
        pagina = nuevaPag;
        buscarSilab();
    }
    
}


function borrarTablaSilab(){
    $("#resultadosBusquedaSilab").html('');
//$("#notFoundFilter").show();
}

function busquedaSilab()
{
    pagina = 1;
    buscarSilab();
}

function abrirFiltroSilab(){
    clearSearch();
    borrarTabla();
    $( "#dialog-form-silab" ).dialog("open");
}

function validarSilab(){
    pagina = 1;
    buscarSilab();
}

function buscarSilab()
{
    //alert($("#popcodSilab").val());
    $("#pErrors").hide();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/busquedaSilab.php',
        data: "id="+jQuery.trim($("#idSilab").val())
        + "&n="+jQuery.trim($("#nSilab").val()) 
        + "&p="+jQuery.trim($("#pSilab").val())
        + "&t="+jQuery.trim($("#tSilab").val())
        + "&pagina="+pagina,
        success: function(data)
        {
            //alert("hola");
            $("#resultadosBusquedaSilab").html(data);
        }
    });
}

function muestraSilab(idMuestra, tipoSilab, update){
    //alert(idMuestra);||
    var muestraSplit = new Array();
    var muestrasGuardadas = $("#globalMuestras").val();
    //    var busquedaSplit = new Array();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosMuestraSilab.php',
        data: "id_muestra="+idMuestra
        +"&tipo_silab="+tipoSilab,
        success: function(data)
        {
            var tmpReg = globalMuestras.length;
            var flag = false;
            for (var i=0; i<tmpReg; i++){
                if(globalMuestras[i][0] == idMuestra && globalMuestras[i][2] == tipoSilab)
                    flag = true;
            }
            //            alert(muestrasGuardadas);
            if(muestrasGuardadas.search(idMuestra) != -1)
                flag = true;
            
            //            busquedaSplit = muestrasGuardadas.split("###");
            if(!flag || update == 1){
                muestraSplit = data.split("###");
                if(muestraSplit[0]=="no"){
                    alert("Esta Muestra aun no esta procesada en el Laboratorio por favor intentelo mas tarde");
                    $("#dialog-form-silab").dialog('close');
                }
                else{
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth()+1; //January is 0!
                    var yyyy = today.getFullYear();
                    if(dd<10){
                        dd='0'+dd
                    }
                    if(mm<10){
                        mm='0'+mm
                    }
                    var d = new Date();
                    var curr_hour = d.getHours();
                    var curr_min = d.getMinutes();

                    today = yyyy+'-'+mm+'-'+dd;
                    $("#resultadosBusquedaSilab").html('');
                    $("#dialog-form-silab").dialog('close');
                    globalMuestras[tmpReg] = new Array(idMuestra,muestraSplit[0],tipoSilab);
                    globalMuestrasSilab[tmpReg] = muestraSplit[1];
                    globalPruebasSilab[tmpReg] = muestraSplit[2];
                    estadoSilab = '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de silab - Actualizada el '+today;
                    $("#estadoSilab").html(estadoSilab);
                    crearMuestras();
                }
            }
            else{
                alert ("Ya existe un registro con la misma muestra");
            }
            
        //alert(globalMuestras[tmpReg]);
        },
        error: function (data, status, e)
        {
        }
    });  
}

function crearMuestras()
{
    var data = "";
    var tmpReg = globalMuestras.length;
    for (var i=0; i<tmpReg; i++){
        data+= "<span style='color:#628529;font-weight : bold;'>Muestra "+(i+1) + "</span> " + globalMuestras[i][1];
    }
    $("#muestraResultadoSilabTmp").html(data);
    if(tmpReg == 0 && $("#globalMuestras").val() == ""){
        estadoSilab = '<img width=16 height=16 src="../img/iconos/pendiente.png"> Pendiente de muestra';
        $("#estadoSilab").html(estadoSilab);
    }
}

function verMuestra(idMuestra){
    $('#contenidoMuestra'+idMuestra).toggle();
    if( $('#contenidoMuestra'+idMuestra).css('display') == 'none'){
        $('#toggle'+idMuestra).html('Ver M&aacute;s');
    }
    else{
        $('#toggle'+idMuestra).html('Ver Menos');
    }
}

function borrarMuestra(idMuestra){
    var tmpReg = globalMuestras.length;
    for (var i=0; i<tmpReg; i++){
        if(globalMuestras[i][0] == idMuestra){
            if (confirm("\xbfEsta seguro de eliminar la muestra?")){
                globalMuestras.splice(i, 1);
                globalMuestrasSilab.splice(i, 1);
                globalPruebasSilab.splice(i, 1);
                break;
            }
        }
    }
    crearMuestras();
}

function borrarMuestraGuardada(idMuestra){
    var idUcetiForm = $("#id_uceti").val();
    var muestraSplit = "";
    if (confirm("\xbfEsta seguro de eliminar la muestra guardada?")){
        $.ajax({
            type: "POST",
            url: urlprefix + 'js/dynamic/borrarMuestraSilab.php',
            data: "id_muestra="+idMuestra+"&id_uceti="+idUcetiForm,
            success: function(data)
            {
                muestraSplit = data.split("###");
                $("#muestraResultadoSilab").html(muestraSplit[0]);
                $("#globalMuestras").val(muestraSplit[1]);
                if( $("#globalMuestras").val() == "" ){
                    crearMuestras();
                }
                $("#globalPruebas").val(muestraSplit[2]);
            }
        });
    }
    
}

function actualizarMuestraGuardada(idMuestra, tipoSilab){
    $("#muestraResultadoSilabTmp").html("");
    $("#muestraResultadoSilab").html("");
    estadoSilab = '<img width=16 height=16 src="../img/iconos/valido.png">Buscando Muestra en Silab, espere por favor...';
    $("#estadoSilab").html(estadoSilab);
    var idUcetiForm = $("#id_uceti").val();
    
    var muestraSplit = new Array();
    $.ajax({
        type: "POST",
        url: urlprefix + 'js/dynamic/datosMuestraSilab.php',
        data: "id_muestra="+idMuestra
        +"&tipo_silab="+tipoSilab,
        success: function(data)
        {
            var tmpReg = globalMuestras.length;
            
            muestraSplit = data.split("###");
            if(muestraSplit[0]=="no"){
                alert("Esta Muestra aun no esta procesada en el Laboratorio por favor intentelo mas tarde");
                $("#dialog-form-silab").dialog('close');
            }
            else{
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd
                }
                if(mm<10){
                    mm='0'+mm
                }
                var d = new Date();
                var curr_hour = d.getHours();
                var curr_min = d.getMinutes();

                today = yyyy+'-'+mm+'-'+dd;
                $("#resultadosBusquedaSilab").html('');
                $("#dialog-form-silab").dialog('close');
                globalMuestras[tmpReg] = new Array(idMuestra,muestraSplit[0],tipoSilab);
                globalMuestrasSilab[tmpReg] = muestraSplit[1];
                globalPruebasSilab[tmpReg] = muestraSplit[2];
                estadoSilab = '<img width=16 height=16 src="../img/iconos/valido.png">Actualizando muestra en el sistema, espere por favor - Fecha: '+today;
                $("#estadoSilab").html(estadoSilab);
               
                    
                $.ajax({
                    type: "POST",
                    url: urlprefix + 'js/dynamic/borrarMuestraSilab.php',
                    data: "id_muestra="+idMuestra+"&id_uceti="+idUcetiForm,
                    success: function(data)
                    {
                        //                        crearMuestras();
                        muestraSplit = data.split("###");
//                        $("#muestraResultadoSilab").html(muestraSplit[0]);
                        $("#globalMuestras").val(muestraSplit[1]);
                        if( $("#globalMuestras").val() == "" ){
                        //                            crearMuestras();
                        }
                        $("#globalPruebas").val(muestraSplit[2]);
                        guardadoPrevioUceti(1);                        
                    }
                });
            }
        },
        error: function (data, status, e)
        {
            alert("El sistema no ha podido actualizar la muestra, intentelo mas tarde");
        }
    });  
    
}