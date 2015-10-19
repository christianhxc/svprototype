<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

//require_once ('libs/dal/vih/MuestraSilab.php');

class formLabVicIts extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {

        $lectura = false;
        //test
//        $this->tpl->setVariable('drpExaEspeculoSi', 'selected="selected"');

        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vicits/form_laboratorio.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

        // ESTADO DE LA MUESTRA
//                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
//                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                

        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);

        if ($this->config["error"]) {
            $this->tpl->setVariable('mensajeError', '');
            $this->tpl->setVariable('mostrarError', '');
            $this->tpl->setVariable('valError', $this->config["mensaje"]);
        } else {
            $this->tpl->setVariable('mensajeError', 'none');
            $this->tpl->setVariable('mostrarError', 'none');
            $this->tpl->setVariable('valError', '');
        }
        // Carga catálogo de razones de tipo Id
        $tiposId = $this->config["catalogos"]["tipoId"];
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoId');
                $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                if (isset($this->config["read"]["id_tipo_identidad"]))
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["id_tipo_identidad"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkTipoId');
            }
        }
        
        $poblaciones = $this->config["catalogos"]["poblacion"];
        if (is_array($poblaciones)) {
            foreach ($poblaciones as $poblacion) {
                $this->tpl->setCurrentBlock('blkPoblacion');
                $this->tpl->setVariable("valPoblacion", $poblacion["id_grupo_poblacion"]);
                $this->tpl->setVariable("opcPoblacion", htmlentities($poblacion["nombre_grupo_poblacion"]));
                if ($this->config["read"])
                    $this->tpl->setVariable("selPoblacion", ($poblacion["id_grupo_poblacion"] == $this->config["read"]["id_grupo_poblacion"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkPoblacion');
            }
        }

        $muestras = $this->config["catalogos"]["muestra"];
        if (is_array($muestras)) {
            foreach ($muestras as $muestra) {
                $this->tpl->setCurrentBlock('blkTipoMuestra');
                $this->tpl->setVariable("valTipoMuestra", $muestra["id_tipos_muestras"]);
                $this->tpl->setVariable("opcTipoMuestra", htmlentities($muestra["nombre_tipos_muestras"]));
                $this->tpl->parse('blkTipoMuestra');
            }
        }
        
        $pruebas = $this->config["catalogos"]["prueba"];
        if (is_array($pruebas)) {
            foreach ($pruebas as $prueba) {
                $this->tpl->setCurrentBlock('blkPrueba');
                $this->tpl->setVariable("valPrueba", $prueba["id_prueba"]);
                $this->tpl->setVariable("opcPrueba", htmlentities($prueba["nombre_prueba"]));
                $this->tpl->parse('blkPrueba');
            }
        }
        

        if ($nuevo) {
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
        } else if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdFormLaboratorio', $this->config['read']['id_vicits_laboratorio']);
            //print_r($this->config['read']);exit;
            //
            //Individuo
            $tipoConsulta = $this->config['read']['formulario_tipo_consulta'];
            switch ($tipoConsulta) {
                case '1': $this->tpl->setVariable('selTipoConNueva', 'selected="selected"');  break;
                case '2': $this->tpl->setVariable('selTipoConRecon', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selTipoConDesc', 'selected="selected"'); break;
            }

            $this->tpl->setVariable('valFechaConsulta', helperString::toDateView($this->config['read']['formulario_fecha_consulta']));
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');

            
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            $this->tpl->setVariable('valPrimerNombre', $this->config['read']['primer_nombre']);
            $this->tpl->setVariable('valSegundoNombre', $this->config['read']['segundo_nombre']);
            $this->tpl->setVariable('valPrimerApellido', $this->config['read']['primer_apellido']);
            $this->tpl->setVariable('valSegundoApellido', $this->config['read']['segundo_apellido']);
            $this->tpl->setVariable('valIdUn', $this->config['read']['id_un']);
            $this->tpl->setVariable('valNombreUn', $this->config['read']['nombre_un']);
            $prePrueba = $this->config['read']['formulario_pre_prueba'];
            switch ($prePrueba) {
                case '1': $this->tpl->setVariable('selPrePruebaSi', 'selected="selected"');  break;
                case '2': $this->tpl->setVariable('selPrePruebaNo', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selPrePruebaDesc', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valNombreMedico', $this->config['read']['formulario_nombre_medico']);
            
            //////////////////////////////////////////// RESULTADOS //////////////////////////////////////////////////
            $poliformos= $this->config['read']['resultado_poliformos'];
            switch ($poliformos) {
                case '1': $this->tpl->setVariable('selPoliformos1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selPoliformos2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selPoliformos3', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selPoliformos4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selPoliformosDesc', 'selected="selected"'); break;
            }
            $celulas= $this->config['read']['resultados_celulas'];
            switch ($celulas) {
                case '1': $this->tpl->setVariable('selCelulas1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selCelulas2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selCelulas3', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selCelulas4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selCelulasDesc', 'selected="selected"'); break;
            }
            $diplocco= $this->config['read']['[resultados_diplocco'];
            switch ($diplocco) {
                case '1': $this->tpl->setVariable('selDiplococo1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selDiplococo2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selDiplococo3', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selDiplococo4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selDiplococoDesc', 'selected="selected"'); break;
            }
            $levaduras= $this->config['read']['resultados_levaduras'];
            switch ($levaduras) {
                case '1': $this->tpl->setVariable('selLevaduras1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selLevaduras1', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selLevaduras1', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selLevaduras1', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selLevadurasDesc', 'selected="selected"'); break;
            }
            $otros= $this->config['read']['resultados_otros'];
            switch ($otros) {
                case '1': $this->tpl->setVariable('selOtros1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selOtros2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selOtros3', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selOtros4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selOtrosDesc', 'selected="selected"'); break;
            }
            $flora= $this->config['read']['resultados_flora'];
            switch ($flora) {
                case '1': $this->tpl->setVariable('selFlora1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selFlora2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selFlora3', 'selected="selected"');  break;
                case '4': $this->tpl->setVariable('selFloraDesc', 'selected="selected"'); break;
            }
            $levadura= $this->config['read']['resultados_exa_levaduras'];
            switch ($levadura) {
                case '1': $this->tpl->setVariable('selObsLevaduraPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selObsLevaduraNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selObsLevaduraDesc', 'selected="selected"'); break;
            }
            $tricho= $this->config['read']['resultados_exa_trichomonas'];
            switch ($tricho) {
                case '1': $this->tpl->setVariable('selObsTrichoPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selObsTrichoNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selObsTrichoDesc', 'selected="selected"'); break;
            }
            $esperma= $this->config['read']['resultados_exa_esperma'];
            switch ($esperma) {
                case '1': $this->tpl->setVariable('selObsEspermaPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selObsEspermaNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selObsEspermaDesc', 'selected="selected"'); break;
            }
            $gonorrea= $this->config['read']['resultados_pcr_neisseria'];
            switch ($gonorrea) {
                case '1': $this->tpl->setVariable('selCulGonorreaPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selCulGonorreaNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selCulGonorreaDesc', 'selected="selected"'); break;
            }
            $clamidia= $this->config['read']['resultados_pcr_chlamydia'];
            switch ($clamidia) {
                case '1': $this->tpl->setVariable('selCulClamidiaPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selCulClamidiaNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selCulClamidiaDesc', 'selected="selected"'); break;
            }
            $beta= $this->config['read']['resultados_pcr_lactamasa'];
            switch ($beta) {
                case '1': $this->tpl->setVariable('selCulBetaPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selCulBetaNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selCulBetaDesc', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valVDRL_titulacion', $this->config['read']['resultados_vdrl_titulacion']);
            $vdrl= $this->config['read']['resultados_vdrl'];
            switch ($vdrl) {
                case '1': $this->tpl->setVariable('selResVDRLPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selResVDRLNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selResVDRLDesc', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valRPR_titulacion', $this->config['read']['resultados_rpr_titulacion']);
            $rpr= $this->config['read']['resultados_rpr'];
            switch ($rpr) {
                case '1': $this->tpl->setVariable('selResRPRPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selResRPRNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selResRPRDesc', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valTP_titulacion', $this->config['read']['resultados_tp_titulacion']);
            $tp= $this->config['read']['resultados_exa_levaduras'];
            switch ($tp) {
                case '1': $this->tpl->setVariable('selResTPPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selResTPNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selResTPDesc', 'selected="selected"'); break;
            }
            $vih= $this->config['read']['resultados_vih'];
            switch ($vih) {
                case '1': $this->tpl->setVariable('selResVIHPos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selResVIHNeg', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selResVIHDesc', 'selected="selected"'); break;
            }
            $pos= $this->config['read']['resultados_pos_prueba'];
            switch ($pos) {
                case '1': $this->tpl->setVariable('selPosPruebaSi', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selPosPruebaNo', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selPosPruebaDesc', 'selected="selected"'); break;
            }
            $tarv= $this->config['read']['resultados_referido_tarv'];
            switch ($tarv) {
                case '1': $this->tpl->setVariable('selTARVSi', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selTARVNo', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selTARVDesc', 'selected="selected"'); break;
            }
            
            //Muestras relacionadas
            $muestrasTotal = "";
            if (isset($this->config['muestras'])) {
                foreach ($this->config['muestras'] as $muestras) {
                    $muestrasTotal .= $muestras['id_tipos_muestras'] . "#-#" . $muestras['nombre_tipos_muestras'] . "###";
                }

                $muestrasTotal = substr($muestrasTotal, 0, strlen($muestrasTotal) - 3);
                $this->tpl->setVariable('valMuestrasRelacionadas', $muestrasTotal);
            }
            //Pruebas relacionadas
            $pruebasTotal = "";
            if (isset($this->config['pruebas'])) {
                foreach ($this->config['pruebas'] as $prueba) {
                    $pruebasTotal .= $prueba['id_prueba'] . "#-#" . $prueba['nombre_prueba'] . "###";
                }

                $pruebasTotal = substr($pruebasTotal, 0, strlen($pruebasTotal) - 3);
                $this->tpl->setVariable('valPruebasRelacionadas', $pruebasTotal);
            }
            
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormLaboratorio, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarFormLaboratorio();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormLaboratorio, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarFormLaboratorio();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormLaboratorio, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="indexFormLaboratorio.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

?>
