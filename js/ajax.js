//Enumeracion
var tipoRetorno = {
    TEXTO: 1,
    JSON: 2,
    XML: 3
}

var metodoHTTP = {
    GET: 1,
    POST: 2,
    POSTBinario : 3 //TODO: Manejar uploads.
}

var cacheAJAX = [];

function obtenerXHR(){
    var req = false;
    if (window.XMLHttpRequest){
        //Instanciacion en objeto nativo - Opera / firefo /safari / IE 7.o
        req = new XMLHttpRequest();
    }
    else {
        if (ActiveXObject){
            //Instanciarlo como Internet Explorer
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e){
                try{
                    req = new ActiveXObject("MSXML2.XMLHTTP");
                }
                catch(e){}
            }
        }
        else{
            alert("Este browser no soporta AJAX\n+y esta es una aplicaci\xd3n que solo sirve con AJAX");
        }
    }
    return req;
}

var __funcionRetorno;


function leerPropiedad (coleccion, cual, defecto){
    if (coleccion == undefined){
        return defecto;
    }
    else if (coleccion[cual]==undefined){
        return defecto;
    }
    else
        return coleccion[cual];
}

/* Formato de parametros opcionales JSON
 * 
 * metodo: metodoHTTP.GET o metodoHTTP.POST
 * parametrosPOST: string
 * tipo: tipoRetorno.TEXT, JSON, XML
 * onerror: funcion que se ejecutara si existe algun error
 * cache: true/false
 * cartelCargando: id del elemento que queremos que sea el cartel
 * repetir: n cantidad de segundos
 * timeout: n cantidad de segundos de espera antes de suponer timeout
 */

function pedirAJAX(url, funcionRetorno, parametros){
    var requerimiento = obtenerXHR();
    var hagoPeticion = true;
    var tipo = leerPropiedad(parametros, "tipo", tipoRetorno.TEXTO);
    var cache = leerPropiedad(parametros, "cache", false);
    if (!cache){
        // Agregar en la URL un parametro aleatorio
        if (url.indexOf("?")==-1)
            url += "?" + Math.random();
        else
            url += "&" + Math.random();
    }
    else{
        //El usuario quiere cache
        if (cacheAJAX[url]!= undefined){
            funcionRetorno(cacheAJAX[url]);
            hagoPeticion = false;
        }
    }
    if (hagoPeticion){
        var strMetodo;
        if(leerPropiedad(parametros, "metodo", metodoHTTP.GET)== metodoHTTP.GET)
            strMetodo = "GET";
        else
            strMetodo = "POST";

        var peticion ={
            funcionRetorno: undefined,
            tipoRetorno: undefined
        }
        var cartelCargando = leerPropiedad(parametros, "cartelCargando");

        peticion.funcionRetorno = funcionRetorno;
        peticion.tipoRetorno = tipo;
        peticion.onerror = leerPropiedad(parametros, "onerror");
        peticion.cartelCargando = cartelCargando;
        peticion.cache = cache;

        var objetoTimeOut;
        var timeout = leerPropiedad(parametros, "timeout", 0);
        var that = this;
        if (timeout>0){
            objetoTimeOut = window.setTimeout(function (){that.procesarTimeOut(peticion.onerror, requerimiento)}, timeout*1000);
        }

        peticion.objetoTimeOut = objetoTimeOut;
        peticion.url = url;

        requerimiento.open(strMetodo, url, true);
        requerimiento.onreadystatechange = function(){that.__recibirAJAX(requerimiento, peticion)};
        //si es post, necesitamos mandar una cabecera especial
        if (leerPropiedad(parametros, "metodo", metodoHTTP.GET) == metodoHTTP.POST){
            requerimiento.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            requerimiento.send(leerPropiedad(parametros, "parametrosPOST", null));
        }
        else
            requerimiento.send(null);

        //prender el cartel cargando
        if (cartelCargando!=undefined){
            $(cartelCargando).show();
        }

        var repetir = leerPropiedad(parametros, "repetir", 0);
        if (repetir > 0){
            window.setTimeout(function(){that.pedirAJAX(url, funcionRetorno, parametros)}, repetir*1000);
        }
    }
}

function procesarTimeOut(onerror, xhr){
    xhr.abort();
    xhr.onreadystatechange = undefined;
    __errorAJAX(602, onerror);
    
}

function __recibirAJAX(requerimiento, peticion){
    if (requerimiento.readyState==4){
        //limpiar time out
        if (peticion.objetoTimeOut!=undefined){
            clearTimeout(peticion.objetoTimeOut);
        }
        if (peticion.cartelCargando!=undefined){
            $(peticion.cartelCargando).hide();
        }
        if(requerimiento.status==200){
            var datos;
            var ok = true;
            if (peticion.tipoRetorno==tipoRetorno.TEXTO){
                datos = requerimiento.responseText;
            }
            else if (peticion.tipoRetorno==tipoRetorno.XML){
                datos = requerimiento.responseXML;
            }
            else{
                try {
                    datos = eval("("+requerimiento.responseText+")");
                }
                catch (e) {
                    ok = false;
                    __errorAJAX(601, peticion.onerror);
                }
            }
            if (ok){
                peticion.funcionRetorno(datos);
                if (peticion.cache)
                    cacheAJAX[peticion.url] = datos;
            }
        } else {
            if (requerimiento.status >0)
            __errorAJAX(requerimiento.status, peticion.onerror);
        }
    }
}

function __errorAJAX(codigo, funcionError){
    if (funcionError!=undefined){
        funcionError(codigo);
    }
}