<?php

class auxPagetb{
    
    public function auxparseHTMLVIH($tpl,$config) {
        
        $tpl->addBlockFile('blkVIHTB', 'blkVIHTB', Configuration::templatesPath . 'tb/VIH.tpl.html');
        
        $tpl->setVariable('valDummy', "");
        
        if ($config['read']['clas_diag_VIH'] == 0) {
        $tpl->setVariable('selSolicitud_VIH'.$config['read']['TB_VIH_solicitud_VIH'], 'selected="selected"'); 
        $tpl->setVariable('selAcepto_VIH'.$config['read']['TB_VIH_acepto_VIH'], 'selected="selected"'); 
        $tpl->setVariable('selRealizada_VIH'.$config['read']['TB_VIH_realizada_VIH'], 'selected="selected"'); 
        
        $tpl->setVariable("valFechaMuestraVIH", helperString::toDateView($config['read']['TB_VIH_fecha_muestra_VIH']));
        
        $tpl->setVariable('selRes_VIH'.$config['read']['TB_VIH_res_VIH'], 'selected="selected"'); 
        $tpl->setVariable('selAseso_VIH'.$config['read']['TB_VIH_aseso_VIH'], 'selected="selected"');
        
        $tpl->setVariable('selCotri_VIH'.$config['read']['TB_VIH_cotrimoxazol'], 'selected="selected"');
        $tpl->setVariable('valFechaCotri', helperString::toDateView($config['read']['TB_VIH_fecha_cotrimoxazol']));

        $tpl->setVariable('selTARV_VIH'.$config['read']['TB_VIH_ref_TARV'], 'selected="selected"');
        $tpl->setVariable('valFechaRefTARV', helperString::toDateView($config['read']['TB_VIH_fecha_ref_TARV']));
        
        
        $tpl->setVariable('selIniTARV_VIH'.$config['read']['TB_VIH_inicio_TARV'], 'selected="selected"');
        $tpl->setVariable('valFechaIniTARV', helperString::toDateView($config['read']['TB_VIH_fecha_inicio_TARV']));
        
        $tpl->setVariable('valLugadmTARV', $config['read']['TB_VIH_lug_adm_TARV']);
        
        } else
        {
        
        $tpl->setVariable('valFechaPruebaVIH', helperString::toDateView($config['read']['TB_VIH_fecha_prueba_VIH']));
        $tpl->setVariable('selResPrevia_VIH'.$config['read']['TB_VIH_res_previa_VIH'], 'selected="selected"');    
        $tpl->setVariable('selAseso2_VIH'.$config['read']['TB_VIH_aseso_VIH'], 'selected="selected"');
        
        
        $tpl->setVariable('selCotri2_VIH'.$config['read']['TB_VIH_cotrimoxazol'], 'selected="selected"');
        $tpl->setVariable('valFechaCotri2', helperString::toDateView($config['read']['TB_VIH_fecha_cotrimoxazol']));
        
        $tpl->setVariable('selActTARV_VIH'.$config['read']['TB_VIH_act_TARV'], 'selected="selected"');
        $tpl->setVariable('valFechaIniTARV2', helperString::toDateView($config['read']['TB_VIH_fecha_inicio_TARV']));
        
        $tpl->setVariable('valLugadmTARV2', $config['read']['TB_VIH_lug_adm_TARV']);
        
        $tpl->setVariable('selIsoniacida_VIH'.$config['read']['TB_VIH_isoniacida'], 'selected="selected"');
        
        }

        // Ejemplos
//        $this->tpl->setVariable('valFechaBK2', $this->config['read']['mat_diag_fecha_BK2']);
//        $this->tpl->setVariable('chkEP'.$this->config['read']['clas_pulmonar_EP'], 'checked=checked"');
        return $tpl;
       
    }
    
    
    public function auxparseHTMLControl($tpl,$config) {
        $tpl->addBlockFile('blkControlesTB', 'blkControlesTB', Configuration::templatesPath . 'tb/control.tpl.html');
        $tpl->setVariable("valFechaControl", "");
        $tpl->setVariable("valContactIdentificados", "");
        
        $Control_blk = $config["catalogos"]["controles"];
            if (is_array($Control_blk)) {
                foreach ($Control_blk as $control) {
                    $tpl->setCurrentBlock('blkControlDetalle');
                        $tpl->setVariable("control_indice", $control["id_tb_control"]);
                        $tpl->setVariable("fecha_control", helperString::toDateView($control["fecha_control"]));
                        $tpl->setVariable("peso", $control["peso"]);
                        $tpl->setVariable("no_dosis_control", $control["no_dosis_control"]);
                        $tpl->setVariable("fecha_BK_control", helperString::toDateView($control["fecha_BK_control"]));
                        $tpl->setVariable("nombre_clasificacion_BK", $control["nombre_clasificacion_BK"]);
                        $tpl->setVariable("resultado_cultivo", (($control["res_cultivo_control"] == "1") ? "Micobacterias No tuberculosas": ($control["res_cultivo_control"] == "2") ? "Mycobacterium tuberculosis": ($control["res_cultivo_control"] == "3") ? "No hubo crecimiento de micobacterias": ""  ));
                        $tpl->setVariable("fecha_cultivo_control",helperString::toDateView($control["fecha_cultivo_control"]) );
                        $tpl->setVariable("id_tb_form", $control["id_tb_form"]);
                        $tpl->setVariable("res_BK_control", $control["res_BK_control"]);
                        $tpl->setVariable("resultado_BK", ($control["res_BK_control"] == "1") ? "Positivo": ($control["res_BK_control"] == "0") ? "Negativo":"");
                        $tpl->setVariable("id_clasificacion_BK", $control["id_clasificacion_BK"]);
                        $tpl->setVariable("fecha_cultivo_control", helperString::toDateView($control["fecha_cultivo_control"]));
                        $tpl->setVariable("id_clasificacion_BK", $control["id_clasificacion_BK"]);
                        $tpl->setVariable("res_cultivo_control", $control["res_cultivo_control"]);
                        $tpl->setVariable("control_H", $control["control_H"]);
                        
                        $tpl->setVariable("control_R", $control["control_R"]);
                        $tpl->setVariable("control_Z", $control["control_Z"]);
                        $tpl->setVariable("control_E", $control["control_E"]);
                        $tpl->setVariable("control_S", $control["control_S"]);
                        $tpl->setVariable("control_Otros", $control["control_Otros"]);
                        $tpl->setVariable("fluoroquinolonas", $control["fluoroquinolonas"]);
                        $tpl->setVariable("inyec_2_linea", $control["inyec_2_linea"]);
                        $tpl->setVariable("reac_adv", $control["reac_adv"]);
                        $tpl->setVariable("fecha_reac_adv", helperString::toDateView($control["fecha_reac_adv"]));
                        $tpl->setVariable("id_clasi_reac_adv", $control["id_clasi_reac_adv"]);
                        $tpl->setVariable("id_conducta", $control["id_conducta"]);
                        $tpl->setVariable("hospitalizado", $control["hospitalizado"]);
                        $tpl->setVariable("preso", $control["preso"]);
                        $tpl->setVariable("fecha_preso", helperString::toDateView($control["fecha_preso"]));
                        $tpl->setVariable("usr_drogas", $control["usr_drogas"]);
                        $tpl->setVariable("alcoholismo", $control["alcoholismo"]);
                        $tpl->setVariable("tabaquismo", $control["tabaquismo"]);
                        $tpl->setVariable("mineria", $control["mineria"]);
                        $tpl->setVariable("hacinamiento", $control["hacinamiento"]);
                        $tpl->setVariable("empleado", $control["empleado"]);
                    $tpl->parse('blkControlDetalle');

                }
            }
        
        return $tpl;
       
    }
    public function auxparseHTMLContactos($tpl,$config) {
        $tpl->addBlockFile('blkContactosTB', 'blkContactosTB', Configuration::templatesPath . 'tb/contactos.tpl.html');

        $tpl->setVariable("valFecha_preso_contact", "");
        
                    // Contactos

                $tpl->setVariable('valIdent5min', $config['read']['contacto_identificados_5min']);
                $tpl->setVariable('valSintoResp5min', $config['read']['contacto_sinto_resp_5min']);
                $tpl->setVariable('valEval5min', $config['read']['contacto_evaluados_5min']);
                $tpl->setVariable('valQuimio5min', $config['read']['contacto_quimioprofilaxis_5min']);
                $tpl->setVariable('valTB5min', $config['read']['contacto_TB_5min']);
                $tpl->setVariable('valIdent5pl', $config['read']['contacto_identificados_5pl']);
                $tpl->setVariable('valSintoResp5pl', $config['read']['contacto_sinto_resp_5pl']);
                $tpl->setVariable('valEval5pl', $config['read']['contacto_evaluados_5pl']);
                $tpl->setVariable('valQuimio5pl', $config['read']['contacto_quimioprofilaxis_5pl']);
                $tpl->setVariable('valTB5pl', $config['read']['contacto_TB_5pl']);
        
        return $tpl;
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
