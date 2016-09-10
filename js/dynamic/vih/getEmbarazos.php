<?php

require_once("libs/helper/helperVih.php");
header('Content-type: text/javascript');
$search = $_REQUEST['idVihForm'];
$factores = helperVih::getVihEmbarazos($search);
if ($factores == NULL) $factores = "";
echo json_encode($factores);
