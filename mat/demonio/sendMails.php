<?php

require_once('Connection.php');
require_once('Configuration.php');
require_once('class.phpmailer.php');

class sendMails{
    public static function enviarMail($mails, $mensaje){
        
        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
        $mail->IsSMTP(); // telling the class to use SMTP
        try {
            /*Para la configuracion de envio de correos desde localhost, cuando este en godaddy comentarias las siguientes 5 lineas y activar la linea comentada*/
            $mail -> SMTPDebug  = Configuration::SMTPDebug;      // enables SMTP debug information (for testing)
            $mail -> SMTPAuth   = Configuration::SMTPAuth;       // enable SMTP authentication
            $mail -> SMTPSecure = Configuration::SMTPSecure;     // sets the prefix to the servier
            $mail -> Host       = Configuration::emailHost;      // sets GMAIL as the SMTP server
            $mail -> Port       = Configuration::emailPort;      // set the SMTP port for the GMAIL server
            //$mail -> Host       = Configuration::emailHostGodaddy;
            $mail -> Username   = Configuration::emailUsername;  // GMAIL username
            $mail -> Password   = Configuration::emailPassword;  // GMAIL password
            
            if (is_array($mails)){
                
                foreach ($mails as $email)
                    $mail -> AddAddress($email);
            }
            else 
                $mail -> AddAddress($mails);
            $mail -> SetFrom(Configuration::emailDe, Configuration::emailAsuntoGeneral);
            $mail -> AddReplyTo(Configuration::emailResponder, Configuration::emailAsuntoGeneral);
            $mail -> Subject = Configuration::emailAsuntoCrearUsuario;
            $mail -> Body = $mensaje;
            //$mail -> AddAttachment('../img/0.png'); // attachment
            $mail -> Send();
            return true;
        } catch (phpmailerException $e) {
            return $e -> errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            return $e -> getMessage(); //Boring error messages from anything else!
        }
    }
}
?>