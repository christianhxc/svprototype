var globalGruposRelacionados = new Array();
var globalGruposDetalle = new Array();
var globalIdForm = 0;

$(document).ready(function() {
    $("#divDetalle").hide();  
    $("#divEsquemasRel").hide();
    $("#divDetalleRelacion").hide();
    $("#botonMultidosis").css( "display", "none" );
    if ($("#idForm" ).val() != ""){
        $("#divDetalle").show();
        globalIdForm = $("#idForm" ).val();
        $("#divDetalleRelacion").show();
        traerDetallesRelacionados();
    }
    $( "#nombre_un" ).autocomplete(urlprefix + "js/dynamic/unidadNotificadora_all.php",
    {
        delay:10,
        minChars:3,
        matchSubset:1,
        matchContains:1,
        cacheLength:10,
        onItemSelect:function(li){
            $("#nombre_un").val(li.selectValue);
            $("#id_un").val(li.extra[0]);
        },
        autoFill:false
    });
    var idProvincia = $("#idPro").val();
    var idRegion = $("#idReg").val();
    if (idProvincia !== "0" & idRegion !== "0")
        setRegionPersona(idProvincia, idRegion);
    setNivel();
    $("#btnDetalleAct").hide();
});

function setNivel(){
    var nivelLoad = $("#idNivel").val();
    if (nivelLoad !== null && nivelLoad !== ""){
        $("#drpNivel").val(nivelLoad);
        $("#idNivel").val("");
    }
    var nivel = parseInt($("#drpNivel").val());
    if (nivel>1 || nivelLoad > 1){
        switch (nivel){
            case 2: 
                $("#divPolitica1").show();
                $("#divPolitica2").show();
                $("#divPolitica3").hide();
                $("#divPolitica4").hide();
                $("#divPolitica5").hide();
                $("#divPolitica6").hide();
                $("#divPolitica7").hide();
                $("#divPolitica8").hide();
                $("#divPolitica9").hide();
                $("#drpPro").val(0);
                $("#drpReg").val(0);
                $("#drpDis").val(0);
                $("#drpCor").val(0);
                $("#nombre_un").val("");
                $("#id_un").val("");
            break;
            case 3: 
                $("#divPolitica1").show();
                $("#divPolitica2").show();
                $("#divPolitica3").show();
                $("#divPolitica4").show();
                $("#divPolitica5").hide();
                $("#divPolitica6").hide();
                $("#divPolitica7").hide();
                $("#divPolitica8").hide();
                $("#divPolitica9").hide();
                $("#drpReg").val(0);
                $("#drpDis").val(0);
                $("#drpCor").val(0);
                $("#nombre_un").val("");
                $("#id_un").val("");
            break;
            case 4: 
                $("#divPolitica1").show();
                $("#divPolitica2").show();
                $("#divPolitica3").show();
                $("#divPolitica4").show();
                $("#divPolitica5").show();
                $("#divPolitica6").show();
                $("#divPolitica7").hide();
                $("#divPolitica8").hide();
                $("#divPolitica9").hide();
                $("#drpDis").val(0);
                $("#drpCor").val(0);
                $("#nombre_un").val("");
                $("#id_un").val("");
            break;
            case 5: 
                $("#divPolitica1").show();
                $("#divPolitica2").show();
                $("#divPolitica3").show();
                $("#divPolitica4").show();
                $("#divPolitica5").show();
                $("#divPolitica6").show();
                $("#divPolitica7").show();
                $("#divPolitica8").show();
                $("#divPolitica9").hide();
                $("#drpCor").val(0);
                $("#nombre_un").val("");
                $("#id_un").val("");
            break;
            case 6: 
                $("#divPolitica1").hide();
                $("#divPolitica2").hide();
                $("#divPolitica3").hide();
                $("#divPolitica4").hide();
                $("#divPolitica5").hide();
                $("#divPolitica6").hide();
                $("#divPolitica7").hide();
                $("#divPolitica8").hide();
                $("#divPolitica9").show();
                $("#drpPro").val(0);
                $("#drpReg").val(0);
                $("#drpDis").val(0);
                $("#drpCor").val(0);
            break;
        }
    }
    else{
        $("#divPolitica1").hide();
        $("#divPolitica2").hide();
        $("#divPolitica3").hide();
        $("#divPolitica4").hide();
        $("#divPolitica5").hide();
        $("#divPolitica6").hide();
        $("#divPolitica7").hide();
        $("#divPolitica8").hide();
        $("#divPolitica9").hide();
        $("#drpPro").val(0);
        $("#drpReg").val(0);
        $("#drpDis").val(0);
        $("#drpCor").val(0);
        $("#nombre_un").val("");
        $("#id_un").val("");
    }
}

function sumCasosH(){
    var suma = 0;
    for (var i=1;i<18;i++){
        if ($("#homRango"+i).val()!=="")
            suma = (parseInt(suma)+parseInt($("#homRango"+i).val()));
    }
    $("#homRango18").val(suma)
}

function sumCasosM(){
    var suma = 0;
    for (var i=1;i<18;i++){
        if ($("#mujRango"+i).val()!=="")
            suma = (parseInt(suma)+parseInt($("#mujRango"+i).val()));
    }
    $("#mujRango18").val(suma)
}

function llenarGrupos(){
    if ($("#globalGruposRelacionados" ).val() !== ""){
        var grupos = $("#globalGruposRelacionados" ).val().split("###");
        for(var i=0; i<grupos.length;i++){
            var grupo = grupos[i].split("#-#");
            llenarGrupo(grupo);
        }
        crearTablaGrupos();
    }
}

function llenarGrupo(grupo){
    var idGrupo = grupo[0];
    var nombreGrupo = grupo[1];
    var numHombre = grupo[2];
    var numMujer = grupo[3];
    if (idGrupo !== ""){
        var tmpReg = globalGruposRelacionados.length;
        idGrupo = (tmpReg === 0) ? idGrupo : "###"+idGrupo;
        globalGruposRelacionados[tmpReg] = new Array(idGrupo,nombreGrupo,numHombre, numMujer);
    }
}

function relacionarGrupos(){
    var idGrupo = $("#drpGrupo").val();
    var nombreGrupo = $("#drpGrupo").find(":selected").text();
    var numHombre = $("#homGrupo").val();
    var numMujer = $("#mujGrupo").val();
    
    if (idGrupo !== 0 && numHombre !== "" && numMujer !== ""){
        
        var tmpReg = globalGruposRelacionados.length;
        var flag = true;
        for (var i=0; i<tmpReg; i++){
            if (idGrupo === globalGruposRelacionados[i][0] || "###"+idGrupo === globalGruposRelacionados[i][0])
                flag = false;
        }
        if (flag){
            idGrupo = (tmpReg === 0) ? idGrupo : "###"+idGrupo;
            globalGruposRelacionados[tmpReg] = new Array(idGrupo, nombreGrupo, numHombre, numMujer);
            $("#drpGrupo").val(0);
            $("#homGrupo").val("");
            $("#mujGrupo").val("");
            crearTablaGrupos();
        }
        else{
            alert ("Ya existe un registro para esta relacion");
            $("#drpGrupo").val(0);
        }
    }
    else
        alert("Debe seleccionar un grupo e ingresar los datos para hombres y mujeres, si no aplica ingresar 0");
}

function crearTablaGrupos(){
    var tabla = '<table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="60%" align="center">'+
    '<tr>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Grupo Especial</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Hombres</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Mujeres</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Eliminar</th>'+
    '</tr>';
    for(var i=0; i<globalGruposRelacionados.length;i++){
        if(__isset(globalGruposRelacionados[i])){
            tabla += '<tr>'+
            '<td class="fila" width="200px">'+globalGruposRelacionados[i][1]+'</th>'+
            '<td class="fila" width="60px">'+globalGruposRelacionados[i][2]+'</th>'+
            '<td class="fila" width="60px">'+globalGruposRelacionados[i][3]+'</th>'+
            '<td class="fila" width="40px" align="center"><a href="javascript:eliminarGrupos('+i+')"><img src="'+urlprefix+'/img/Delete.png" title="Eliminar" border="0"/></a></th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#divTablaGrupos").html(tabla);
}

function eliminarGrupos(pos){
    if (confirm("Esta seguro de eliminar esta relacion")){
        globalGruposRelacionados.splice(pos, 1);
        crearTablaGrupos();
    }
}

function validarDenominador(){
    var Message = '';
    var Errores = '';
    var ErrorEnc='&nbsp; &nbsp;&nbsp; &nbsp;Encabezado:';
    //Encabezado
    var nivel = parseInt($("#drpNivel").val());
    if (nivel === 0)
        Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el Nivel."; 
    if (nivel > 1) {
        switch (nivel){
            case 2: 
                if($("#drpPro").val() === 0)
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Provincia.";
                break;
            case 3: 
                if($("#drpReg").val() === 0)
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar la Region de salud.";
                break;
            case 4: 
                if($("#drpDis").val() === 0)
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el Distrito.";
                break;
            case 5: 
                if($("#drpCor").val() === 0)
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el Corregimiento.";
                break;
            case 6: 
                if($("#id_un").val() === "")
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar la Instalacion de salud.";
                break;
        }
    }
    
    if (Errores==="")
        ErrorEnc="";
    else
        ErrorEnc = ErrorEnc+Errores + "<br/>";
    Message = Errores;
    if(Message!=="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#dSummaryErrors").css('display','none');
        var nuevo = '';
        if($('#action').val()==='M'){
            nuevo = 'A continuaci\xf3n se editar\xe1n los datos del Esquema, \xbfdesea continuar?';
        }
        else
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos del Esquema, \xbfdesea continuar?';
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmContenido').submit();
        }
    }
}

function validarDenominador_file(){
    var Message = '';
    var Errores = '';
    var ErrorEnc='&nbsp; &nbsp;&nbsp; &nbsp;Encabezado:';
    //Encabezado
    
    if($("#flArchivo").val() == "")
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe seleccionar el archivo.";
                
    if($("#anio_deno").val() == "")
                    Errores+= "<br/>&nbsp; &nbsp;&nbsp; &nbsp;- Debe ingresar el año del denominador.";
                
     
    if (Errores==="")
        ErrorEnc="";
    else
        ErrorEnc = ErrorEnc+Errores + "<br/>";
    Message = Errores;
    if(Message!=="")
    {
        $('#dSummaryErrors').show();
        $("#ErrorGuardar").css('display','none');
        $('#pSummaryErrors').html(Message);
    }
    else
    {
        $("#dSummaryErrors").css('display','none');
        var nuevo = '';
            
            nuevo = 'A continuaci\xf3n se ingresar\xe1 los datos de los denominadores por archivo, \xbfdesea continuar?';
            
        if(confirm(nuevo)){
            $("#dSummaryErrors").css('display','none');
            $('#frmFile').submit();
        }
    }
}

function grabarDetalle(){
     var idForm = $("#idForm").val();
     funGrabarDetalle(idForm, "si");
 }
 
 function actDetalle(){
     var idForm = $("#idForm").val();
     funGrabarDetalle(idForm, "no");
 }
 
 function procesarError(codigo){
    if (codigo === 602)
        alert("La p\xe1gina no responde, intentelo de nuevo mas tarde");
    else if (codigo === 404)
        alert("No se encontr\xf3 la p\xe1gina solicitada");
    else
        alert("hubo un error, codigo: "+codigo);
}

function funGrabarDetalle(idForm, nuevo){
    var grupos = '';
    for (var i=1; i<= 18; i++ ){
        if ($("#homRango"+i).val()=== "")
            $("#homRango"+i).val("0");
        if ($("#mujRango"+i).val()=== "")
            $("#mujRango"+i).val("0");
    }
    var casosHombre = $("#homRango1").val()+"##"+$("#homRango2").val()+"##"+$("#homRango3").val()+"##"+$("#homRango4").val()+"##"+$("#homRango5").val()+"##"+$("#homRango6").val()+"##"+
        $("#homRango7").val()+"##"+$("#homRango8").val()+"##"+$("#homRango9").val()+"##"+$("#homRango10").val()+"##"+$("#homRango11").val()+"##"+$("#homRango12").val()+"##"+
        $("#homRango13").val()+"##"+$("#homRango14").val()+"##"+$("#homRango15").val()+"##"+$("#homRango16").val()+"##"+$("#homRango17").val()+"##"+$("#homRango18").val();
    var casosMujer = $("#mujRango1").val()+"##"+$("#mujRango2").val()+"##"+$("#mujRango3").val()+"##"+$("#mujRango4").val()+"##"+$("#mujRango5").val()+"##"+$("#mujRango6").val()+"##"+
        $("#mujRango7").val()+"##"+$("#mujRango8").val()+"##"+$("#mujRango9").val()+"##"+$("#mujRango10").val()+"##"+$("#mujRango11").val()+"##"+$("#mujRango12").val()+"##"+
        $("#mujRango13").val()+"##"+$("#mujRango14").val()+"##"+$("#mujRango15").val()+"##"+$("#mujRango16").val()+"##"+$("#mujRango17").val()+"##"+$("#mujRango18").val();

    var valores = "casosHombre="+casosHombre+"&casosMujer="+casosMujer;

    for(var i=0; i<globalGruposRelacionados.length; i++){
        if(__isset(globalGruposRelacionados[i])){
            grupos+=globalGruposRelacionados[i][0]+"#-#"+globalGruposRelacionados[i][2]+"#-#"+globalGruposRelacionados[i][3];//Sintomas y fecha
        }
    }
    if (grupos !== "" )
        valores +=  "&grupos="+grupos;
    
    valores += "&idForm="+idForm;

    if (nuevo==="si"){
        pedirAJAX(urlprefix +"vacunas/DenominadorDetalle.php", traerDetallesRelacionados, {timeout: 20,
                                                                                    metodo: metodoHTTP.POST,
                                                                                    parametrosPOST: valores,
                                                                                    cartelCargando: "divCargando",
                                                                                    cache: false,
                                                                                    onerror: procesarError});
    }
    else{
        valores += "&eliminar=si";
        pedirAJAX(urlprefix +"vacunas/DenominadorDetalle.php", traerDetallesRelacionados, {timeout: 20,
                                                                                        metodo: metodoHTTP.POST,
                                                                                        parametrosPOST: valores,
                                                                                        cartelCargando: "divCargando",
                                                                                        cache: false,
                                                                                        onerror: procesarError});
    }
}

function traerDetallesRelacionados(){
    var idForm = $("#idForm").val();
    if (idForm !== ""){
        variablesBlanco();
        var valores = "idForm="+idForm;
        pedirAJAX(urlprefix +"vacunas/DenominadorDetalleDatos.php", recibirTraerDetallesRelacionados, {timeout: 20,
                                                                        metodo: metodoHTTP.POST,
                                                                        parametrosPOST: valores,
                                                                        cartelCargando: "divCargando",
                                                                        cache: false,
                                                                        onerror: procesarError});
    }
}

function recibirTraerDetallesRelacionados(texto){
    var denoTablas = texto.split("***");
    if(denoTablas[0] !== ""){
        var grupos = denoTablas[0].split("%%%");
        for (var i=0; i<grupos.length; i++){
            mostrarDetalleGrupos(grupos[i]);
        }
    }
    else
        $("#tablaDetalleGrupos").html("");
    globalGruposDetalle = new Array();
    mostrarDetalleRangos(denoTablas[1], denoTablas[2]);
}

function mostrarDetalleGrupos(grupos){
    var campos = grupos.split("###");
    var tmpReg = globalGruposDetalle.length;
    globalGruposDetalle[tmpReg] = new Array(campos[0], campos[1], campos[2]);
    var tabla = 'Grupos Especiales:<br/><table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="40%" align="center">'+
    '<tr>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Grupo Especial</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Hombres</th>'+
    '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Mujeres</th>'+
    '</tr>';
    for(var i=0; i<globalGruposDetalle.length;i++){
        if(__isset(globalGruposDetalle[i])){
            tabla += '<tr>'+
            '<td class="fila">'+globalGruposDetalle[i][0]+'</th>'+
            '<td class="fila" style="text-align:center" >'+globalGruposDetalle[i][1]+'</th>'+
            '<td class="fila" style="text-align:center" >'+globalGruposDetalle[i][2]+'</th>'+
            '<tr>';
        }
    }
    tabla += "</table>";
    $("#tablaDetalleGrupos").html(tabla);
}

function mostrarDetalleRangos(hombres, mujeres){
    var casosHombres = hombres.split("###");
    var casosMujeres = mujeres.split("###");
    var tabla = 'Grupos por Quinquenios de Edad:<br/><table id="fdg_1" cellspacing="1" cellpadding="4" border="0" width="40%" align="center">'+
    '<tr>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">Sexo</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">< de 1</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">01-04</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">05-09</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">10-14</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">15-19</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">20-24</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">25-29</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">30-34</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">35-39</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">40-44</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">45-49</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">50-54</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">55-59</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">60-64</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">65-69</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">70-74</th>'+
        '<th style="text-align:center" class="dxgvHeader_PlasticBlue">75 o +</th>'+
    '</tr>';
    tabla += '<tr>'+
    '<td >Hombre</th>';
    for(var i=0; i<casosHombres.length;i++){
        tabla += '<td style="text-align:right">'+casosHombres[i]+'</td>'
    }
    tabla += '</tr>';
    tabla += '<tr>'+
    '<td >Mujer</th>';
    for(var i=0; i<casosMujeres.length;i++){
        tabla += '<td style="text-align:right">'+casosMujeres[i]+'</td>'
    }
    tabla += '</tr>';
    tabla += "</table>";
    $("#tablaDetalleRangos").html(tabla);
}

 
function variablesBlanco(){
    for (var i=1; i<= 18; i++ ){
        $("#homRango"+i).val("");
        $("#mujRango"+i).val("");
    }
    $("#divTablaGrupos").html("");
    globalGruposDetalle = new Array();
    globalGruposRelacionados = new Array();
}

function eliminarVacuna(esquemaId, vacId){
    var res = confirm("Estas seguro que quieres eliminar el registro");
    if (res){
        var valores = "esquemaId="+esquemaId+"&vacId="+vacId;
        pedirAJAX(urlprefix +"vacunas/VacunaEliminar.php", traerDetallesRelacionados, {timeout: 20,
                                                                    metodo: metodoHTTP.POST,
                                                                    parametrosPOST: valores,
                                                                    cartelCargando: "divCargando",
                                                                    cache: false,
                                                                    onerror: procesarError});
    }
}

function setRegionCascada(){
    setRegionPersona($("#drpPro").val(),-1);
}

function setRegionPersona(idProvincia, idRegion)
{
    if (idProvincia !== "" && idRegion !== ""){
        $.getJSON(urlprefix + 'js/dynamic/regiones.php',{
            idProvincia: idProvincia,
            ajax: 'true'
        }, function(j){
            var options = '';
            options += '<option value="0">Seleccione...</option>';
            var extra='';
            for (var i = 0; i < j.length; i++)
            {
                if(j[i].optionValue != idRegion)
                    extra = '';
                else
                    extra='selected="selected"';
                options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
            }

            $("#drpReg").html(options);
            if ($("#idReg").val()!=""){
                $("#drpReg").val($("#idReg").val());
                setDistritoCascada();
            }
        })
    }
}

function setDistritoCascada(){
    setDistritoPersona($("#drpPro").val(),$("#drpReg").val(),-1);
}

function setDistritoPersona(idProvincia, idRegion, idDistrito)
{
    $.getJSON(urlprefix + 'js/dynamic/distritos.php',{
        idProvincia: idProvincia,
        idRegion:idRegion,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idDistrito)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }

        $("#drpDis").html(options);
        if ($("#idDis").val()!=""){
            $("#drpDis").val($("#idDis").val());
            setCorregimientoCascada();
        }
    })
}

function setCorregimientoCascada(){
    setCorregimientoPersona($("#drpDis").val(),-1);
}

function setCorregimientoPersona(idDistrito, idCorregimiento)
{
    $.getJSON(urlprefix + 'js/dynamic/corregimientos.php',{
        idDistrito: idDistrito,
        ajax: 'true'
    }, function(j){
        var options = '';
        options += '<option value="0">Seleccione...</option>';
        var extra='';
        for (var i = 0; i < j.length; i++)
        {
            if(j[i].optionValue != idCorregimiento)
                extra = '';
            else
                extra='selected="selected"';
            options += '<option value="' + j[i].optionValue + '" '+extra+'>' + j[i].optionDisplay + '</option>';
        }

        $("#drpCor").html(options);
        if ($("#idCor").val()!=""){
            $("#drpCor").val($("#idCor").val());
        }
    })
}