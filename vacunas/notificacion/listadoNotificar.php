<?php
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/notificacion/dalNotificacionVacunas.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');
$page = $_GET["page"];
$itemsPeerPage = 100;
function getDateNextVac($hrsVac, $hrsPac){
    $hrs = $hrsVac - $hrsPac;
    return date('d/m/Y', strtotime($hrs.' hour'));

}
$dateIni = "2010-01-01";
$date = date("Y-m-d");
$pacientes = dalReporteCobertura::getPacientesVacunas(array("fecini" => "", "fecfin" => ""), 2);
$vacunasListado = dalNotificacionVacunas::LeerVacunas();
$data = array("pages" => 0, "data" => array());
foreach($pacientes as $key=>$value){
    $vacunas = array();
    $horas = $value["edad_vac_horas"];
    foreach($vacunasListado as $keyV=>$valueV){
        if($horas >= $valueV["edad_vac_ini_horas"] && $horas <= $valueV["edad_vac_fin_horas"]){
            $vacunas[] = $valueV;
        }
    }
    if(count($vacunas) > 0){
        $data["data"][] = array(
            "numero_identificacion" => $value["numero_identificacion"],
            "nombre" => $value["primer_nombre"]." ".$value["primer_apellido"],
            "dir_referencia" => $value["dir_referencia"],
            "tel_residencial" => $value["tel_residencial"],
            "correo_electronico" => $value["correo_electronico"],
            "vacunas" => array()
        );
        $lastIndex = count($data["data"])-1;
        foreach($vacunas as $kvac=>$valvac){
            $data["data"][$lastIndex]["vacunas"][] = array(
                "nombre_vacuna" => $valvac['nombre_vacuna'],
                "num_dosis_refuerzo" => $valvac['num_dosis_refuerzo'],
                "fecha_vacuna" => getDateNextVac($valvac['edad_vac_fin_horas'], $value['edad_vac_horas'])
            );
        }
    }
}
$data["pages"] = ceil(count($data["data"])/$itemsPeerPage);
$offset = 0;
if($page > 0 && $page <= $data["pages"]){
    $offset =  ($page == 1) ? 0 : ($page-1) * $itemsPeerPage;
    $sliceData = array_slice($data["data"], $offset, $itemsPeerPage);
}
echo json_encode($data);