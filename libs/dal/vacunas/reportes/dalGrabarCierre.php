<?php
require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalGrabarCierre extends baseDal {

    public static function GetTotalRowsToUpdate($year, $month) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "select count(1) as total from vac_registro_diario_dosis
                    where
                    cierre = 0
                    AND YEAR(fecha_dosis) = ".$year."
                    AND MONTH(fecha_dosis) = ".$month;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    public static function VerificarCierre($year, $month){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT count(1) as total FROM mov_historial_cierres
                    where
                    revertido = 0
                    AND anio = ".$year."
                    AND mes = ".$month;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    public static function GrabarCierre($year, $month) {
        $total = self::GetTotalRowsToUpdate($year, $month);
        self::ToggleCierre($year, $month, 1);
        self::GrabarHistorial($year, $month, $total[0]);
    }
    public static function RevertirCierre($year, $month, $id){
        self::ToggleCierre($year, $month, 0);
        self::RevertirHistorial($id);
    }
    public static function GetHistorialCierres(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT * FROM mov_historial_cierres
                    WHERE
                    revertido = 0 ORDER BY id DESC ";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    public static function GetLastHistorial(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select * from mov_historial_cierres
                order by id desc limit 1";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    private static function ToggleCierre($year, $month, $status){
        $conn = new Connection();
        $conn->initConn();
        $sql = "UPDATE vac_registro_diario_dosis SET
                    cierre = ".$status."
                    WHERE
                    YEAR(fecha_dosis) = ".$year."
                    AND MONTH(fecha_dosis) = ".$month;
        $conn->prepare($sql);
        $conn->execute();
        $conn->closeConn();
        return true;
    }
    private static function GrabarHistorial($year, $month, $total){
        $conn = new Connection();
        $conn->initConn();
        $sql = "INSERT INTO mov_historial_cierres SET
                    mes = ".$month.",
                    anio = ".$year.",
                    total_registros = ".$total.",
                    fecha_creacion = NOW(),
                    fecha_modificacion = NOW()";
        $conn->prepare($sql);
        $conn->execute();
        $conn->closeConn();
        return true;
    }
    private static function RevertirHistorial($id){
        $conn = new Connection();
        $conn->initConn();
        $sql = "UPDATE mov_historial_cierres SET
                    revertido = 1
                    WHERE
                    id = ".$id;
        $conn->prepare($sql);
        $conn->execute();
        $conn->closeConn();
        return true;
    }
} 