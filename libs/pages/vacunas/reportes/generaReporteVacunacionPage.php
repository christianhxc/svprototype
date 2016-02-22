<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');

class generaReporteVacunacionPage extends page {
    public $config;
    protected $vacunas;
    protected $vacunasId = array();
    protected $dosisId = array();
    protected $grupoEdad = 0;
    protected $programacionVacunas = array();
    protected $programacionPacientes = array();
    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }
    public function parseHeader(){}
    public function parseFooter(){}
    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/generareportevacunacion.tpl.html');
        if($this->config["parameters"]["search"]["tipo"] == "Vacunacion"){
            $this->tpl->setVariable("data", $this->getHtmlReportData());
            $tipo = "Listado de Vacunación";
        }elseif($this->config["parameters"]["search"]["tipo"] == "Programacion"){
            $this->tpl->setVariable("data", $this->getHtmlProgInasistenciasData($this->config["parameters"]["search"]["tipo"]));
            $tipo = "Listado de Programación";
        }else{
            $this->tpl->setVariable("data", $this->getHtmlProgInasistenciasData($this->config["parameters"]["search"]["tipo"]));
            $tipo = "Listado de Inasistencias";
        }
        $this->tpl->setVariable("title", $tipo);
        $this->tpl->setVariable("headers", $this->getHtmlHeaders($this->config["parameters"]["search"]["tipo"]));
        $this->tpl->parse('contentBlock');
    }
    public function getHtmlHeaders($tipo){
        if($tipo == 'Vacunacion'){
            return '<th>Cédula</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo electronico</th>
                        <th>Vacuna</th>
                        <th>Dosis</th>
                        <th>Fecha aplicación</th>
                        <th>Lugar aplicación</th>';
        }else{
            return '<th>Cédula</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo electronico</th>';
        }
    }
    public function getHtmlReportData(){
        $data = $this->getReportData();
        $html = '';
        foreach($data as $key=>$value){
            $html .= '<tr>';
            $html .= '<td>'.$value["numero_identificacion"].'</td>';
            $html .= '<td>'.$value["primer_nombre"].$value["segundo_nombre"].'</td>';
            $html .= '<td>'.$value["dir_referencia"].'</td>';
            $html .= '<td>'.$value["tel_residencial"].'</td>';
            $html .= '<td>'.$value["correo_electronico"].'</td>';
            $html .= '<td>'.$value["nombre_vacuna"].'</td>';
            $html .= '<td>'.$value["dosis"].'</td>';
            $html .= '<td>'.$value["fecha_dosis"].'</td>';
            $html .= '<td>Zona '.$value["id_zona"].'</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    public function getHtmlProgInasistenciasData($tipo){
        $this->getProgramacionPacientes();
        $this->getProgramacionVacunas();
        $html = '';
        foreach($this->programacionPacientes as $key=>$value){
            if($tipo == "Programacion"){
                $vacunas = $this->checkVacunas($value["edad_vac_horas"]);
            }else{
                $vacunas = $this->verificaAnasistencias($value["edad_vac_horas"], $value["dosis_aplicadas"]);
            }
            if(count($vacunas) > 0){
                $html .= '<tr>';
                $html .= '<td>'.$value["numero_identificacion"].'</td>';
                $html .= '<td>'.$value["primer_nombre"]." ".$value["primer_apellido"].'</td>';
                $html .= '<td>'.$value["dir_referencia"].'</td>';
                $html .= '<td>'.$value["tel_residencial"].'</td>';
                $html .= '<td>'.$value["correo_electronico"].'</td>';
                $html .= '</tr>';
                $html .= '<tr><td></td><td colspan="4">';
                $html .= '<table width="100%" style="border: 1px solid black;"><tr>
                    <td><strong>No.</strong></td>
                    <td><strong>Vacuna</strong></td>
                    <td><strong>Dosis</strong></td>
                    <td><strong>Fecha Programada</strong></td>
                </tr>';
            }
            $x = 1;
            foreach($vacunas as $kvac=>$valvac){
                $dateNextVac = $this->getDateNextVac($valvac['edad_vac_fin_horas'], $value['edad_vac_horas']);
                $html .= "<tr>";
                $html .= "<td>".$x."</td>";
                $html .= "<td width='50%'>".$valvac['nombre_vacuna']."</td>";
                $html .= "<td>".$valvac['num_dosis_refuerzo']."</td>";
                $html .= "<td>".$dateNextVac."</td>";
                $html .= "</tr>";
                $x++;
            }
            if(count($vacunas) > 0){
                $html .= "</table></td></tr>";
            }
        }
        return $html;
    }
    public function getProgramacionPacientes(){
        $this->programacionPacientes = dalReporteCobertura::getPacientesVacunas($this->config["parameters"]["search"], 2);
    }
    public function getProgramacionVacunas(){
        $this->programacionVacunas = dalReporteCobertura::getVacunasPorHora($this->config["parameters"]["search"], 2);
    }
    public function getReportData(){
        $rptdata = dalReporteCobertura::ObtieneDatosVacunas($this->config["parameters"]["search"], "5,6,7,8,9,10,25,26,27,28,29", "104,105,106,107,331,323", 2);
        return $rptdata;
    }
    private function getNivelName($nivelId){
        switch ($nivelId) {
            case 1:
                return "Nacional";
            case 2:
                return "Provincia";
            case 3:
                return "Region";
            case 4:
                return "Distrito";
            case 5:
                return "Corregimiento";
            case 6:
                return "Unidad Notificadora";
        }
        return "";
    }
    private function getDenominador($denominadorData, $id){
        foreach($denominadorData as $key=>$value){
            if($value["id_proregcor"] == $id){
                return $value["denominador"];
            }
        }
        return 0;
    }
    private function getDateNextVac($hrsVac, $hrsPac){
        $hrs = $hrsVac - $hrsPac;
        return date('d/m/Y', strtotime($hrs.' hour'));

    }
    private function checkVacunas($horas){
        $vacunas = array();
        foreach($this->programacionVacunas as $key=>$value){
            if($horas >= $value["edad_vac_ini_horas"] && $horas <= $value["edad_vac_fin_horas"]){
                $vacunas[] = $value;
            }
        }
        return $vacunas;
    }
    private function verificaAnasistencias($horas, $asistencias){
        $vacunas = array();
        foreach($this->programacionVacunas as $key=>$value){
            if($horas > $value["edad_vac_fin_horas"] && strpos($asistencias, $value["id_dosis"]) == false){
                $vacunas[] = $value;
            }
        }
        return $vacunas;
    }
}