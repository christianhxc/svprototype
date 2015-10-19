<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ('libs/caus/clsCaus.php');
require_once ('libs/caus/ConfigurationCAUS.php');
require_once ('libs/pages/recuperarUsuario.php');

$config["jsfiles"][] = "recuperarusuario.js";

if (count($_POST)){
    // Enviar email
    $subject = ConfigurationCAUS::emailAsuntoUsuario;

    $message = "Un usuario del sistema ha solicitado su nombre de usuario para accesar al sistema debido a que no lo recuerda, estos son sus datos:\n\n";
    $message .= "Nombres: ".$_POST["nombres"]."\n";
    $message .= "Apellidos: ".$_POST["apellidos"]."\n";
    $message .= "Email: ".$_POST["email"]."\n";
    $message .= "Telefono: ".$_POST["telefono"]."\n";
    $message .= "Comentario: ".$_POST["comentario"]."\n";

    $headers = 'From: '.ConfigurationCAUS::emailDe."\r\n";
    $headers .= 'Reply-To: '.ConfigurationCAUS::emailResponder. "\r\n";

    @mail(ConfigurationCAUS::emailPara, $subject, $message, $headers);
    
    header("location: login.php?info=Un correo electr&oacute;nico ha sido enviado, en unos minutos se le contactar&aacute;");
    exit;
}

$page = new recuperarUsuario($config);
$page->displayPage();
?>
