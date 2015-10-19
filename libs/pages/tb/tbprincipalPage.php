<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class tbprincipalPage extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'tb/principal.tpl.html');

        // Muestra mensajes de error correspondientes
        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);
        $this->tpl->setVariable('mensajeError', ($this->config["error"] ? '' : 'none'));
        $this->tpl->setVariable('mensajeExito', ($this->config["exito"] ? '' : 'none'));

        
        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProPagInicio');
                $this->tpl->setVariable("valPagInicio", $provincia["provincia"]);
                $this->tpl->setVariable("opcPagInicio", htmlentities($provincia["descripcionProvincia"]));
                $this->tpl->parse('blkProPagInicio');
            }
        }

        $this->tpl->parse('contentBlock');
    }

}

?>