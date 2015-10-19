<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class vihPage extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vih/buscarVih.tpl.html');

        // Muestra mensajes de error correspondientes
        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);
        $this->tpl->setVariable('mensajeError', ($this->config["error"] ? '' : 'none'));
        $this->tpl->setVariable('mensajeExito', ($this->config["exito"] ? '' : 'none'));

        switch ($this->config["selectMensaje"]) {
            case '1':
                $data = helperMuestra::getCodigos($this->config["muestra"]);
                $this->tpl->setVariable('valExito', '&#161;Muestra agregada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>' . $data[0]["mue_codigo_global_anio"] .
                        ' - ' . helperString::completeZeros($data[0]["mue_codigo_global_numero"]) . '</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                        . $data[0]["mue_codigo_correlativo_alfa"] . ' - ' . helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]) . '</strong>');
                break;
            case '2':
                $data = helperMuestra::getCodigos($this->config["muestra"]);
                $this->tpl->setVariable('valExito', '&#161;Muestra editada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>' . $data[0]["mue_codigo_global_anio"] .
                        ' - ' . helperString::completeZeros($data[0]["mue_codigo_global_numero"]) . '</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                        . $data[0]["mue_codigo_correlativo_alfa"] . ' - ' . helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]) . '</strong>');
                break;
            case '3':
                $data = helperMuestra::getCodigos($this->config["muestra"]);
                $this->tpl->setVariable('valExito', 'La muestra con C&Oacute;DIGO GLOBAL: <strong>' . $data[0]["mue_codigo_global_anio"] .
                        ' - ' . helperString::completeZeros($data[0]["mue_codigo_global_numero"]) . '</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                        . $data[0]["mue_codigo_correlativo_alfa"] . ' - ' . helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]) . '</strong>
                                    se anul&oacute; correctamente');
                //$this->tpl->setVariable('valExito','&#161;Muestra anulada correctamente&#33;');
                break;
            case '4':
                $this->tpl->setVariable('valError', '&#161;Imposible anular la muestra, ya posee pruebas/diagn&oacute;stico/al&iacute;cuotas asignadas&#33;');
                break;
            case '5':
                $this->tpl->setVariable('valError', '&#161;Imposible editar, muestra no existente&#33;');
                break;
        }

        $this->tpl->setGlobalVariable('prinurl', TemplateHelp::getSearchParameters($this->config['path'] . '.php?', $this->config['search']));
        $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);

        require_once ('libs/caus/clsCaus.php');
        if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Consultar)) {
            $this->tpl->setVariable("botonBuscar", '<div style="margin-top:10px"><a href="javascript:buscarMuestra();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Buscar</a></div>');
            $this->tpl->setVariable("mostrarTablaSearch", '');
        } else {
            $this->tpl->setVariable("botonBuscar", '&nbsp;');
            $this->tpl->setVariable("mostrarTablaSearch", 'none');
        }
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Agregar))
            $this->tpl->setVariable("botNuevo", '');
        else 
            $this->tpl->setVariable("botNuevo", 'none');
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Borrar))
            $this->tpl->setVariable("valPermisoBorrar", 'si');
        else 
            $this->tpl->setVariable("valPermisoBorrar", 'no');
        if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Reportes))
            $this->tpl->setVariable("valPermisoReporte", 'si');
        else
            $this->tpl->setVariable("valPermisoReporte", 'no');

        $this->tpl->parse('contentBlock');
    }

}

?>