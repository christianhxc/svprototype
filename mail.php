<?php
$to      = 'christian@autoweb.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: admin_sisvig@programainfluenza.org' . "\r\n" .
    'Reply-To: admin_sisvig@programainfluenza.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>