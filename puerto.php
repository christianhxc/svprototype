<?php
// Dominio a comprobar
$sitio = "201.218.241.172";
// Puerto a comprobar, el web es el 80
$puerto = 3306;
$fp = fsockopen($sitio,$puerto,$errno,$errstr,10);
if(!$fp)
{
echo "No ha sido posible la conexion con ".$sitio." para el puerto ".$puerto;
// El modo de tratamiento del error puede ser el que se quiera, por ejemplo enviar un email.
}else{
echo "Conexion realizada con exito con ".$sitio." para el puerto ".$puerto;
fclose($fp);
}
?>

<?php
echo curPageURL();
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"];
 }
 return $pageURL;
}
?>
