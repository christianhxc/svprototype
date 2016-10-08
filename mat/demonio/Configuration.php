<?php
class Configuration
{
    //const absolutePath = 'http://localhost/sisvig2/';
    const absolutePath = 'http://190.34.154.85/sisvig2/';
//    const absolutePath = 'http://173.201.187.40/sisvig2/';

    const DefaultTitleAdmin = 'SISVIG .::. Sistema de vigilancia en salud publica Panama';

    // BD LOCAL    
//    const DBHandler = 'mysql';
//    const DBuser = 'sisvig';
//    const DBpass = '123456';
//    const DB = 'sisvigdb';
//    const host = 'localhost';
    
    // BD EN Panama
    const DBHandler = 'mysql';
    const DBuser = 'sisvigPan';
    const DBpass = 'qwerty12';
    const DB = 'sisvigdb';
    const host = '10.130.16.41';
    
    //BD EN GODADDY
//    const DBHandler = 'mysql';
//    const DBuser = 'dtroncoso';
//    const DBpass = 'dTBdFlu2010';
//    const DB = 'sisvigdb';
//    const host = '173.201.187.40';

//    const templatesPath = 'C:/xampp/htdocs/sisvig2/templates/';
    const templatesPath = '/opt/lampp/htdocs/sisvig2/templates/';
    
//    const bdEpiInfoPath = 'C:/xampp/htdocs/sisvig2/vih/archivos_bd/';
    const bdEpiInfoPath = '/opt/lampp/htdocs/sisvig2/vih/archivos_bd/';

    // No. de dias para cambiar una clave, si es 0 no solicita cambiar
    const expiracion = 0;

    // Id de las secciones de menú
    const idVigRutinaria = 70;
    const idVigMortalidad = 72;
    const idRumoresBrotes = 71;
    const idVigCentinela = 73;
    const idInvEspecial = 74;
    const idCatalogos = 4;
    
    // Cantidad de filas mostradas pagineo
    const paginado = 10;

//    const urlprefix ='http://localhost/sisvig2/';
//    const urlprefixViejo ='http://localhost/pan/';
//    const javaAddress = 'C:/xampp/htdocs/pan/bridge/java/Java.inc';
//    const reportAddress = 'http://localhost/sisvig2/';
//    const urlReport = "jdbc:mysql://localhost:3306/";
//    const dbReport = "sisvigdb?user=sisvig&password=123456";
    

    const urlprefix = 'http://190.34.154.85/sisvig2/';    
    const urlprefixViejo ='http://190.34.154.85/sisvig/';
    const javaAddress = '/opt/lampp/htdocs/sisvig2/bridge/java/Java.inc';    
    const reportAddress = 'http://190.34.154.85/sisvig2/';    
    const urlReport = "jdbc:mysql://10.130.16.41:3306/";
    const dbReport = "sisvigdb?user=sisvigPan&password=qwerty12";
    

//    const urlprefix = 'http://173.201.187.40/sisvig2/';    
//    const urlprefixViejo ='http://173.201.187.40/sisvig2/';    
//    const reportAddress = 'http://173.201.187.40/sisvig2/';    
//    const urlReport = "jdbc:mysql://173.201.187.40:3306/";
//    const dbReport = "sisvigdb?user=dtroncoso&password=dTBdFlu2010";
//    const javaAddress = '/var/www/html/pan/bridge/java/Java.inc';

    const emailAsuntoCrearUsuario = "Se ha generado una nueva alerta la cual requiere de su intervencion";
    const emailAsunto = "Recuperar clave para SISVIG";
    const emailAsuntoUsuario = "Recuperar usuario para SISVIG";
    const emailAsuntoGeneral = "Modulo de Alerta Temprana - MAT";
    const emailDe = "admin_sisvig@programainfluenza.org";
    const emailResponder = "admin_sisvig@programainfluenza.org";
    const emailUsername = "caus_pan@programainfluenza.org";  // GMAIL username
    const emailPassword = "sisvig2011";            // GMAIL password

    /*Estas variables las usamos para configurar el localhost para poder enviar correos, cuando esta en godaddy las deshabilitamos, estan configuradas por defecto*/
    const SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    /*  2: muestra todo el estado de las diferentes operaciones
        1: solo los errores si es que los hay, en las operaciones
        0: naranja, no muestra nada    */
    const SMTPAuth   = true;                  // enable SMTP authentication
    const SMTPSecure = "ssl";                 // sets the prefix to the servier
    const emailHost = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    const emailPort = 465;                   // set the SMTP port for the GMAIL server
    
    /*Configuracion para go daddy*/
    const emailHostGodaddy = "relay-hosting.secureserver.net"; 

}
?>
