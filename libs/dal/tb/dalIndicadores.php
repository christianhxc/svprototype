<?php

require_once('libs/helper/helperString.php');
require_once('libs/Connection.php');

class dalIndicadores {

private $filtroUbicaciones;

    public function __construct($search){

                    $this->filtroUbicaciones = $search;
    }

    public function SR_registrado($data) {  // Total de SR_registrados en el rango de fechas
         $sql = "   SELECT QUARTER(fecha_inic) AS T, SUM(numero_casos) AS total ";
        $sql .= "     FROM eno_detalle e_d LEFT JOIN eno_encabezado e_e ON e_e.id_enc=e_d.id_enc ";
        $sql .= "    WHERE id_evento in ( SELECT id_evento ";
        $sql .= "                         FROM cat_evento ";
	$sql .= "                         WHERE cie_10_1 LIKE '%A15%' or cie_10_1 LIKE '%A16%' or cie_10_1 LIKE '%A17%' or cie_10_1 LIKE '%A18%' or cie_10_1 LIKE '%A19%' and activo = 1)  ";
        $sql .= "    AND fecha_inic BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function Total_Cohorte($data) {  // Indicador 1.2.1.5  -- egreso_cond_egreso = 4
         $sql = "   SELECT COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_motivo_exclusion not in (1)";
        $sql .= " ".  $this->filtroUbicaciones;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetchOne();
        $conn->closeConn();
        return $resp;
    }
    
    public function Total_Cohorte_Quarter($data) {  // Indicador 1.2.1.5  -- egreso_cond_egreso = 4
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_motivo_exclusion not in (1)";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }    
    
    public function SR_examinado($data) {  // Total de SR_registrados en el rango de fechas
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND mat_diag_fecha_BK1 is not NULL ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function positivo_basiloscopia($data) {  // Total de positividad a la Baciloscopía
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (mat_diag_resultado_BK1 = 1 OR mat_diag_resultado_BK2 = 1 OR mat_diag_resultado_BK3 = 1) ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function positivo_cultivo($data) {  // Total de positividad a la Baciloscopía
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (mat_diag_res_cultivo=1) ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function positivo_p_r($data) {  // Total de positividad a la Baciloscopía
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (mat_diag_res_metodo_WRD=1) ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function total_positivo($data) {  // Total de positividad a la Baciloscopía
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( mat_diag_resultado_BK1 = 1 OR mat_diag_resultado_BK2 = 1 OR mat_diag_resultado_BK3 = 1 OR mat_diag_res_cultivo=1 OR mat_diag_res_metodo_WRD=1 ) ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function diag_b_3($data) {  // Total de positividad a la Baciloscopía
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T, COUNT(*) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (id_clasificacion_BK3=3 OR id_clasificacion_BK1=3 OR id_clasificacion_BK2=3) ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= "    GROUP BY T ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function Curados_n($data) {  // Indicador 1.2.1.1  -- egreso_cond_egreso = 1 -- clas_trat_previo = 1 (nuevo) -- BK positivo
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 1 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }  
    
    public function Trat_terminado_n($data) {  // Indicador 1.2.1.2  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 2 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }     

    public function Fracaso_n($data) {  // Indicador 1.2.1.2  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 5 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    } 
    
    public function Perd_seguimiento_n($data) {  // Indicador 1.2.1.2  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 3 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }  
    
    public function Fallecidos_n($data) {  // Indicador 1.2.1.5  -- egreso_cond_egreso = 4
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 4 AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function No_evaluados_n_locales($data) {  // Indicador 1.2.1.6  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 6 AND tipo_identificacion NOT IN (4) AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function No_evaluados_n_extranjeros($data) {  // Indicador 1.2.1.7  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 6 AND tipo_identificacion IN (4) AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function exito_n($data) {  // Indicador 1.2.1.8  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( trat_fecha_fin_tratF2 IS NOT NULL OR egreso_cond_egreso = 1 ) AND tipo_identificacion IN (4) AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }

    public function Curados_at($data) {  // Indicador 1.2.1.9  -- egreso_cond_egreso = 1 -- clas_trat_previo = 1 (nuevo) -- BK positivo
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 1 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }  
    
    public function Trat_terminado_at($data) {  // Indicador 1.2.1.10  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 2 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }     

    public function Fracaso_at($data) {  // Indicador 1.2.1.11  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 5 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    } 
    
    public function Perd_seguimiento_at($data) {  // Indicador 1.2.1.12  -- Tratamiento terminado
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 3 AND clas_trat_previo = 1 ";
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }  
    
    public function Fallecidos_at($data) {  // Indicador 1.2.1.13  -- egreso_cond_egreso = 4
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 4 AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function No_evaluados_at_locales($data) {  // Indicador 1.2.1.14  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 6 AND tipo_identificacion NOT IN (4) AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function No_evaluados_at_extranjeros($data) {  // Indicador 1.2.1.15  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND egreso_cond_egreso = 6 AND tipo_identificacion IN (4) AND clas_trat_previo = 1 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function exito_at($data) {  // Indicador 1.2.1.16  -- egreso_cond_egreso = 6
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( trat_fecha_fin_tratF2 IS NOT NULL OR egreso_cond_egreso = 1 ) AND tipo_identificacion IN (4) AND clas_trat_previo = 2 "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function VIH_pruebas($data) {  // Indicador 1.3.1  -- pacientes con prueba de VIH
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (TB_VIH_fecha_muestra_VIH IS NOT NULL OR TB_VIH_fecha_prueba_VIH IS NOT NULL) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function VIH_positivo($data) {  // Indicador 1.3.2  -- pacientes con prueba de VIH
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND (clas_diag_VIH = 1 OR TB_VIH_res_VIH = 1 OR TB_VIH_res_previa_VIH = 1) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function VIH_TARV($data) {  // Indicador 1.3.3  -- pacientes con prueba de VIH
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( TB_VIH_act_TARV = 1 OR TB_VIH_inicio_TARV = 1 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function VIH_cotrimaxazol($data) {  // Indicador 1.3.3  -- pacientes con prueba de VIH
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM tb_form ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( TB_VIH_cotrimoxazol = 1 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }

    public function ubi_n5($data) {  // Ubicación institución de salud
         $sql = "   SELECT id_un AS id_n5, nombre_un AS nombre_n5 ";
        $sql .= "     FROM  `cat_unidad_notificadora` ";
        $sql .= "    WHERE 1 ";
        if ($data["n5"] != "" && $data["n5"] != "0") 
            $sql .= " AND id_un in (".$data["n5"].")";
        if ($data["n4"] != "" && $data["n4"] != "0") 
            $sql .= " AND id_corregimiento in (".$data["n4"].")";
        $sql .= " ORDER BY nombre_un ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    
      public function ubi_n1($data) {  // Ubicacion provincia
         $sql = "   SELECT id_provincia AS id_n1, nombre_provincia AS nombre_n1 ";
        $sql .= "     FROM  `cat_provincia` ";
        $sql .= "    WHERE 1 ";
        if ($data["n1"] != "" && $data["n1"] != "0") 
            $sql .= " AND id_provincia in (".$data["n1"].")";
        $sql .= " ORDER BY nombre_provincia ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
      public function ubi_n2($data) {  // Ubicacion region
         $sql = "   SELECT id_region AS id_n2, nombre_region AS nombre_n2 ";
        $sql .= "     FROM  `cat_region_salud` ";
        $sql .= "    WHERE 1 ";
        if ($data["n2"] != "" && $data["n2"] != "0") 
            $sql .= " AND id_region in (".$data["n2"].")";
        if ($data["n1"] != "" && $data["n1"] != "0") 
            $sql .= " AND id_provincia in (".$data["n1"].")";
        $sql .= " ORDER BY nombre_region ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }

      public function ubi_n3($data) {  // Ubicacion region
         $sql = "   SELECT id_distrito AS id_n3, nombre_distrito  AS nombre_n3 ";
        $sql .= "     FROM  `cat_distrito` ";
        $sql .= "    WHERE 1 ";
        if ($data["n3"] != "" && $data["n3"] != "0") 
            $sql .= " AND id_distrito in (".$data["n3"].")";
        if ($data["n2"] != "" && $data["n2"] != "0") 
            $sql .= " AND id_region in (".$data["n2"].")";
        $sql .= " ORDER BY nombre_distrito  ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
      public function ubi_n4($data) {  // Ubicacion corregimiento
         $sql = "   SELECT id_corregimiento AS id_n4, nombre_corregimiento  AS nombre_n4 ";
        $sql .= "     FROM  `cat_corregimiento` ";
        $sql .= "    WHERE 1 ";
        if ($data["n3"] != "" && $data["n3"] != "0") 
            $sql .= " AND id_distrito in (".$data["n3"].")";
        if ($data["n4"] != "" && $data["n4"] != "0") 
            $sql .= " AND id_corregimiento in (".$data["n4"].")";
        $sql .= " ORDER BY nombre_corregimiento  ; ";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_trab_salud($data) {  // Indicador 1.4.1  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 1 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_VIH($data) {  // Indicador 1.4.2  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 2 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }  
    
    public function MDR_CDR($data) {  // Indicador 1.4.3  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 3 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_PPL($data) {  // Indicador 1.4.4  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 4 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_Recaidas($data) {  // Indicador 1.4.5  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 5 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_Reingresos($data) {  // Indicador 1.4.6  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 6 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
    public function MDR_Otros($data) {  // Indicador 1.4.7  -- 
         $sql = "   SELECT QUARTER(trat_fecha_inicio_tratF1) AS T , COUNT(trat_fecha_inicio_tratF1) AS total ";
        $sql .= "     FROM `tb_form` tb_f LEFT JOIN `tb_grupo_riesgo_mdr` tb_mdr ON tb_f.id_tb = tb_mdr.id_tb ";
        $sql .= "    WHERE trat_fecha_inicio_tratF1 BETWEEN '".helperString::toDate($data["fecha_inicio"])."' AND '".helperString::toDate($data["fecha_fin"])."' ";
        $sql .= "          AND ( id_grupo_riesgo_MDR = 7 ) "; 
        $sql .= " ".  $this->filtroUbicaciones;
        $sql .= " GROUP BY T ;";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
        public function buscar_unidad_notificadora($data) {  // Total de SR_registrados en el rango de fechas
         $sql = "   SELECT id_un ";
        $sql .= "     FROM  ".$data["sql"];
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
}