<?php
class Configuration
{
    //const absolutePath = 'http://localhost/sisvig2/';
//    const absolutePath = 'http://190.34.154.87/sisvig2/';

    const DefaultTitleAdmin = 'SISVIG .::. Sistema de vigilancia en salud publica Panama';

    // BD LOCAL    
//    const DBHandler = 'mysql';
//    const DBuser = 'sisvig';
//    const DBpass = '123456';
//    const DB = 'sisvigdb';
//    const host = 'localhost';
    
    // BD EN GODADDY
    const DBHandler = 'mysql';
    const DBuser = 'root';
    const DBpass = '';
    const DB = 'sisvigdb';
    const host = 'localhost';

    const templatesPath = 'G:/xampp/htdocs/sisvig2/templates/';
    //const templatesPath = '/var/www/html/sisvig2/templates/';

    const bdEpiInfoPath = 'G:/xampp/htdocs/sisvig2/vih/archivos_bd/';
    //const bdEpiInfoPath = '/var/www/html/sisvig2/vih/archivos_bd/';

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
    
    const urlprefix = 'http://localhost/sisvig/';    
    const urlprefixViejo ='http://localhost/sisvig/';
    const javaAddress = '/var/www/html/sisvig2/bridge/java/Java.inc';    
    const reportAddress = 'http://localhost/sisvig/';    
    const urlReport = "jdbc:mysql://localhost:3306/";
    const dbReport = "sisvigdb?user=sisvigPan&password=qwerty12";

    public static function getAbsolutePath() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL . "/sisvig2/";
    }
    
    public static function getUrlprefix() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL . "/sisvig2/";
    }

    const smtpEmail = 'sisvigpai';
    const smtpPass = 'panama2015$';
}
?>
