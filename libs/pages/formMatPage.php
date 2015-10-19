<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formMat extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }
    

    public function parseContent() {
        $lectura = false;
//        if ($this->config["actionEscenario"] == "N")
            $nuevo = true;
//        else
//            $nuevo = false;
        if ($this->config["actionEscenario"] == "R" || $this->config["actionEscenario"] == "M") {
            $lectura = true;
        }
        $this->tpl->setVariable("action", $this->config["action"]);
        $this->tpl->setVariable("actionContacto", ($this->config["actionContacto"]=='NC')?'NC':'');

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'mat/formulario.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

        $this->tpl->setVariable('disErrorGeneral','none');  
        $this->tpl->setVariable('disInfoGeneral','none');  

        if ($this->config["Einfo"]!=""){ 
            $this->tpl->setVariable('disInfoGeneral','');
            $this->tpl->setVariable('valInfoGeneral', $this->config["Einfo"]);
        }
        if ($this->config["Eerror"]!=""){
            $this->tpl->setVariable('disErrorGeneral','');
            $this->tpl->setVariable('valErrorGeneral', $this->config["Eerror"]);
        }
        if ($this->config["Cinfo"]!=""){ 
            $this->tpl->setVariable('disInfoGeneral','');
            $this->tpl->setVariable('valInfoGeneral', $this->config["Cinfo"]);
        }
        if ($this->config["Cerror"]!=""){
            $this->tpl->setVariable('disErrorGeneral','');
            $this->tpl->setVariable('valErrorGeneral', $this->config["Cerror"]);
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
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["data"]["individuo"]["tipoId"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkTipoId');
            }
        }
        
        $grupos = $this->config["catalogos"]["grupos"];
        if (is_array($grupos)) {
            foreach ($grupos as $grupo) {
                $this->tpl->setCurrentBlock('blkGrupoContacto');
                $this->tpl->setVariable("valGrupoContacto", $grupo["id_grupo_contacto"]);
                $this->tpl->setVariable("opcGrupoContacto", htmlentities($grupo["nombre_grupo_contacto"]));
                $this->tpl->parse('blkGrupoContacto');
                
                $this->tpl->setCurrentBlock('blkSelGrupo');
                $this->tpl->setVariable("valSelGrupo", $grupo["id_grupo_contacto"]);
                $this->tpl->setVariable("opcSelGrupo", htmlentities($grupo["nombre_grupo_contacto"]));
                $this->tpl->parse('blkSelGrupo');
                
            }
        }
        
        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProvincia');
                $this->tpl->setVariable("valProvincia", $provincia["provincia"]);
                $this->tpl->setVariable("opcProvincia", htmlentities($provincia["descripcionProvincia"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selProvincia", ($provincia["provincia"] == $this->config["data"]["individuo"]["idProvincia"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkProvincia');
            }
        }

        if ($nuevo) {
            $this->tpl->setVariable('actionEscenario', 'N');
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["escenario"]["nombreCrear"]))
                $nombreUsuario = $this->config["data"]["escenario"]["nombreCrear"];
            $this->tpl->setVariable('valNombreCrear', $nombreUsuario);
            $fechaFormulario = date("d/m/Y");
            $this->tpl->setVariable('valFechaCrear', $fechaFormulario);
                      
            
        } else if ($lectura) {
            $this->tpl->setVariable('actionEscenario', 'M');
            $this->tpl->setVariable('valIdVihForm', $this->config['read']['id_vih_form']);
            //print_r($this->config['read']);exit;
            //
            
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
                        
            
            $this->tpl->setVariable('valCondicionSida', ($this->config['read']['cond_sida'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valFechaSida', helperString::toDateView($this->config['read']['cond_fecha_sida']));
            $this->tpl->setVariable('valCondicionVih', ($this->config['read']['cond_vih'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valFechaVih', helperString::toDateView($this->config['read']['cond_fecha_vih']));
            $this->tpl->setVariable('valSobrevida', $this->config['read']['cond_sobrevida']);
            if($this->config['read']['cond_fecha_defuncion']!="")
                $this->tpl->setVariable('valFechaDefuncion', helperString::toDateView($this->config['read']['cond_fecha_defuncion']));
            
            $this->tpl->setVariable('valLugarDefuncion', $this->config['read']['cond_otro_defuncion']);
            
            if ($this->config['read']['cond_id_un_defuncion']!=null&&$this->config['read']['cond_id_un_defuncion']!="")
                $this->tpl->setVariable('valIdUnDefuncion', $this->config['read']['cond_id_un_defuncion']);
            else if ($this->config['read']['cond_otro_defuncion']!="")
                $this->tpl->setVariable('valOtroDefuncion', 'checked="checked"');
                         
                       
            $this->tpl->setVariable('valSobrevidaSida', $this->config['read']['cond_sobrevida_sida']);
            $this->tpl->setVariable('valLugarDiagnostico', $this->config['read']['cond_lugar_diagnostico']);
            
            
            //Notificacion
            $unidadDisponible = $this->config['read']['unidad_disponible'];
            if ($unidadDisponible == '1') {
                $this->tpl->setVariable('valNotificacionUnidad', $this->config['read']['nombre_un']);
                $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
                $this->tpl->setVariable('valUnidadNoDisponible', '');
                $this->tpl->setVariable('otraInstalacion', 'none');
                $this->tpl->setVariable('valNotificacionOtraUnidad', '');
            } else {
                $this->tpl->setVariable('valNotificacionUnidad', "");
                $this->tpl->setVariable('valNotificacionIdUn', "");
                $this->tpl->setVariable('valUnidadNoDisponible', 'checked="checked"');
                $this->tpl->setVariable('otraInstalacion', '');
                $this->tpl->setVariable('valNotificacionOtraUnidad', $this->config['read']['otro_nombre_un']);
            }
                        
            
            $this->tpl->setVariable('valNombreInvestigador', $this->config['read']['nombre_notifica']);
            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config['read']['fecha_notificacion']));
            $this->tpl->setVariable('valSemanaEpi', $this->config['read']['semana_epi']);
            $this->tpl->setVariable('valNotificacionAnio', $this->config['read']['anio']);
            $this->tpl->setVariable('valNombreRegistra', $this->config['read']['nombre_registra']);
            $this->tpl->setVariable('valFecFormVih', helperString::toDateView($this->config['read']['fecha_formulario']));
            $this->tpl->setVariable('valDatoSilab', $this->config['read']['silab']);
            $this->tpl->setVariable('valDatoEpiInfo', $this->config['read']['epiInfo']);
            
            //require_once ('libs/dal/vih/MuestraSilab.php');
            $muestras = MuestraSilab::construirMuestraSilab($this->config["muestras"]);
            $muestraSplit = explode("###",$muestras);
            if (isset($muestraSplit[1])&&$muestraSplit[1]!=''){
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de SILAB - Actualizada');
                $this->tpl->setVariable('valResultadoSilab',$muestraSplit[0]);
                $this->tpl->setVariable('valGlobalMuestras',$muestraSplit[1]);
                $this->tpl->setVariable('valGlobalPruebas',$muestraSplit[2]);
            }
            else{
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Sin Muestra de SILAB');
                $this->tpl->setVariable('valResultadoSilab','');
                $this->tpl->setVariable('valGlobalMuestras','');
                $this->tpl->setVariable('valGlobalPruebas','');
            }
        }

        

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Agregar)){
                $this->tpl->setVariable("btnGuardarEscenario", '<a id="btnPrincipal" href="javascript:validarEscenario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("btnGuardarContacto", '<a id="btnContacto" href="javascript:validarContacto();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("btnGuardarRel", '<a id="btnRelacion" href="javascript:guardarRelacion();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
            }
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Modificar)){
                $this->tpl->setVariable("btnGuardarEscenario", '<a id="btnPrincipal" href="javascript:validarEscenario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
                $this->tpl->setVariable("btnGuardarContacto", '<a id="btnContacto" href="javascript:validarContacto();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
            }
        }
        
        $this->tpl->setVariable("btnCancelarEscenario", '<a href="javascript:cancelarEscenario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');
        $this->tpl->setVariable("btnCancelarContacto", '<a href="javascript:cancelarContacto();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');
        $this->tpl->setVariable("btnCancelarRel", '<a href="javascript:cancelarRelacion();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

?>
