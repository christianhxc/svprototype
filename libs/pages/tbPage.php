<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class tbPage extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'tb/buscartb.tpl.html');

        // Muestra mensajes de error correspondientes
        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);
        $this->tpl->setVariable('mensajeError', ($this->config["error"] ? '' : 'none'));
        $this->tpl->setVariable('mensajeExito', ($this->config["exito"] ? '' : 'none'));




//        // Carga evento y seleccionados
//        if ($this->config["search"]["area"] != 0) {
//            $eventos = helperCatalogos::getEventos($this->config["search"]["area"]);
//
//            if (is_array($eventos)) {
//                foreach ($eventos as $evento) {
//                    $this->tpl->setCurrentBlock('blkEventos');
//                    $this->tpl->setVariable("valEventos", $evento["eve_id"]);
//                    $this->tpl->setVariable("opcEventos", htmlentities($evento["eve_nombre"]));
//
//                    // Selecciona el área guardada
//                    $this->tpl->setVariable("selEventos", ($evento["eve_id"] == $this->config["search"]["evento"] ? 'selected="selected"' : ''));
//                    $this->tpl->parse('blkEventos');
//                }
//            }
//        }
//
//        // Valores de búsqueda almacenados
//        $this->tpl->setVariable("n", htmlentities($this->config["search"]["nombres"]));
//        $this->tpl->setVariable("a", htmlentities($this->config["search"]["apellidos"]));
//        $this->tpl->setVariable("id", htmlentities($this->config["search"]["identificador"]));
//        $this->tpl->setVariable("hc", htmlentities($this->config["search"]["historia_clinica"]));
//
//        $this->tpl->setVariable("t1", ($this->config["search"]["rechazada"] == '2' ? 'selected' : ''));
//        $this->tpl->setVariable("t2", ($this->config["search"]["rechazada"] == '1' ? 'selected' : ''));
//        $this->tpl->setVariable("t3", ($this->config["search"]["rechazada"] == '0' ? 'selected' : ''));
//
//        $this->tpl->setVariable("u1", ($this->config["search"]["ubicacion"] == '0' ? 'selected' : ''));
//        $this->tpl->setVariable("u2", ($this->config["search"]["ubicacion"] == '1' ? 'selected' : ''));
//        $this->tpl->setVariable("u3", ($this->config["search"]["ubicacion"] == '2' ? 'selected' : ''));
//
//        $this->tpl->setVariable("x1", ($this->config["search"]["tipo_muestra"] == '0' ? 'selected' : ''));
//        $this->tpl->setVariable("x2", ($this->config["search"]["tipo_muestra"] == '1' ? 'selected' : ''));
//        $this->tpl->setVariable("x3", ($this->config["search"]["tipo_muestra"] == '2' ? 'selected' : ''));
//
//        $this->tpl->setVariable("gd", $this->config["search"]["global_desde"]);
//        $this->tpl->setVariable("gh", $this->config["search"]["global_hasta"]);
//        $this->tpl->setVariable("cd", $this->config["search"]["correlativo_desde"]);
//        $this->tpl->setVariable("ch", $this->config["search"]["correlativo_hasta"]);
//        $this->tpl->setVariable("td", $this->config["search"]["toma_desde"]);
//        $this->tpl->setVariable("th", $this->config["search"]["toma_hasta"]);
//        $this->tpl->setVariable("rd", $this->config["search"]["recepcion_desde"]);
//        $this->tpl->setVariable("rh", $this->config["search"]["recepcion_hasta"]);
//
//        // Mostrar las entradas del grid
//        $entradas = $this->config["entradas"];
//        if (is_array($entradas)) {
//            foreach ($entradas as $entrada) {
//                $this->tpl->setCurrentBlock('blkEntradas');
//
//                $this->tpl->touchBlock("blkHeaderModificar");
//                $this->tpl->touchBlock("blkHeaderAlicuotas");
//                $this->tpl->touchBlock("blkHeaderBorrar");
//
//                $this->tpl->setCurrentBlock("blkOpcionModificar");
//                $this->tpl->setVariable('linkEditar', '<a href="ingreso.php?action=M&m=' . $entrada["MUE_ID"] . '" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Editar">'
//                        . '<span class="ui-icon ui-icon-pencil"></span></a>');
//
//                if (!clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Modificar))
//                    $this->tpl->setVariable('linkEditar', '&nbsp;');
//                $this->tpl->parse("blkOpcionModificar");
//
//                $this->tpl->setCurrentBlock("blkOpcionAlicuotas");
//
//                if ($entrada["ubicacion"] == Configuration::ventanilla) {
//                    if ($entrada["tipo"] == Configuration::tipoMuestra)
//                        $this->tpl->setVariable('linkAlicuotas', '<a href="asignarAlicuota.php?m=' . $entrada["MUE_ID"] . '" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Asignar al&iacute;cuotas">'
//                                . '<span class="ui-icon ui-icon-copy"></span></a>');
//                    else
//                        $this->tpl->setVariable('linkAlicuotas', '&nbsp;');
//                }
//                else
//                    $this->tpl->setVariable('linkAlicuotas', '&nbsp;');
//
//                if (!clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Modificar))
//                    $this->tpl->setVariable('linkAlicuotas', '&nbsp;');
//                $this->tpl->parse("blkOpcionAlicuotas");
//
//                $this->tpl->setCurrentBlock("blkOpcionBorrar");
//                if ($entrada["ubicacion"] == Configuration::ventanilla)
//                    $this->tpl->setVariable('linkBorrar', '<a href="javascript:confirmarAnular(' . $entrada["MUE_ID"] . ');" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Anular" >'
//                            . '<span class="ui-icon ui-icon-trash"></span></a>');
//                else
//                    $this->tpl->setVariable('linkBorrar', '&nbsp;');
//
//                if (!clsCaus::validarSeccion(ConfigurationCAUS::v3, ConfigurationCAUS::Borrar))
//                    $this->tpl->setVariable('linkBorrar', '&nbsp;');
//
//                $this->tpl->parse("blkOpcionBorrar");
//
//                $this->tpl->setVariable('global', $entrada["global"] . ' - ' . helperString::completeZeros($entrada["gnumero"]));
//                $this->tpl->setVariable('correlativo', $entrada["correlativo"] . ' - ' . helperString::completeZeros($entrada["cnumero"]));
//                $this->tpl->setVariable('area', htmlentities($entrada["ARE_ANA_NOMBRE"]));
//                $this->tpl->setVariable('evento', htmlentities($entrada["EVE_NOMBRE"]));
//
//                $nombreCompleto = $entrada["IND_PRIMER_NOMBRE"] . ' ' . $entrada["IND_SEGUNDO_NOMBRE"] . ' ' . $entrada["IND_PRIMER_APELLIDO"] . ' ' . $entrada["IND_SEGUNDO_APELLIDO"];
//                $nombreCompleto = htmlentities($nombreCompleto);
//                $identificador = ($entrada["IDENTIFICADOR"] == "") ? 'No corresponde' : $entrada["IDENTIFICADOR"];
//                $this->tpl->setVariable('nombre', (trim($nombreCompleto) == '' ? $identificador : $nombreCompleto));
//                $this->tpl->setVariable('toma', $entrada["ftoma"]);
//                $this->tpl->setVariable('recepcion', $entrada["frecepcion"]);
//
//                if ($entrada["ubicacion"] == Configuration::ventanilla)
//                    $this->tpl->setVariable('estado', "Ventanilla");
//                else if ($entrada["ubicacion"] >= Configuration::enviada)
//                    $this->tpl->setVariable('estado', "Enviada");
//
//                $this->tpl->parse('blkEntradas');
//            }
//        }
//        else
//            $this->tpl->touchBlock('blkNoEntradas');
//
//        $this->tpl->setGlobalVariable('prinurl', TemplateHelp::getSearchParameters($this->config['path'] . '.php?', $this->config['search']));
//        $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);
//
//        require_once ('libs/caus/clsCaus.php');
//        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Consultar)) {
//            $this->tpl->setVariable("botonBuscar", '<div style="margin-top:10px"><a href="javascript:buscarMuestra();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Buscar</a></div>');
//            $this->tpl->setVariable("mostrarTablaSearch", '');
//        } else {
//            $this->tpl->setVariable("botonBuscar", '&nbsp;');
//            $this->tpl->setVariable("mostrarTablaSearch", 'none');
//        }

        $this->tpl->parse('contentBlock');
    }

}

?>