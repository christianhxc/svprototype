<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    require_once('libs/helper/helperVacunas.php');
    
    $resultado = null;    
    
    if (isset ($_POST["numero_identificacion"]) && isset ($_POST["tipo_identificacion"]) && isset ($_POST["registro_diario"])){
        $sql = "select * from tbl_persona 
                where numero_identificacion = '".$_POST["numero_identificacion"]."' AND tipo_identificacion = ".$_POST["tipo_identificacion"];
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        $persona = ($data!=null) ? $data : null;
        $persona = $persona[0];
        $algoritmo = new algoritmoPersonaDosis($persona);
        $esquemas = $algoritmo->getEsquemas();
        $edadDiasPersona = $algoritmo->getDiasEntreFechas($persona["fecha_nacimiento"], $_POST["fecha_formulario"]);
        $idEsquemas;
        $i = 0;
        foreach ($esquemas as $esquema){
            $agregarEsquema1 = $agregarEsquema2 = $agregarEsquema3 = $agregarEsquema4 = $agregarEsquema5 = 1;
            
            if($esquema["tipo_rango_ini"]!=0)
                $agregarEsquema1 = ($edadDiasPersona > $algoritmo->getNumeroDias($esquema["rango_edad_ini"], $esquema["tipo_rango_ini"])) ? 1 : 0;
            if($esquema["tipo_rango_fin"]!=0){
                $diasEsquemaFin = $algoritmo->getNumeroDias($esquema["rango_edad_fin"], $esquema["tipo_rango_fin"]);
                $diasEsquemaFin =  ( $esquema["tipo_rango_fin"] === 5 ) ? $diasEsquemaFin+360 : $diasEsquemaFin;
                $agregarEsquema2 = ($edadDiasPersona < $diasEsquemaFin) ? 1 : 0;
            }
            if($esquema["sexo"]!="N")
                $agregarEsquema3 = ($persona["sexo"] === $esquema["sexo"]) ? 1 : 0;
            if($esquema["tomar_fecha_vigencia"]===1)
                $agregarEsquema4 = (strtotime($esquema["tomar_fecha_vigencia"])-strtotime($persona["fecha_nacimiento"]))>0 ? 1 : 0;
            $condicionesEsquema = $algoritmo->getCondicionesEsquema($esquema["id_esquema"]);
            $condicionesPersona = $algoritmo->getCondicionesPersona($persona);

            $condicion = 0;
            foreach ($condicionesEsquema as $condicionEsq){ 
                foreach ($condicionesPersona as $condicionPer){
                    if ($condicionEsq === $condicionPer){
                        $condicion = 1;
                        break 2;
                    }
                    else
                        $condicion = 0;
                }
            }
            
            if (count($condicionesPersona)>0)
                $agregarEsquema5 = ($condicion === 1) ? 1 : 0;
            
            else if (count($condicionesEsquema)>0)
                $agregarEsquema5 = ($condicion === 1) ? 1 : 0;
            
            if ($agregarEsquema1 === 1 && $agregarEsquema2 === 1 && $agregarEsquema3 === 1 && $agregarEsquema4 === 1 && $agregarEsquema5 === 1){
                $idEsquemas[$i] = $esquema["id_esquema"];
                $i++;                
            }
//            echo "<br/>Id de esquema: ".$esquema["id_esquema"];
//            $diasInicio = $algoritmo->getNumeroDias($esquema["rango_edad_ini"], $esquema["tipo_rango_ini"]);
//            echo "<br/>Dias Inicio :".$diasInicio."<br/>";
//            $diasInicio = $algoritmo->getNumeroDias($esquema["rango_edad_fin"], $esquema["tipo_rango_fin"]);
//            echo "Dias Fin :".$diasInicio."<br/>";
//            echo "Dias persona ".$edadDiasPersona."<br/>";
//            echo "persona sexo: ".$persona["sexo"]."<br/>";
//            echo "esquema sexo: ".$esquema["sexo"]."<br/>";
//            echo "tomar fecha vigencia: ".$esquema["tomar_fecha_vigencia"]."<br/>";
//            echo "Condiciones Esquema: <br/>";
//            print_r($condicionesEsquema);
//            echo "<br/>Condiciones Persona: <br/>";
//            print_r($condicionesPersona);
//            echo "<br/>condicion: ".$condicion."<br/>";
        }
//        echo "Los esquemas que si acepta son:<br/>";
//        print_r($idEsquemas);
//        exit;
        $dosisForm = $algoritmo->getFormDosis($_POST["registro_diario"]);
        $allDosis = $algoritmo->getDosis($idEsquemas, $dosisForm);
        $i=0;
        foreach ($allDosis as $dosis){
            $agregarDosis1 = $agregarDosis2 = 1;
            $diasMenos = 0;
            $diasMas = 0;
            $diasInicial = 0;
            $diasFinal = 0;
            if($dosis["edad_vac_ini"]!=null){
                if ($dosis["margen_vac_ini"]!=null)
                    $diasMenos = $algoritmo->getNumeroDias($dosis["margen_vac_ini"], ($dosis["tipo_margen_vac_ini"]+1));
                $diasInicial = $algoritmo->getNumeroDias($dosis["edad_vac_ini"], $dosis["tipo_edad_vac_ini"])-$diasMenos;
                $agregarDosis1 = ($edadDiasPersona > $diasInicial) ? 1 : 0;
            }
            if($dosis["tipo_edad_vac_fin"]!=0 && $dosis["tipo_edad_vac_fin"]!=null){
                if ($dosis["margen_vac_fin"]!=null)
                    $diasMas = $algoritmo->getNumeroDias($dosis["margen_vac_fin"], ($dosis["tipo_margen_vac_fin"]+1));
                $diasMas = ($dosis["tipo_edad_vac_fin"] === 5) ? $diasMas+360: $diasMas;
                $diasFinal = $algoritmo->getNumeroDias($dosis["edad_vac_fin"], $dosis["tipo_edad_vac_fin"]) + $diasMas;
                $agregarDosis2 = ($edadDiasPersona < $diasFinal) ? 1 : 0;
            }
            if ($agregarDosis1 === 1 && $agregarDosis2 === 1 ){
                $idDosis[$i] = $dosis["id_dosis"];
                $i++;                
            }
//            echo "<br/>Id dosis: ".$dosis["id_dosis"];
//            $diasInicial = $algoritmo->getNumeroDias($dosis["edad_vac_ini"], $dosis["tipo_edad_vac_ini"]);
//            echo "<br/>Dias Inicio :".$diasInicial."<br/>";
//            echo "Dias Menos :".$diasMenos."<br/>";
//            echo "Agrega condicion dias inicial: ".$agregarDosis1."<br/>";
//            echo "Dias Fin :".$diasFinal."<br/>";
//            echo "Agrega condicion dias fina: ".$agregarDosis2."<br/>";
//            echo "Dias persona ".$edadDiasPersona."<br/>";
        }
//        echo "Las dosis que si aplica son: ";
//        print_r($idDosis);exit;
        
        $allDosis = $algoritmo->getDosisFormato($idEsquemas, $dosisForm);
        $i = $j = 0;
        foreach ($allDosis as $dosis){
            foreach ($idDosis as $idDo){
                if ($dosis["id_dosis"] == $idDo){
                    $allDosis[$i]["status"] = 1;
                    break;
                }
            }
            $i++;
        }
        $resultado = array_merge((array)$dosisForm, (array)$allDosis);
        
        //print_r($resultado);exit;
        
    }
    
    if (isset($_REQUEST["mode"]) || $resultado!=null){
        
        $codigoError = '';
        $tabla = "";

        // Acción por realizar
        $mode = $_REQUEST["mode"];

        // Encabezado de tabla
        echo '<table id="tabla" align="center" width="100%">';
        echo "<tr class=\"dxgvDataRow_PlasticBlue\">
                <th width=\"5%\" class=\"dxgvHeader_PlasticBlue\">Id.</th>
                <th width=\"15%\" class=\"dxgvHeader_PlasticBlue\">Escenario</th>
                <th width=\"20%\" class=\"dxgvHeader_PlasticBlue\">Nombre Vacuna</th>
                <th width=\"10%\" class=\"dxgvHeader_PlasticBlue\">Tipo Dosis</th>
                <th width=\"15%\" class=\"dxgvHeader_PlasticBlue\">Fecha</th>
                <th width=\"20%\" class=\"dxgvHeader_PlasticBlue\">No. Lote(s)</th>
                <th width=\"10%\" class=\"dxgvHeader_PlasticBlue\">Status</th>
                <th width=\"5%\" class=\"dxgvHeader_PlasticBlue\">Acciones</th>
            </tr>";
        
        foreach ($resultado as $lista) {
            $id_dosis = $lista['id_dosis'];
            $id_vacuna = $lista['id_vacuna'];
            $nombre_vacuna = $lista['nombre_vacuna'];
            $nombre_esquema = $lista['nombre_esquema'];
            $id_esquema_form = $lista['id_esquema'];
            $tipo_dosis = $lista['tipo_dosis'];
            $num_dosis_refuerzo = $lista['num_dosis_refuerzo'];
            $edad_ini = $lista['edad_ini'];
            $lote1 = $lista['numero_lote_1'];
            $lote2 = $lista['numero_lote_2'];
            $lote3 = $lista['numero_lote_3'];
            $status = ($lista['status'] == '1' ? 'Habilitado' : 'No Habilitado');
            $id_ = $id_dosis;
            $id_vac_registro = $lista['id_esq_detalle'];
            $fecha_dosis = $lista['fecha_dosis'];
            $id_diario_dosis = $lista['id_registro_diario_dosis'];
            
//            print_r($lista); exit;
            echo "<tr class=\"dxgvDataRow_PlasticBlue\">";
            echo "<td class=\"dxgv\" width= \"5%\">" . htmlentities($id_) . "</td>";
            echo "<td class=\"dxgv\" width= \"15%\">" . htmlentities($nombre_esquema) . "</td>";    
            echo "<td class=\"dxgv\" width= \"20%\"> "
                        . "<input type='hidden' value='" . $id_ . "' name='prev_id' id='id_dosis" . $id_ . "'/> "
                        . "<input type='hidden' value='" . $id_vacuna . "' name='prev_id' id='id_vacuna" . $id_ . "'/> "
                        . htmlentities($nombre_vacuna) 
                ."</td>";
            echo "<td class=\"dxgv\" width= \"10%\">" . htmlentities($tipo_dosis)." ". htmlentities($num_dosis_refuerzo) . "</td>";
            echo "<td class=\"dxgv\" width= \"10%\">" . htmlentities($fecha_dosis) ;
            if ($fecha_dosis!=""){
                echo  "  <a href=\"javascript:borrar_dosis_registro('$id_diario_dosis')\"><img id=\"btn_delete\" border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/delete.png\" title=\"Eliminar dosis\"/></a> ";
            }
            echo  " </td>";
            if ($lista['status'] == '1'){
                echo "<td class=\"dxgv\" width=\"20%\">"
                        . "<input type='text' value='" . htmlentities($lote1) . "' id='lote1_" . $id_ . "' style=\"width: 150px;\" />"
                        . "<input type='text' value='" . htmlentities($lote2) . "' id='lote2_" . $id_ . "' style=\"width: 150px;\" />"
                        . "<input type='text' value='" . htmlentities($lote3) . "' id='lote3_" . $id_ . "' style=\"width: 150px;\" />"
                    . "</td>";
            }
            else{
                echo "<td class=\"dxgv\" width=\"20%\">". htmlentities($lote1) ."-". htmlentities($lote2) ."-". htmlentities($lote3) ."</td>";
            }
            echo "<td class=\"dxgv\" width= \"10%\">" . $status . "</td>";
            if ($lista['status'] == '1'){
                if ($fecha_dosis!=""){
                    echo "<td class=\"dxgv\" width= \"5%\">"
                            . "<a href=\"javascript:editar_dosis_registro('$id_dosis')\"><img border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/Save1.png\" title=\"Guardar\"/></a> "
                            . "<a href=\"javascript:traer_info('$id_dosis')\"><img id=\"btn_info\" border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/info.png\" title=\"Informacion\"/></a>"
                        //. "<a href=\"javascript:eliminar_dosis_registro('$id_dosis')\" onclick='return confirmLink(this);'><img border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/delete.png\" title=\"Eliminar\"/></a>"
                       . "</td>";
                }
                else
                    echo "<td class=\"dxgv\" width= \"10%\">"
                        . "<a href=\"javascript:guardar_dosis_registro('$id_dosis','$id_esquema_form')\"><img border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/Save1.png\" title=\"Guardar\"/></a> "
                        . "<a href=\"javascript:traer_info('$id_dosis')\"><img id=\"btn_info\" border=0 src=\"" . Configuration::getUrlprefix() . "img/iconos/info.png\" title=\"Informacion\"/></a>"
                        . "</td> ";
            }
            else
                echo "<td class=\"dxgv\" width= \"10%\"> </tr>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' value='".$_POST["registro_diario"]."' name='id_form_registro' id='id_form_registro'/>";
    }
    
    class algoritmoPersonaDosis {
        private $unDia = 86400; // segundos
        private $unaSemana = 7;
        private $unMes;
        private $unAnio;
        private $persona;
        
        function __construct($persona){
            $this->persona = $persona;
            $this->unMes = ($this->unaSemana * 4) + 2;
            $this->unAnio = ($this->unMes * 12) + 5;
	}
        
        public function getNumeroDias($edad, $tipo){
            $edadDias=$edad;
            switch ($tipo){
                case 3: $edadDias = $edad * $this->unaSemana; break;
                case 4: $edadDias = $edad * $this->unMes; break;
                case 5: $edadDias = $edad * $this->unAnio; break;
            }
            return $edadDias;
        }
        
        public function getCondicionesEsquema($id_esquema){
            $sql = "select id_condicion
                    from vac_esquema_condicion where id_esquema = ".$id_esquema;
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            $condicionEsquema = ($data!=null) ? $data : null;
            return $condicionEsquema;
        }
        
        public function getCondicionesPersona($persona = null){
            $persona = ($persona == null) ? $this->persona : $persona;
            $sql = "select id_condicion
                    from vac_persona_condicion 
                    where numero_identificacion = '".$persona["numero_identificacion"]."' AND tipo_identificacion = ".$persona["tipo_identificacion"];
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            $condicionPersona = ($data!=null) ? $data : null;
            return $condicionPersona;
        }
        
        public function getEsquemas(){
            //tipo edad: 2 dias, 3 semanas, 4 meses, 5 años 
            $sql = "select * from vac_esquema where status = 1";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }

        public function getDiasEntreFechas ($fechaOld, $fechaActual = null){
            if ($fechaActual!= null){
                $splitFechaForm = explode("/", $fechaActual);
                $fechaActual = $splitFechaForm[2]."-".$splitFechaForm[1]."-".$splitFechaForm[0]." 00:00:00";
            }
            $fechaActual = ($fechaActual == null) ? time() : strtotime($fechaActual); // or your date as well
            $fechaOld = strtotime($fechaOld);
            $datediff = $fechaActual - $fechaOld;
            return floor($datediff/$this->unDia);
        }
        
        // $flag si es 1 va a sumar, sino resta
        public function setDias ($fecha, $dias = 0, $flag = 1){
            $operador = ($flag===1) ? "+":"-";
            return date('Y-m-d', strtotime($fecha. ' '.$operador.' '.$dias.' days'));
        }  
        
        public function getDosis($idEsquemas, $formDosis){
            $i = 0;
            $sql = "select T1.id_dosis, T1.id_esq_detalle, T1.id_vacuna, T1.tipo_dosis, T1.edad_vac_ini, T1.tipo_edad_vac_ini, 
                    T1.edad_vac_fin, T1.tipo_edad_vac_fin, T1.margen_vac_ini, T1.tipo_margen_vac_ini, T1.margen_vac_fin, T1.tipo_margen_vac_fin
                    FROM vac_dosis T1
                    INNER JOIN vac_esq_detalle T2 ON T1.id_esq_detalle = T2.id_esq_detalle
                    WHERE T2.id_esquema IN (";
            foreach ($idEsquemas as $esquema)
                $sql .= $esquema.",";
            $sql .= "0) and T1.id_dosis not in (";
            foreach ($formDosis as $dosis)
                $sql .= $dosis["id_dosis"].",";
            $sql .= "0)";
            
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        
        public function getDosisFormato($idEsquemas, $formDosis){
            $i = 0;
            $sql = "select T1.id_esq_detalle, T4.id_esquema, T4.nombre_esquema, T1.id_dosis, T1.num_dosis_refuerzo, T3.id_vacuna, T3.nombre_vacuna, concat(T1.edad_vac_ini,' ', 
                    case when T1.tipo_edad_vac_ini = 2 then 'Dias'
                         when T1.tipo_edad_vac_ini = 3 then 'Semanas' 
                         when T1.tipo_edad_vac_ini = 4 then 'Meses'
                         when T1.tipo_edad_vac_ini = 5 then 'Años' else '' end) as edad_ini,
                    case when T1.tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as tipo_dosis,
                    '' as fecha_dosis, '' as numero_lote_1, '' as numero_lote_2, '' as numero_lote_3, 0 as status, 0 as id_registro_diario_dosis
                    from vac_dosis T1
                    inner join vac_esq_detalle T2 on T1.id_esq_detalle = T2.id_esq_detalle
                    left join cat_vacuna T3 on T1.id_vacuna = T3.id_vacuna
                    left join vac_esquema T4 ON T4.id_esquema = T2.id_esquema
                    where T2.id_esquema IN (";
            foreach ($idEsquemas as $esquema)
                $sql .= $esquema.",";
            $sql .= "0) and T1.id_dosis not in (";
            foreach ($formDosis as $dosis)
                $sql .= $dosis["id_dosis"].",";
            $sql .= "0)";
            $sql .= " order by T4.id_esquema, T3.nombre_vacuna, T1.tipo_dosis, T1.num_dosis_refuerzo";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        
        public function getFormDosis($idform){
            $i = 0;
            $sql = "select T2.id_esq_detalle, T5.id_esquema, T5.nombre_esquema, T1.id_dosis, T2.num_dosis_refuerzo, T3.id_vacuna, T3.nombre_vacuna, concat(T2.edad_vac_ini,' ', 
                    case when T2.tipo_edad_vac_ini = 2 then 'Dias'
                         when T2.tipo_edad_vac_ini = 3 then 'Semanas' 
                         when T2.tipo_edad_vac_ini = 4 then 'Meses'
                         when T2.tipo_edad_vac_ini = 5 then 'Años' else '' end) as edad_ini,
                    case when T2.tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as tipo_dosis,
                    DATE_FORMAT(T1.fecha_dosis,'%d/%m/%Y') as fecha_dosis,  numero_lote_1, numero_lote_2, numero_lote_3, 1 as status, T1.id_registro_diario_dosis 
                    from vac_registro_diario_dosis T1
                    left join vac_dosis T2 on T1.id_dosis = T2.id_dosis
                    left join cat_vacuna T3 on T2.id_vacuna = T3.id_vacuna
                    left join vac_esq_detalle T4 on T2.id_esq_detalle = T4.id_esq_detalle
                    left join vac_esquema T5 ON T5.id_esquema = T4.id_esquema
                    where T1.id_vac_registro_diario = ".$idform;
            $sql.= " order by T5.nombre_esquema, T3.nombre_vacuna, T2.tipo_dosis, T2.num_dosis_refuerzo";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        
    }

