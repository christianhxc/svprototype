<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');
require_once ('libs/dal/vacunas/reportes/dalGrabarCierre.php');

class cierreMensualPage extends page
{
    public $config;

    function __construct($data = null)
    {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent()
    {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'vacunas/reportes/cierreMensual.tpl.html');

        $initialAnio = 2000;
        $endAnio = date("Y");
        $defaultMonth = date("n");
        $defaultYear = date("Y");
        for($x = 1; $x<=12; $x++){
            $this->tpl->setCurrentBlock('blkMeses');
            $this->tpl->setVariable("mesValue", $x);
            $this->tpl->setVariable("mesDesc", $x);
            $default = ($defaultMonth == $x)? 'selected="selected"': "";
            $this->tpl->setVariable("selectedMes", $default);
            $this->tpl->parse('blkMeses');
        }
        for($x = $initialAnio; $x<=$endAnio; $x++){
            $this->tpl->setCurrentBlock('blkAnios');
            $this->tpl->setVariable("anioValue", $x);
            $this->tpl->setVariable("anioDesc", $x);
            $default = ($defaultYear == $x)? 'selected="selected"': "";
            $this->tpl->setVariable("selectedAnio", $default);
            $this->tpl->parse('blkAnios');
        }
        $historial = dalGrabarCierre::GetHistorialCierres();
        $historialHtml = '';
        foreach($historial as $value){
            $historialHtml .= '<tr class="dxgvDataRow_PlasticBlue">';
            $historialHtml .= '<td class="dxgv" align="right">'.$value["id"].'</td>';
            $historialHtml .= '<td class="dxgv" align="right">'.$value["mes"].'/'.$value["anio"].'</td>';
            $historialHtml .= '<td class="dxgv" align="right">'.number_format($value["total_registros"],0).'</td>';
            $historialHtml .= '<td class="dxgv" align="right">'.$value["fecha_creacion"].'</td>';
            $historialHtml .= '<td class="dxgv" align="center"><a class="revertir-cierre" href="#" cierre-year="'.$value["anio"].'" cierre-month="'.$value["mes"].'" cierre-id="'.$value["id"].'"><img title="Borrar" border=0 src="../../img/Delete.png"></a></td>';
            $historialHtml .= '</tr>';
        }
        $this->tpl->setVariable("historial", $historialHtml);

//                require_once ('libs/caus/clsCaus.php');
//                if(clsCaus::validarSeccion(ConfigurationCAUS::r4, ConfigurationCAUS::Reportes))
        $this->tpl->setVariable("botonGenerar",'<input type="button" value="Grabar cierre" id="grabar-cierre" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Por favor considere que el reporte puede tardarse" />');
        $this->tpl->parse('contentBlock');
    }
}
?>