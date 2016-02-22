<?php
require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalReporteRegistroDiario extends baseDal {

    public static function getData(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT cv.id_vacuna, cv.nombre_vacuna, vrdrcv.id_dosis, CONCAT( if( vd.tipo_dosis =1, 'Dosis', 'Refuerzo' ) , ' ', vd.num_dosis_refuerzo ) AS dosis, vrdrc.* FROM
                    vac_registro_diario_reporte_configuracion vrdrc,
                    vac_registro_diario_reporte_configuracion_vacuna vrdrcv,
                    cat_vacuna cv,
                    vac_dosis vd
                WHERE
                    vrdrcv.id_configuracion = ".$configurarionId."
                    AND vrdrcv.id_vacuna = cv.id_vacuna
                    AND vrdrc.id_configuracion = vrdrcv.id_configuracion
                    AND vd.id_dosis = vrdrcv.id_dosis";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
}