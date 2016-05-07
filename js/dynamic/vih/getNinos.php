<?php

require_once("libs/helper/helperVih.php");
header('Content-type: text/javascript');
$search = $_REQUEST['idVihForm'];
$factores = helperVih::getVihNinos($search);
echo json_encode($factores);
