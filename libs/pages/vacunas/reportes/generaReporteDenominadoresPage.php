<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');

class generaReporteDenominadoresPage extends page {
    public $config;
    protected $vacunas;
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
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/generareportedenominadores.tpl.html');
        $this->tpl->setVariable("title", "Exportacion de denominadores");
        $this->tpl->setVariable("headers", $this->getHtmlHeaders());
        $this->tpl->setVariable("data", $this->getHtmlReportData());
        $this->tpl->parse('contentBlock');
    }
    public function getHtmlHeaders(){
            return '<th>Tipo</th>
                        <th>Region</th>
                        <th>Distrito</th>
                        <th>Corregimiento</th>
                        <th>Unidad notificadora</th>';
    }
    public function getHtmlReportData(){
        $data = $this->getReportData();
        $html = '';
        foreach($data as $key=>$value){
            $html .= '<tr>';
            $html .= '<td>'.$this->getNivelName($value["nivel"]).'</td>';
            $html .= '<td>'.$value["nombre_region"].'</td>';
            $html .= '<td>'.$value["nombre_distrito"].'</td>';
            $html .= '<td>'.$value["nombre_corregimiento"].'</td>';
            $html .= '<td>'.$value["nombre_un"].'</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    public function getReportData(){
        $rptdata = dalReporteCobertura::getDenominadoresForReport($this->config["parameters"]["search"], "5,6,7,8,9,10,25,26,27,28,29", "104,105,106,107,331,323", 2);
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
}