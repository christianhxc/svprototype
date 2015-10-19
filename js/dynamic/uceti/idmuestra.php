<?php
require_once("libs/helper/helperUceti.php");

$correlativo_muestras = helperUceti::IncrementarMuestraId();

print_r($correlativo_muestras["no_muestra"]);

