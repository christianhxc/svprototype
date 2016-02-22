<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');

class configuracionPage extends page {
    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/vacunas/reportes/configuracion.tpl.html');

        $lectura = false;
        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }

        $this->tpl->setVariable("disError", 'none');

        $escenarios = $this->config["catalogos"]["escenarios"];
        if (is_array($escenarios)) {
            foreach ($escenarios as $escenario) {
                $this->tpl->setCurrentBlock('blkEscenario');
                $this->tpl->setVariable("valEscenario", $escenario["id_esquema"]);
                $this->tpl->setVariable("opcEscenario", htmlentities($escenario["nombre_esquema"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selEscenario", ($escenario["bodega"] == $this->config["data"]["escenario"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkEscenario');
            }
        }

        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardarPrevio(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        } else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardarPrevio(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="envios.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->parse('contentBlock');
    }
} 