<?php

require_once('vendor/apache/log4php/src/main/php/Logger.php');
require_once('vendor/swiftmailer/swiftmailer/lib/swift_required.php');
require_once('libs/dal/vacunas/notificacion/dalNotificacionMensajesBatch.php');
require_once('libs/Configuration.php');

Logger::configure('config.xml');
$logger = Logger::getLogger('pai_mensaje');

$batch = 10;
$page = $_REQUEST["page"] != "" ? $_REQUEST["page"] : 1;
$total = dalNotificacionMensajesBatch::Total();
$pages = ceil($total / $batch);

$logger->info("Paginas: ".$pages." | Pagina: ".$page);
if ($page > $pages){
    $logger->info("Se termino de enviar las notificaciones");
    exit;
}

$limit = ($page - 1) * 10;
$data = dalNotificacionMensajesBatch::Notificaciones($limit, $batch);
if (!is_array($data)){
    $logger->info("No hay notificaciones para enviar en este momento");
    exit;
}

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername(Configuration::smtpEmail)
    ->setPassword(Configuration::smtpPass);

$mailer = Swift_Mailer::newInstance($transporter);

foreach ($data as $registro){
    $message = Swift_Message::newInstance($registro["nombre"])
        ->setFrom(array('sisvigpai@gmail.com' => 'Sisvig PAI'))
        ->setTo(array(strtolower($registro["email"])))
        ->setBody($registro["mensaje"]);
    $result = $mailer->send($message);
    $logger->info(json_encode($registro));

    $now = new DateTime(null, new DateTimeZone("America/Panama"));
    $fecha_hora = $now->format('Y-m-d H:i:s');
    $log["id_notificacion"] = $registro["id_notificacion"];
    $log["id_notificacion_contacto"] = $registro["id_notificacion_contacto"];
    $log["fecha_envio"] = $fecha_hora;
    $log["status"] = (string) $result;

    dalNotificacionMensajesBatch::Log($log);

    $logger->info(json_encode($log));
}

header("location: mensaje.php?page=".($page + 1));