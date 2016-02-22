<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class listadoRegistroPage extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vacunas/buscarRegistroDiario.tpl.html');

        // Muestra mensajes de error correspondientes
        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);
        $this->tpl->setVariable('mensajeError', ($this->config["error"] ? '' : 'none'));
        $this->tpl->setVariable('mensajeExito', ($this->config["exito"] ? '' : 'none'));

        $this->tpl->setGlobalVariable('prinurl', TemplateHelp::getSearchParameters($this->config['path'] . '.php?', $this->config['search']));
        $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);

        require_once ('libs/caus/clsCaus.php');
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Consultar)) {
            $this->tpl->setVariable("botonBuscar", '<div style="margin-top:10px"><a href="javascript:buscarFormulario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Buscar</a></div>');
            $this->tpl->setVariable("mostrarTablaSearch", '');
        } else {
            $this->tpl->setVariable("botonBuscar", '&nbsp;');
            $this->tpl->setVariable("mostrarTablaSearch", 'none');
        }
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Agregar))
            $this->tpl->setVariable("botNuevo", '');
        else 
            $this->tpl->setVariable("botNuevo", 'none');
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Borrar))
            $this->tpl->setVariable("valPermisoBorrar", 'si');
        else 
            $this->tpl->setVariable("valPermisoBorrar", 'no');
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Reportes))
            $this->tpl->setVariable("valPermisoReporte", 'si');
        else
            $this->tpl->setVariable("valPermisoReporte", 'no');
        
        $tiposId = $this->config["catalogos"]["tipoId"];
        //print_r($tiposId);exit;
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoId');
                $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                $this->tpl->parse('blkTipoId');
            }
        }
        
        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProIndividuo');
                $this->tpl->setVariable("valProIndividuo", $provincia["provincia"]);
                $this->tpl->setVariable("opcProIndividuo", htmlentities($provincia["descripcionProvincia"]));
                $this->tpl->parse('blkProIndividuo');
            }
        }

        $this->tpl->parse('contentBlock');
    }

}

?>