<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    require_once('libs/helper/helperVacunas.php');
    
    $resultado = null;    
    
    if (isset ($_POST["id_dosis"])){
        
        $sql = "select T1.*, T2.nombre_un, T3.nombre_modalidad, T5.nombre_vacuna, 
                case when  T4.tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as tipo_dosis, T4.num_dosis_refuerzo, T6.nombre_zona,
                case when T1.per_tipo_edad = 3 then 'Años' 
                     when T1.per_tipo_edad = 2 then 'Meses' 
                     when T1.per_tipo_edad = 1 then 'Días'
                     when T1.per_tipo_edad = 4 then 'Meses' else '' end as tipo_edad,
                DATE_FORMAT(T1.fecha_dosis ,'%d/%m/%Y') as fecha_aplica     
                from vac_registro_diario_dosis T1
                left join cat_unidad_notificadora T2 on T1.id_un = T2.id_un
                left join cat_vac_modalidad T3 on T3.id_modalidad = T1.id_modalidad
                left join vac_dosis T4 on T1.id_dosis = T4.id_dosis
                left join cat_vacuna T5 on T5.id_vacuna = T4.id_vacuna
                left join cat_vac_zona T6 on T6.id_zona = T1.id_zona
                where T1.id_dosis = ".$_POST["id_dosis"];
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        
        if (is_array($data)){
            foreach ($data as $registro){
                $nombre_modalidad = ($registro['nombre_modalidad_otro'] !== "") ? $registro['nombre_modalidad']." - Nombre:".$registro['nombre_modalidad_otro'] : $registro['nombre_modalidad'];
                
                echo " - Instalaci&oacute;n Notificadora: ".$registro['nombre_un'];
                echo "<br/> - Nombre funcionario: ".$registro['nombre_reporta'];
                echo "<br/> - Nombre quien registra: ".$registro['nombre_registra'];
                echo "<br/> - Modalidad: ".$nombre_modalidad;
                echo "<br/> - Zona: ".$registro['nombre_zona'];
                echo "<br/> - Nombre de la Vacuna: ".$registro['nombre_vacuna'];
                echo "<br/> - Fecha de aplicaci&oacute;n: ".$registro['fecha_aplica'];
                echo "<br/> - Edad al vacunarse: ".$registro['per_edad']." ".$registro['tipo_edad'];
                echo "<br/> - Dosis/Refuerzo: ".$registro['num_dosis_refuerzo']." ".$registro['tipo_dosis'];
                echo "<br/> - No. Lote 1: ".$registro['numero_lote_1'];
                echo "<br/> - No. Lote 2: ".$registro['numero_lote_2'];
                echo "<br/> - No. Lote 3: ".$registro['numero_lote_3']."<br/>";
            }
        }
        
    }

