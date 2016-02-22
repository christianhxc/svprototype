<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionTipo extends baseDal {

    public static function Leer(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select id_notificacion_tipo, nombre
                from vac_notificacion_tipo
                where deleted = 0 and status = 1
                order by id_notificacion_tipo ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

}