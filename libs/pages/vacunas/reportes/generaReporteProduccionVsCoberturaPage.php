<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');

class generaReporteProduccionVsCoberturaPage extends page {
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
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/generareporteproduccionvscobertura.tpl.html');
            $tipo = "Reporte Producción vs Cobertura";
        $this->tpl->setVariable("title", $tipo);
        $this->tpl->setVariable("data", $this->getHtmlReportData());
        $this->tpl->parse('contentBlock');
    }
    public function getHtmlHeaders(){
        return '<th>Cédula</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo electronico</th>
                    <th>Vacuna</th>
                    <th>Dosis</th>
                    <th>Fecha corresponde vacuna</th>';
    }
    public function getHtmlReportData(){
        /*$data = $this->getReportData();
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
        }*/
        $html = "";
        $data = $this->getReportData();
        foreach($data as $key=> $value){
            $prod = rand(10,500);
            $cob = rand(5,$prod);
            $por = number_format(($cob/$prod)*100,2);
            $html .= "<tr>";
            $html .= "<td>".$value["nombre_distrito"]."</td>";
            $html .= "<td>".$prod."</td>";
            $html .= "<td>".$cob."</td>";
            $html .= "<td>".$por."%</td>";
            $html .= "</tr>";
        }
        return $html;
    }
    public function getReportData(){
        $rptdata = dalReporteCobertura::getDistrito();
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

}