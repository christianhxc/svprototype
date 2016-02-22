<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');

class generaReporteRegistroDiarioPage extends page {
    public $config;
    protected $vacunas;
    protected $pacientes;
    protected $vacunasId = array();
    protected $dosisId = array();
    protected $grupoEdad = 0;
    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }
    public function parseHeader(){}
    public function parseFooter(){}
    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/generareporteregistrodiario.tpl.html');
        $this->tpl->setVariable("title", "Reporte de Registro Diario");
        $this->tpl->setVariable("headers", $this->getHtmlHeaders());
        $this->tpl->setVariable("data", $this->getHtmlReportData());
        $this->tpl->parse('contentBlock');
    }
    public function getHtmlHeaders(){
            $headers = '
                        <th valign="top">Region</th>
                        <th valign="top">Distrito</th>
                        <th valign="top">Corregimiento</th>
                        <th valign="top">Unidad notificadora</th>
                        <th valign="top">ZONA</th>
                        <th valign="top">No. Provisional</th>
                        <th valign="top">ID del Paciente</th>
                        <th valign="top">Fecha de Nac.</th>
                        <th valign="top">M</th>
                        <th valign="top">Tipo paciente</th>
                        <th valign="top">Tipo</th>
                        <th valign="top">Sexo</th>
                        <th valign="top">Edad</th>
                        ';
            $vacunas = $this->getListadoVacunas();
            foreach($vacunas as $key=>$value){
                $headers .= '<th valign="top" width="8%">'.$value["nombre_vacuna"].'</th>';
            }
        return $headers;
    }
    public function getHtmlReportData(){
        $data = $this->getReportData();
        $html = '';
        foreach($data as $key=>$value){
            $html .= '<tr>';
            $html .= '<td>'.$value["nombre_region"].'</td>';
            $html .= '<td>'.$value["nombre_distrito"].'</td>';
            $html .= '<td>'.$value["nombre_corregimiento"].'</td>';
            $html .= '<td>'.$value["nombre_un"].'</td>';
            $html .= '<td>'.$value["nombre_zona"].'</td>';
            $html .= '<td>'.$value["num_provisional"].'</td>';
            $html .= '<td>'.$value["id_paciente"].'</td>';
            $html .= '<td>'.$value["fecha_nacimeinto"].'</td>';
            $html .= '<td>'.$value["m"].'</td>';
            $html .= '<td>'.$value["tipo_paciente"].'</td>';
            $html .= '<td>'.$value["tipo"].'</td>';
            $html .= '<td>'.$value["sexo"].'</td>';
            $html .= '<td>'.$value["edad"].'</td>';
            $html .= $this->getVacunasHtml($value["dosis"], $value["fecha_dosis"]);
            $html .= '</tr>';
        }
        return $html;
    }
    private function getVacunasHtml($vacunasPaciente, $fechaDosis){
        $html = '';
        $vacpaciente = explode(",", $vacunasPaciente);
        $vacfecha = explode(",", $fechaDosis);
        $combine = array_combine($vacpaciente, $vacfecha);
        foreach($this->vacunas as $vacuna){
            $html .= '<td align="center">';
            $vacdosis = explode(",", $vacuna["dosis"]);
            $intersect = array_intersect($vacpaciente, $vacdosis);
            if(count($intersect) > 0){
                $html .= $combine[$intersect[0]];
            }
            $html .= '</td>';
        }
        return $html;
    }
    private function getListadoVacunas(){
        $this->vacunas = dalReporteCobertura::getListadoVacunasRegistroDiario();
        return $this->vacunas;
    }
    public function getReportData(){
        $this->pacientes = dalReporteCobertura::getListadoPacientesRegistroDiario();
        return $this->pacientes;
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
}