<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalReporteCobertura.php');

class generaReportePage extends page {
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
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/generareporte.tpl.html');
        $this->tpl->setVariable("vacunas", $this->getHtmlVacunas());
        $this->tpl->setVariable("dosis", $this->getHtmlDosis());
        $this->tpl->setVariable("data", $this->getHtmlReportData());
        $this->tpl->setVariable("title", "Reportes de Registro Diario - ".$this->config["parameters"]["search"]["tipo"]);
        $this->tpl->parse('contentBlock');
    }
    public function getHtmlVacunas(){
        $colspan = 1;
        if($this->config["parameters"]["search"]["tipo"] == "Cobertura") $colspan = 2;
        $html = '<td colspan="'.$colspan.'"></td>';
        $vacunas = $this->getHeadersData();
        foreach($vacunas as $key=>$vacuna){
            $colspan = 1;
            $colspanTasaDeserc = 0;
            if($this->config["parameters"]["search"]["tipo"] == "Cobertura") $colspan = 2;
//            $colspanTasaDeserc = (count($vacuna["dosis"]) > 1 && $this->config["parameters"]["search"]["tipo"] == "Cobertura")? 1: 0;
                $html .= '<td style="background-color: cornflowerblue;" colspan="'.((count($vacuna["dosis"])*$colspan)+$colspanTasaDeserc).'" align="center">'.$vacuna["name"].'</td>';
        }
        return $html;
    }
    public function getHtmlDosis(){
        $nivelName = $this->getNivelName($this->config["parameters"]["search"]["nivel"]);
        $denominadorData = dalReporteCobertura::getDenominador($this->config["parameters"]["search"], $this->grupoEdad);
        $html = '<td style="background-color: peachpuff;">'.$nivelName.'</td>';
        if($this->config["parameters"]["search"]["tipo"] == "Cobertura")
            $html .= '<td style="background-color: yellow;">'.$denominadorData[0]["descripcion"].'</td>';
        $vacunas = $this->getHeadersData();
        foreach($vacunas as $key=>$vacuna){
            foreach($vacuna["dosis"] as $keyDosis => $valueDosis){
                $html .= '<td style="background-color: cornflowerblue;">'.$valueDosis["name"].'</td>';
                if($this->config["parameters"]["search"]["tipo"] == "Cobertura")
                    $html .= '<td style="background-color: cornflowerblue;">'.$valueDosis["pname"].'</td>';
            }
//            if(count($vacuna["dosis"]) > 1 && $this->config["parameters"]["search"]["tipo"] == "Cobertura"){
//                $html .= '<td style="background-color: cornflowerblue;">Tasa Deserc</td>';
//            }
        }
        return $html;
    }
    public function getVacunasFromDb(){
        $this->vacunas = dalReporteCobertura::ObtieneConfiguraciones($this->config["parameters"]["search"]["configuracion"]);
        $this->grupoEdad = $this->vacunas[0]["id_grupo_edad"];
    }
    public function getHeadersData(){
        $this->getVacunasFromDb();
        $vacunas = array();
        foreach($this->vacunas as $key=> $value){
            if(!in_array($value["id_vacuna"], $this->vacunasId)){
                array_push($this->vacunasId,$value["id_vacuna"]);
            }
            if(!in_array($value["id_dosis"], $this->dosisId)){
                array_push($this->dosisId, $value["id_dosis"]);
            }
            $vacunas[$value["id_vacuna"]]["name"] =  $value["nombre_vacuna"];
            if(!is_array($vacunas[$value["id_vacuna"]]["dosis"])) $vacunas[$value["id_vacuna"]]["dosis"] = array();
            if($this->config["parameters"]["search"]["tipo"] == "Cobertura"){
//                if(count($vacunas[$value["id_vacuna"]]["dosis"]) > 1){
//                    $vacunas[$value["id_vacuna"]]["dosis"][$value["id_dosis"]] =  array("name" => $value["dosis"], "dvalue" => 0, "pname" => "%Cobertura", "pvalue" => 0, "tname" => "Tasa Deserc", "tvalue"=>0);
//                }else{
//                    $vacunas[$value["id_vacuna"]]["dosis"][$value["id_dosis"]] =  array("name" => $value["dosis"], "dvalue" => 0, "pname" => "%Cobertura", "pvalue" => 0);
//                }
                $vacunas[$value["id_vacuna"]]["dosis"][$value["id_dosis"]] =  array("name" => $value["dosis"], "dvalue" => 0, "pname" => "%Cobertura", "pvalue" => 0);
            }else{
                $vacunas[$value["id_vacuna"]]["dosis"][$value["id_dosis"]] =  array("name" => $value["dosis"], "dvalue" => 0);
            }
        }
        return $vacunas;
    }
    public function getHtmlReportData(){
        $data = $this->getReportData();
        $html = '';
        foreach($data as $key=>$value){
            $html .= '<tr><td>'.$key.'</td>';
            if($this->config["parameters"]["search"]["tipo"] == "Cobertura")
                $html .= '<td>'.$value["denominador"].'</td>';
            foreach($value["vacunas"] as $keyVacuna=>$valueVacuna){
                foreach($valueVacuna["dosis"] as $keyDosis => $valueDosis){
                    $html .= '<td>'.$valueDosis["dvalue"].'</td>' ;
                    if($this->config["parameters"]["search"]["tipo"] == "Cobertura")
                        $html .= '<td>'.$valueDosis["pvalue"].'</td>' ;
//                    if(count($valueVacuna["dosis"]) > 1 && $this->config["parameters"]["search"]["tipo"] == "Cobertura")
//                        $html .= '<td>'.$valueDosis["tvalue"].'</td>' ;
                }
            }
            $html .= '</tr>';
        }
        return $html;
    }
    public function getReportData(){
        $denominadorData = dalReporteCobertura::getDenominador($this->config["parameters"]["search"], $this->grupoEdad);
        $rptdata = $this->vacunas = dalReporteCobertura::ObtieneDatosConfiguraciones($this->config["parameters"]["search"], implode(",", $this->vacunasId), implode(",", $this->dosisId), $this->grupoEdad);
        $data = array();
        foreach($rptdata as $key=>$value){
            if(!is_array($data[$value["nombre_proregcor"]])) $data[$value["nombre_proregcor"]] = array("denominador" => 0, "vacunas" => array());
            //$data[$value["nombre_proregcor"]]["denominador"] = rand(50, 500);
            $data[$value["nombre_proregcor"]]["denominador"] = $this->getDenominador($denominadorData, $value["id_proregcor"]);
            if(count($data[$value["nombre_proregcor"]]["vacunas"]) <= 0) {
                $data[$value["nombre_proregcor"]]["vacunas"] = $this->getHeadersData();
            }
            $data[$value["nombre_proregcor"]]["vacunas"][$value["id_vacuna"]]["dosis"][$value["id_dosis"]]["dvalue"] = $value["total"];
            if($this->config["parameters"]["search"]["tipo"] == "Cobertura"){
                $data[$value["nombre_proregcor"]]["vacunas"][$value["id_vacuna"]]["dosis"][$value["id_dosis"]]["pvalue"] = ($data[$value["nombre_proregcor"]]["denominador"] > 0)? round(($value["total"]/$data[$value["nombre_proregcor"]]["denominador"])*100,2): 0;
            }
        }
        return $data;
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