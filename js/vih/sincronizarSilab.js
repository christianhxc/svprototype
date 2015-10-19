var globalCargaDatos = 0;

function validarTraerDatos(){
    var mensaje = 'A continuaci\xf3n se cargaran los datos desde SILAB, \xbfdesea continuar?';
    if(confirm(mensaje)){
        if (globalCargaDatos == 0){
            globalCargaDatos = 1;
            $("#resultadosSincronizar").html("<img alt='cargando' src='"+urlprefix+"img/cargando.gif' /> Cargando..."); //loading gif will be overwrited when ajax have success
            $("#resultadosSincronizar").load(
                $.ajax({
                type: "POST",
                url: urlprefix + 'js/dynamic/silabVih/cargarDatosVihSilab.php',
                success: function(data)
                {
                    var total = new Array();
                    total = data.split("$$$");
                    var mensaje = '<fieldset class="ui-widget ui-widget-content ui-corner-all">\n\
                                    <legend>Resultado</legend>\n\
                                    <p style="width:70%; text-align: left;"> <br/> Resultados: <br/> ';
                    if (total[0] == -1)
                        mensaje += "- Se produjo un error y no se pudo conectar con la base de datos";
                    else if (total[0] > 0){
                        mensaje += "- Se cargaron "+total[0]+" registros de muestras desde la base de datos de SILAB";
                        mensaje += "<br/>- Se cargaron "+total[1]+" registros de Factores de Riesgo desde la base de datos de SILAB";
                    }
                    else
                        mensaje += "- No se encontro ningun registro para cargar desde SILAB";
                    mensaje += "<br/> </p> </fieldset> ";

                    $("#resultadosSincronizar").html(mensaje);
                }
            }));    
        }
        else
            alert('Ya se cargaron los datos desde SILAB, por favor espere mientras arrojan los resultados, este proceso se puede demorar un poco');
    }
}

function validarSincronizar(){
    if (globalCargaDatos == 1){
        globalCargaDatos = 2;
        var mensaje = 'A continuaci\xf3n se sincronizaran los datos desde SILAB, \xbfdesea continuar?';
        if(confirm(mensaje)){
            $("#resultadosSincronizar").html("<img alt='cargando' src='"+urlprefix+"img/cargando.gif' /> Cargando..."); //loading gif will be overwrited when ajax have success
            $("#resultadosSincronizar").load(
                $.ajax({
                type: "POST",
                url: urlprefix + 'js/dynamic/silabVih/sincronizarDatosSilabSisvig.php',
                success: function(data)
                {
                    var total = new Array();
                    total = data.split("$$$");
                    var mensaje = '<fieldset class="ui-widget ui-widget-content ui-corner-all">\n\
                                    <legend>Resultado</legend>\n\
                                    <p style="width:80%; text-align: left;"> <br/> Resultados: <br/>';
                    if (total[0] == -1)
                        mensaje += "- Se produjo un error y no se pudo conectar con la base de datos";
                    else if (total[0] > 0){
                        mensaje += "- El total de los registros de muestras de SILAB es: <strong>"+total[0]+"</strong>";
                        mensaje += "<br/>- De ese total se procesaron <strong>"+total[1]+"</strong> registros de personas con VIH que se encontraban registradas en SILAB pero no en SISVIG";
                        if (total[1] == 0)
                            mensaje += "<br/>- <strong style='color:green;'>Todos los registros que se encontraban en SILAB ya estan cargados a SISVIG</strong>";
                        else{
                            var formRepetidos = total[1]-total[3];
                            mensaje += "<br/>- Del total de registros procesados habia un total de <strong>"+formRepetidos+"</strong> registros repetidos, un persona puede tener varias muestras de VIH positivas en SILAB, pero solo un formulario en SISVIG";
                            mensaje += "<br/>- Del total de los registros ingresados desde SILAB, el numero de personas nuevas es: <strong>"+total[2]+"</strong>";
                            var numPersonasExistentes = total[1]-total[2];
                            mensaje += "<br/>- Un total de <strong>"+numPersonasExistentes+"</strong> personas ya se encontraban en SISVIG pero en otros formularios";
                        }
                    }
                    else
                        mensaje += "- No se encontro ningun registro para cargar desde SILAB";
                    mensaje += "<br/> </p> </fieldset> ";

                    $("#resultadosSincronizar").html(mensaje);
                }
            }));   
        }
    }
    else
        alert('Primero debe cargar los datos desde SILAB (el punto 1)');
}

function validarSincronizarFactor(){
    if (globalCargaDatos == 2){
        var mensaje = 'A continuaci\xf3n se sincronizaran los factores de riesgo desde SILAB, \xbfdesea continuar?';
        if(confirm(mensaje)){
            $("#resultadosSincronizar").html("<img alt='cargando' src='"+urlprefix+"img/cargando.gif' /> Cargando..."); //loading gif will be overwrited when ajax have success
            $("#resultadosSincronizar").load(
                $.ajax({
                type: "POST",
                url: urlprefix + 'js/dynamic/silabVih/sincronizarFactoresSilab.php',
                success: function(data)
                {
                    var total = new Array();
                    total = data.split("$$$");
                    var mensaje = '<fieldset class="ui-widget ui-widget-content ui-corner-all">\n\
                                    <legend>Resultado</legend>\n\
                                    <p style="width:70%; text-align: left;"> <br/> Resultados: <br/> ';
                    if (total[0] == -1)
                        mensaje += "- Se produjo un error y no se pudo conectar con la base de datos";
                    else if (total[0] > 0){
                        mensaje += "- El total de los registros de factores de riesgo de SILAB es: <strong>"+total[0]+"</strong>";
                        mensaje += "<br/>- De ese total se cargaron <strong>"+total[1]+"</strong> factores de riesgo a los formualrios de VIH/SIDA de SISVIG";
                        if (total[2]!=0)
                            mensaje += "<br/>- Durante el proceso se presentaron algunos errores sin importancia: "+total[2];
                        mensaje += "<br/>- <strong>NOTA: Las tablas temporales de SILAB se borraron</strong>";
                        
                        
                        globalCargaDatos = 0;
                    }
                    else
                        mensaje += "- No se encontro ningun registro para cargar desde SILAB";
                    mensaje += "<br/> </p> </fieldset> ";

                    $("#resultadosSincronizar").html(mensaje);
                }
            }));   
        }
    }
    else
        alert('Primero debe procesar los datos que llegan desde SILAB (El punto 2)');
}